<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use App\Services\InvoiceNumberService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\DataTables\InvoiceDataTable;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\InvoiceBrowseDataTable;
use App\Helpers\Helpers;
use App\Mail\FinanceInvoiceSubmitted;
use App\Mail\FinanceInvoiceDecision;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $isAdmin = (method_exists($user, 'hasRole') && $user->hasRole('super admin'))
            || (method_exists($user, 'can') && $user->can('view all projects'));

        $isWebAdmsProjects = ($user->id == 45);

        $title = 'Generate Invoice';

        $qb = DB::table('projects as p')
            ->join('project_statuses as s', 's.id', '=', 'p.project_status_id')
            ->whereNull('p.deleted_at')
            ->whereRaw('LOWER(s.name) = ?', ['active'])   // case-insensitive "Active"
            ->select('p.id', 'p.project_name', 's.name as status_name')
            ->orderBy('p.project_name', 'asc')            // <- use the alias here
            ->distinct();


        $projects = $qb->get();

        if (!$isAdmin) {
            // Non-admin: restrict to projects assigned in project_user
            if (Schema::hasTable('project_user')) {
                $qb->join('project_user', 'project_user.project_id', '=', 'p.id')
                    ->where('project_user.user_id', $user->id);
            } else {
                // Pivot missing: return empty list for non-admins
                $projects = collect();
                return view('content.invoices.index', compact('projects', 'isAdmin', 'title'));
            }
        }

        $projects = $qb->distinct()
            ->orderBy('p.project_name', 'asc')
            ->get();

        if ($isWebAdmsProjects) {
            return view('content.invoices.webadms.index', compact('projects', 'isAdmin', 'title'));
        }
        return view('content.invoices.index', compact('projects', 'isAdmin', 'title'));
    }

    public function previewData(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            //'month'      => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'], // YYYY-MM
        ]);

        $project = DB::table('projects')
            ->select('id', 'project_name', 'project_category', 'pricing_type', 'customer_id', 'pricing_id')
            ->where('id', $request->integer('project_id'))
            ->first();
        //  dd($project);
        if (!$project) {
            return response()->json(['data' => [], 'message' => 'Project not found.'], 404);
        }

        $cat = (int) ($project->project_category ?? 0);
        $yyyyMm = $request->input('month');

        switch ($cat) {
            case 2:
                // ---- Project context (IV/Dept) ----
                $proj = DB::table('projects as p')
                    ->leftJoin('pricing_masters as pm', 'pm.id', '=', 'p.pricing_id') // legacy project-level pricing (optional)
                    ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                    ->where('p.id', $project->id)
                    ->select([
                        'p.pricing_type',
                        'p.industry_vertical_id',
                        'p.department_id',
                        'pm.rate as project_rate',
                        'cur.name as project_currency_name',
                    ])
                    ->first();

                $ivId  = $proj->industry_vertical_id ?? null;
                $depId = $proj->department_id ?? null;

                // ---- Locked lines (existing invoices) ----
                $locked = DB::table('invoice_lines as il')
                    ->join('invoices as iv', 'iv.id', '=', 'il.invoice_id')
                    ->where('iv.project_id', $project->id)
                    ->where('iv.billing_month', $yyyyMm)
                    ->whereIn('iv.status', ['submitted', 'finance_approved', 'sent'])
                    ->select('il.source_intake_id as intake_id', 'iv.invoice_no', 'iv.status')
                    ->get()
                    ->keyBy('intake_id');

                $deliveredStatusId = DB::table('intake_statuses')
                    ->where('name', 'Delivered')
                    ->value('id');

                [$startDate, $endDate] = (function ($yyyyMm) {
                    $start = Carbon::createFromFormat('m-Y', $yyyyMm)->startOfMonth()->toDateString();
                    $end   = Carbon::createFromFormat('m-Y', $yyyyMm)->endOfMonth()->toDateString();
                    return [$start, $end];
                })($yyyyMm);

                $base = DB::table('project_intakes as pi')
                    ->select(
                        'pi.id',
                        'pi.property_name',
                        'pi.tenant_name',
                        'pi.delivered_date',
                        'pi.cost_usd',
                        'pi.billing_month',
                        'pi.property_id',
                        'pi.type_of_work_id' // service_offering_id
                    )
                    ->where('pi.parent_id', $project->id)
                    ->where('pi.billing_month', $yyyyMm)
                    // delivered_date must fall within the billing month
                    ->whereNotNull('pi.delivered_date')
                    ->whereBetween(DB::raw('DATE(pi.delivered_date)'), [$startDate, $endDate])
                    // all three stages completed
                    ->whereNotNull('pi.abstract_completion_date')
                    ->whereNotNull('pi.review_completion_date')
                    ->whereNotNull('pi.sense_check_completion_date');

                // Filter by Delivered status
                if ($deliveredStatusId) {
                    $base->where('pi.status_master_id', $deliveredStatusId);
                } else {
                    // Fallback (in case status row doesn't exist)
                    $base->join('intake_statuses as ist', 'ist.id', '=', 'pi.status_master_id')
                        ->where('ist.name', 'Delivered');
                }

                $intakes = $base->orderByDesc('pi.id')->get();


                // Currency from the *pricing we actually resolve*, else fallback to project-level currency name (if any)
                // We'll decide per row; but expose a top-level currency name/symbol using project-level as a safe fallback.
                $currencyName   = $proj->project_currency_name ?? '';
                $currencyKey    = strtoupper(trim($currencyName));
                $currencySymbol = config('currency.symbols.' . $currencyKey, '');

                // Helper to resolve a pricing row for a single intake (STANDARD pricing only)
                $resolveApprovedPricing = function ($serviceOfferingId) use ($ivId, $depId, $project) {
                    if (!$ivId || !$depId || !$serviceOfferingId) {
                        return [null, 'Missing IV/Dept/ServiceOffering.'];
                    }
                    if ($project->pricing_type == "fixed") {

                        $row = DB::table('pricing_masters as pm')
                            ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                            ->whereNull('pm.deleted_at')
                            ->where('pm.status', 1)
                            ->where('pm.id', $project->pricing_id)
                            ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                            ->orderByDesc('pm.id')
                            ->first();
                    } else {
                        $row = DB::table('pricing_masters as pm')
                            ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                            ->whereNull('pm.deleted_at')
                            ->where('pm.status', 1)
                            ->where('pm.pricing_type', 'static') // STANDARD
                            ->where('pm.industry_vertical_id', $ivId)
                            ->where('pm.department_id', $depId)
                            ->where('pm.service_offering_id', $serviceOfferingId)
                            ->whereNull('pm.customer_id')
                            ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                            ->orderByDesc('pm.id')
                            ->first();
                    }

                    if (!$row) return [null, 'No approved pricing found for IV/Dept/SO.']; // generic; we’ll keep “approved” wording
                    if ($row->approval_status !== 'approved') {
                        return [$row, 'Pricing approval is pending.'];
                    }
                    return [$row, null];
                };

                $rows = $intakes->map(function ($r) use ($resolveApprovedPricing, $locked, &$currencyName, &$currencySymbol) {
                    $notes = [];

                    // Resolve pricing by combination (IV/Dept/SO) - STANDARD only
                    [$priceRow, $pricingNote] = $resolveApprovedPricing($r->type_of_work_id);
                    if ($pricingNote) $notes[] = $pricingNote;

                    // Rate preference:
                    // - If approved pricing row exists => use its rate
                    // - Else fallback to legacy $r->cost_usd (kept for safety), else null
                    $rate = null;
                    if ($priceRow && $priceRow->approval_status === 'approved') {
                        $rate = (float)$priceRow->rate;
                        if (!empty($priceRow->currency_name)) {
                            $currencyName   = $priceRow->currency_name; // keep the last seen non-empty (top-level display)
                            $currencySymbol = config('currency.symbols.' . strtoupper($currencyName), '');
                        }
                    } else {
                        // fallback: intake’s own cost_usd if given
                        $rate = $r->cost_usd !== null ? (float)$r->cost_usd : null;
                    }

                    $qty   = 1;
                    $value = $rate !== null ? $qty * $rate : null;

                    $lock  = $locked->get($r->id);
                    $status = $lock->status ?? 'pending_invoice';

                    // NOTE for missing invoice
                    if (!$lock) {
                        $notes[] = 'Invoice not created for this month.';
                    }

                    // Pretty status for UI
                    $label = $status ? ucwords(str_replace('_', ' ', $status)) : 'Pending Invoice';
                    $badge = [
                        'submitted'        => 'info',
                        'finance_approved' => 'success',
                        'sent'             => 'primary',
                        'pending_invoice'  => 'secondary',
                    ][$status] ?? 'secondary';

                    return (object)[
                        'id'                 => $r->id,
                        'property_name'      => $r->property_name,
                        'tenant_name'        => $r->tenant_name,
                        'delivered_date'     => Helpers::ymd_to_mdy($r->delivered_date),
                        'billing_month'      => $r->billing_month,
                        'property_id'        => $r->property_id,
                        'service_offering_id' => $r->type_of_work_id,
                        'qty'                => $qty,
                        'rate'               => $rate,
                        'value'              => $value,
                        'locked'             => (bool)$lock,
                        'locked_invoice_no'  => $lock->invoice_no ?? null,
                        'invoice_status'        => $status,
                        'invoice_status_label'  => $label,
                        'invoice_status_badge'  => $badge,
                        'notes'              => implode(' ', array_unique(array_filter($notes))),
                    ];
                });

                $gross = (float) $rows
                    ->where('locked', false)               // only editable rows contribute to draft total
                    ->filter(fn($r) => $r->value !== null) // skip rows with no rate resolved
                    ->sum('value');

                return response()->json([
                    'category' => 2,
                    'source'   => 'project_intakes',
                    'project'  => ['id' => $project->id, 'name' => $project->project_name],
                    'currency' => [
                        'name'   => $currencyName,
                        'symbol' => $currencySymbol,
                    ],
                    'pricing' => [
                        'mode' => 'standard', // explicit
                    ],
                    'data'   => $rows,
                    'totals' => [
                        'gross' => $gross,
                    ],
                ]);
            case 1:

                return response()->json([
                    'category' => 1,
                    'source'   => 'custom_logic_cat1', // change when you implement
                    'project'  => ['id' => $project->id, 'name' => $project->project_name],
                    'data'     => [], // return your computed rows here later
                    'message'  => 'No invoice logic implemented for Category 1 yet.',
                ]);

            case 3:
                // ---------------- Category 3: API-based invoice logic ----------------
                // Parse month and year from MM-YYYY format
                if (!$yyyyMm || !preg_match('/^(\d{2})-(\d{4})$/', $yyyyMm, $matches)) {
                    return response()->json([
                        'category' => 3,
                        'source'   => 'api_delivered_company',
                        'project'  => ['id' => $project->id, 'name' => $project->project_name],
                        'data'     => [],
                        'message'  => 'Invalid month format. Expected MM-YYYY.',
                    ], 422);
                }

                $month = (int) $matches[1];
                $year  = (int) $matches[2];
                //dd($month,$year);
                // Make API call
                try {
                    $apiBaseUrl = env('API_BASE_URL');
                    $apiUrl = rtrim($apiBaseUrl, '/') . '/api/invoice/delivered-company';
                    
                    $response = Http::timeout(15)->get($apiUrl, [
                        'month' => $month,
                        'year'  => $year,
                    ]);

                    if (!$response->successful()) {
                        return response()->json([
                            'category' => 3,
                            'source'   => 'api_delivered_company',
                            'project'  => ['id' => $project->id, 'name' => $project->project_name],
                            'data'     => [],
                            'message'  => 'API request failed: ' . $response->status(),
                        ], 422);
                    }

                    $apiData = $response->json();
                    $totalDelivered = (int) ($apiData['total_delivered'] ?? 0);
                    
                    // If total_delivered is 0, return "No record found" message
                    if ($totalDelivered <= 0) {
                        return response()->json([
                            'category' => 3,
                            'source'   => 'api_delivered_company',
                            'project'  => ['id' => $project->id, 'name' => $project->project_name],
                            'data'     => [],
                            'message'  => 'No record found.',
                        ], 200);
                    }
                    
                    // Category 3: Get pricing from pricing_id directly (check approval_status and status)
                    $pricingRow = null;
                    $rate = null;
                    $currencyName = '';
                    $currencySymbol = '';
                    $pricingNote = '';
                    
                    if ($project->pricing_id) {
                        $pricingRow = DB::table('pricing_masters as pm')
                            ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                            ->whereNull('pm.deleted_at')
                            ->where('pm.status', 1)
                            ->where('pm.id', $project->pricing_id)
                            ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                            ->orderByDesc('pm.id')
                            ->first();

                        if ($pricingRow) {
                            // Check approval status - if not approved, add to notes instead of error
                            if ($pricingRow->approval_status !== 'approved') {
                                $pricingNote = 'Pricing approval is pending. Please approve the pricing before creating invoice.';
                            } else {
                                $rate = (float) ($pricingRow->rate ?? 0);
                                if ($pricingRow->currency_name) {
                                    $currencyName = $pricingRow->currency_name;
                                    $currencyKey = strtoupper(trim($currencyName));
                                    $currencySymbol = config('currency.symbols.' . $currencyKey, '');
                                }
                            }
                        } else {
                            $pricingNote = 'No pricing found for this project.';
                        }
                    } else {
                        $pricingNote = 'No pricing configured for this project.';
                    }
                    
                    // If no rate found, set to 0 and add note
                    if (!$rate) {
                        $rate = 0;
                        if (empty($pricingNote)) {
                            $pricingNote = 'No approved pricing rate found.';
                        }
                    }

                    // Calculate final invoice amount: total_delivered * rate
                    $gross = ($rate !== null && $totalDelivered > 0) 
                        ? (float) ($totalDelivered * $rate) 
                        : 0.0;

                    // Create a single row for the invoice (matching case 2 format)
                    $rows = collect([
                        (object)[
                            'id'                 => 'api_delivered',
                            'property_name'      => 'Data Collection of Indian Companies',
                            'tenant_name'        => '-',
                            'delivered_date'     => Carbon::createFromFormat('m-Y', $yyyyMm)->endOfMonth()->format('m-d-Y'),
                            'billing_month'      => $yyyyMm,
                            'property_id'        => null,
                            'service_offering_id' => null,
                            'qty'                => $totalDelivered,
                            'rate'               => $rate,
                            'value'              => $gross,
                            'locked'             => false,
                            'locked_invoice_no'  => null,
                            'invoice_status'        => 'pending_invoice',
                            'invoice_status_label'  => 'Pending Invoice',
                            'invoice_status_badge'  => 'secondary',
                            'notes'              => $pricingNote,
                        ]
                    ]);

                    return response()->json([
                        'category' => 3,
                        'source'   => 'api_delivered_company',
                        'project'  => ['id' => $project->id, 'name' => $project->project_name],
                        'currency' => [
                            'name'   => $currencyName,
                            'symbol' => $currencySymbol,
                        ],
                        'pricing' => [
                            'mode' => 'fixed', // Category 3 always uses fixed pricing from pricing_id
                        ],
                        'data'   => $rows,
                        'totals' => [
                            'gross' => $gross,
                        ],
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'category' => 3,
                        'source'   => 'api_delivered_company',
                        'project'  => ['id' => $project->id, 'name' => $project->project_name],
                        'data'     => [],
                        'message'  => 'API error: ' . $e->getMessage(),
                    ], 500);
                }

            default:
                return response()->json([
                    'category' => $cat,
                    'project'  => ['id' => $project->id, 'name' => $project->project_name],
                    'data'     => [],
                    'message'  => 'Unsupported project category for invoices.',
                ], 422);
        }
    }
    /**
     * Build invoice preview HTML and RESERVE (store) an invoice number on a draft invoice.
     *
     * Requirements:
     * - invoices.status supports 'draft'
     * - invoices.invoice_no is NOT NULL and UNIQUE
     * - invoices.billing_month is VARCHAR(7) and NOT NULL (we auto-fill if not sent)
     */
    public function previewHtml(Request $request)
    {
        $request->validate([
            'project_id'   => 'required|integer|exists:projects,id',
            'intake_ids'   => 'required|array|min:1',
            'intake_ids.*' => 'integer',
            // Month is REQUIRED and must be MM-YYYY
            'month'        => ['required', 'regex:/^(0[1-9]|1[0-2])-\d{4}$/'], // MM-YYYY
        ]);

        // ---------------- Company info (config) ----------------
        $ci = config('companyinfo');
        $companyName      = $ci['name']        ?? 'Company Name';
        $companyAddress   = $ci['address']     ?? "Address line 1\nCity, State PIN\nContact";
        $companySignatory = $ci['signatory']   ?? 'Authorized Signatory';
        $pan              = $ci['pan']         ?? '';
        $gstin            = $ci['gst_no']      ?? '';
        $referenceNo      = $ci['reference_no'] ?? '';
        $lutNo            = $ci['lut_no']      ?? '';
        $iec              = $ci['iec_code']    ?? '';
        $invDescription   = $ci['description'] ?? '';
        $sacCodeDefault   = "-";

        // ---------------- Month (MM-YYYY) ----------------
        $monthHdr = trim((string) $request->input('month'));
        if (!preg_match('/^(0?[1-9]|1[0-2])-\d{4}$/', $monthHdr)) {
            return response()->json(['html' => '<div>Invalid month (use MM-YYYY).</div>'], 422);
        }
        [$mmStr, $yyyyStr] = explode('-', $monthHdr);
        $mm     = (int) $mmStr;
        $yyyy   = (int) $yyyyStr;
        $monthDb = sprintf('%02d-%04d', $mm, $yyyy); // store consistently as MM-YYYY

        // ---------------- Project (+ IV/Dept, pricing & currency) ----------------
        $project = DB::table('projects as p')
            ->leftJoin('pricing_masters as pm', 'pm.id', '=', 'p.pricing_id')
            ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
            ->where('p.id', $request->project_id)
            ->select([
                'p.id',
                'p.project_name',
                'p.pricing_id',
                'p.customer_id',
                'p.industry_vertical_id',
                'p.department_id',
                'pm.rate as pricing_rate',
                'cur.name as currency_name',
                'p.pricing_type'
            ])
            ->first();

        if (!$project) {
            return response()->json(['html' => '<div>Project not found.</div>'], 404);
        }

        // ---------------- Customer (template selection) ----------------
        $customer = DB::table('companies')
            ->where('id', $project->customer_id)
            ->select('name', 'address', 'company_type', 'location', 'zip_code')
            ->first();

        $customerType = (int)($customer->company_type ?? 1); // 1=India, 2=Non-Indian
        //dd($customerType);
        // ---------------- Currency (keep your variables) ----------------
        $currencyName   = $project->currency_name ?? '';
        $currencyKey    = strtoupper(trim($currencyName));
        $currencySymbol = config('currency.symbols.' . $currencyKey, '');

        // ---------------- Intake IDs (robust) ----------------
        $intakeIds = array_map('intval', (array) $request->input('intake_ids', []));

        // ---------------- Intake rows (include type_of_work_id) ----------------
        $rows = DB::table('project_intakes')
            ->select('id', 'property_name', 'tenant_name', 'cost_usd', 'property_id', 'billing_month', 'type_of_work_id')
            ->where('parent_id', $request->project_id)
            ->whereIn('id', $intakeIds)
            ->where('billing_month', $monthDb)
            ->orderBy('id')
            ->get();

        // If nothing matched (wrong month/ids relation), return a gentle message
        if ($rows->isEmpty()) {
            return response()->json([
                'html' => '<div class="alert alert-warning m-0">No rows found for the selected month and intakes.</div>',
                'invoice_no'    => null,
                'invoice_date'  => null,
                'billing_month' => $monthHdr,
            ]);
        }

        // ---------------- Resolver for STANDARD pricing (approved) ----------------
        // Cache lookups per (iv, dept, so) to avoid N queries
        $pricingCache = [];

        $resolveStandardPricing = function ($ivId, $deptId, $serviceOfferingId) use (&$pricingCache, $project) {
            if (!$ivId || !$deptId || !$serviceOfferingId) return null;
            $key = "{$ivId}|{$deptId}|{$serviceOfferingId}";
            if (array_key_exists($key, $pricingCache)) {
                return $pricingCache[$key];
            }

            if ($project->pricing_type == "fixed") {
                $row = DB::table('pricing_masters as pm')
                    ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                    ->whereNull('pm.deleted_at')
                    ->where('pm.status', 1)
                    ->where('pm.id', $project->pricing_id)
                    ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                    ->orderByDesc('pm.id')
                    ->first();
            } else {
                $row = DB::table('pricing_masters as pm')
                    ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                    ->whereNull('pm.deleted_at')
                    ->where('pm.status', 1)
                    ->where('pm.pricing_type', 'static') // STANDARD
                    ->where('pm.industry_vertical_id', $ivId)
                    ->where('pm.department_id', $deptId)
                    ->where('pm.service_offering_id', $serviceOfferingId)
                    ->whereNull('pm.customer_id')
                    ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                    ->orderByDesc('pm.id')
                    ->first();
            }
            // 

            return $pricingCache[$key] = $row ?: null;
        };

        // ---------------- Project-level static rate (if any) ----------------
        $projectRate = isset($project->pricing_rate) ? (float)$project->pricing_rate : null;

        // ---------------- Service Offering names for descriptions ----------------
        $soIds = $rows->pluck('type_of_work_id')->filter()->unique()->values();
        $soNameById = $soIds->isEmpty()
            ? collect()
            : DB::table('service_offerings')->whereIn('id', $soIds)->pluck('name', 'id');
        $grouped = [];
        foreach ($rows as $r) {
            $soId = (int)($r->type_of_work_id ?? 0);

            // Resolve standard pricing (approved) for (IV, Dept, SO)
            $pm = $resolveStandardPricing(
                $project->industry_vertical_id,
                $project->department_id,
                $soId ?: null
            );

            // If project had no currency, adopt pricing currency (once)
            if (!$currencyName && $pm && $pm->currency_name) {
                $currencyName   = $pm->currency_name;
                $currencyKey    = strtoupper(trim($currencyName));
                $currencySymbol = config('currency.symbols.' . $currencyKey, '');
            }

            // Effective unit rate for THIS row
            $unitRate = ($pm ? (float)$pm->rate : null) ?? $projectRate ?? (float)($r->cost_usd ?? 0);
            $value    = $unitRate; // qty is always 1 per intake row

            if (!isset($grouped[$soId])) {
                $grouped[$soId] = [
                    'count'     => 0,
                    'sum_value' => 0.0,
                    'std_rate'  => $pm ? (float)$pm->rate : null, // remember standard rate if present
                ];
            }
            $grouped[$soId]['count']     += 1;
            $grouped[$soId]['sum_value'] += (float)$value;

            // If we didn't have a std_rate yet and this row had one, keep it
            if ($grouped[$soId]['std_rate'] === null && $pm) {
                $grouped[$soId]['std_rate'] = (float)$pm->rate;
            }
        }

        // ---------------- Build grouped LINES ----------------
        $lines = collect();
        $lineNo = 1;
        foreach ($grouped as $soId => $agg) {
            $qty       = (int)$agg['count'];
            $sumValue  = (float)$agg['sum_value'];
            $displayRate = $agg['std_rate'] ?? ($projectRate ?? ($qty > 0 ? round($sumValue / $qty, 2) : 0.0));

            $soName = $soNameById[(int)$soId] ?? ($soId ? "Service Offering #{$soId}" : "Service Offering");

            $lines->push((object)[
                'sno'         => $lineNo++,
                'description' => $soName,      // <— ONLY service offering name
                'sac'         => $sacCodeDefault,
                'qty'         => $qty,
                'rate'        => (float)$displayRate,
                'value'       => (float)$sumValue,
            ]);
        }

        // ---------------- Totals ----------------
        $subtotal = (float)$lines->sum('value');
        $discount = 0.00;
        $net      = $subtotal - $discount;

        // ---------------- PO number (optional; unchanged) ----------------
        $poNo = '';

        // ---------------- Decide series & date (preview only) ----------------
        $entityCode  = $customerType === 1 ? 'IN' : 'US';
        $invoiceDate = \Carbon\Carbon::createFromFormat('m-Y', $monthDb)->endOfMonth();
        $billingMonth = $monthHdr;

        try {
            $invoiceNo = InvoiceNumberService::next($entityCode, $invoiceDate);
        } catch (\RuntimeException $e) {
            $invoiceNo = 'AUTO-' . now()->format('Ymd-His');
        }

        // ---------------- Render (IND vs US) ----------------
        $view = $customerType === 1
            ? 'content.invoices.preview_ind'
            : 'content.invoices.preview_us';

        $html = view($view, [
            'project'        => $project,
            'month'          => $billingMonth,
            'invoiceNo'      => $invoiceNo,
            'invoiceDt'      => Helpers::ymd_to_mdy($invoiceDate->toDateString()),
            'poNo'           => $poNo,

            // statutory / config
            'gstin'          => $gstin,
            'pan'            => $pan,
            'lutNo'          => $lutNo,
            'iec'            => $iec,
            'referenceNo'    => $referenceNo,

            // company (bank placeholders for preview)
            'company' => [
                'name'      => $companyName,
                'address'   => $companyAddress,
                'due'       => now()->addDays(7)->format('m-d-Y'),
                'signatory' => $companySignatory,
                'bank'      => [
                    'name'    => '—',
                    'account' => '—',
                    'ifsc'    => '—',
                    'branch'  => '—',
                    'swift'   => '—',
                    'routing' => '—',
                    'aba'     => '—',
                ],
            ],

            // bill to
            'billTo' => [
                'name'    => $customer->name ?? 'Customer Name',
                'address' => $customer->address ?? 'Customer Address',
                'customer_zipcode' => ($customer->location ?? '') . ' ' . ($customer->zip_code ?? ''),
            ],

            'lines'  => $lines,
            'totals' => [
                'gross'    => $subtotal,
                'discount' => $discount,
                'net'      => $net,
            ],

            'currency' => [
                'name'   => $currencyName,
                'symbol' => $currencySymbol,
            ],

            // optional fields for templates
            'invoiceDescription' => $invDescription,
            'placeOfSupply'      => '', // IND
            'destination'        => '', // US
        ])->render();

        return response()->json([
            'html'          => $html,
            'invoice_no'    => $invoiceNo,
            'invoice_date'  => $invoiceDate->toDateString(),
            'billing_month' => $billingMonth, // MM-YYYY
        ]);
    }

    /**
     * Build invoice preview HTML for Category 3 (API-based).
     * Uses total_delivered from API and project rate.
     */
    public function previewHtmlCategory3(Request $request)
    {
        $request->validate([
            'project_id'   => 'required|integer|exists:projects,id',
            // Month is REQUIRED and must be MM-YYYY
            'month'        => ['required', 'regex:/^(0[1-9]|1[0-2])-\d{4}$/'], // MM-YYYY
        ]);

        // ---------------- Company info (config) ----------------
        $ci = config('companyinfo');
        $companyName      = $ci['name']        ?? 'Company Name';
        $companyAddress   = $ci['address']     ?? "Address line 1\nCity, State PIN\nContact";
        $companySignatory = $ci['signatory']   ?? 'Authorized Signatory';
        $pan              = $ci['pan']         ?? '';
        $gstin            = $ci['gst_no']      ?? '';
        $referenceNo      = $ci['reference_no'] ?? '';
        $lutNo            = $ci['lut_no']      ?? '';
        $iec              = $ci['iec_code']    ?? '';
        $invDescription   = $ci['description'] ?? '';
        $sacCodeDefault   = "-";

        // ---------------- Month (MM-YYYY) ----------------
        $monthHdr = trim((string) $request->input('month'));
        if (!preg_match('/^(0?[1-9]|1[0-2])-\d{4}$/', $monthHdr)) {
            return response()->json(['html' => '<div>Invalid month (use MM-YYYY).</div>'], 422);
        }
        [$mmStr, $yyyyStr] = explode('-', $monthHdr);
        $mm     = (int) $mmStr;
        $yyyy   = (int) $yyyyStr;
        $monthDb = sprintf('%02d-%04d', $mm, $yyyy); // store consistently as MM-YYYY

        // ---------------- Project (+ pricing & currency) ----------------
        $project = DB::table('projects as p')
            ->leftJoin('pricing_masters as pm', 'pm.id', '=', 'p.pricing_id')
            ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
            ->where('p.id', $request->project_id)
            ->select([
                'p.id',
                'p.project_name',
                'p.pricing_id',
                'p.customer_id',
                'p.industry_vertical_id',
                'p.department_id',
                'pm.rate as pricing_rate',
                'cur.name as currency_name',
                'p.pricing_type'
            ])
            ->first();

        if (!$project) {
            return response()->json(['html' => '<div>Project not found.</div>'], 404);
        }

        // ---------------- Customer (template selection) ----------------
        $customer = DB::table('companies')
            ->where('id', $project->customer_id)
            ->select('name', 'address', 'company_type', 'location', 'zip_code')
            ->first();

        $customerType = (int)($customer->company_type ?? 1); // 1=India, 2=Non-Indian

        // ---------------- Currency ----------------
        $currencyName   = $project->currency_name ?? '';
        $currencyKey    = strtoupper(trim($currencyName));
        $currencySymbol = config('currency.symbols.' . $currencyKey, '');

        // ---------------- API call to get total_delivered ----------------
        try {
            $apiBaseUrl = env('API_BASE_URL');
            $apiUrl = rtrim($apiBaseUrl, '/') . '/api/invoice/delivered-company';
            $response = Http::timeout(15)->get($apiUrl, [
                'month' => $mm,
                'year'  => $yyyy,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'html' => '<div class="alert alert-danger">API request failed: ' . $response->status() . '</div>',
                ], 422);
            }

            $apiData = $response->json();
            $totalDelivered = (int) ($apiData['total_delivered'] ?? 0);

            // If total_delivered is 0, return "No record found" message
            if ($totalDelivered <= 0) {
                return response()->json([
                    'html' => '<div class="alert alert-warning">No record found.</div>',
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'html' => '<div class="alert alert-danger">API error: ' . $e->getMessage() . '</div>',
            ], 500);
        }

        // ---------------- Category 3: Get pricing from pricing_id directly (check approval_status and status) ----------------
        $rate = null;
        $currencyName = '';
        $currencySymbol = '';
        $pricingNote = '';
        
        if ($project->pricing_id) {
            $pricingRow = DB::table('pricing_masters as pm')
                ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                ->whereNull('pm.deleted_at')
                ->where('pm.status', 1)
                ->where('pm.id', $project->pricing_id)
                ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                ->orderByDesc('pm.id')
                ->first();

            if ($pricingRow) {
                // Check approval status - if not approved, add to notes instead of error
                if ($pricingRow->approval_status !== 'approved') {
                    $pricingNote = 'Pricing approval is pending. Please approve the pricing before creating invoice.';
                } else {
                    $rate = (float) ($pricingRow->rate ?? 0);
                    if ($pricingRow->currency_name) {
                        $currencyName = $pricingRow->currency_name;
                        $currencyKey = strtoupper(trim($currencyName));
                        $currencySymbol = config('currency.symbols.' . $currencyKey, '');
                    }
                }
            } else {
                $pricingNote = 'No pricing found for this project.';
            }
        } else {
            $pricingNote = 'No pricing configured for this project.';
        }
        
        // If no rate found, set to 0 and add note
        if (!$rate) {
            $rate = 0;
            if (empty($pricingNote)) {
                $pricingNote = 'No approved pricing rate found.';
            }
        }

        // ---------------- Calculate final invoice amount: total_delivered * rate ----------------
        $gross = ($rate !== null && $totalDelivered > 0) 
            ? (float) ($totalDelivered * $rate) 
            : 0.0;

        // ---------------- Build single LINE ----------------
        $lines = collect([
            (object)[
                'sno'         => 1,
                'description' => 'Data Collection of Indian Companies',
                'sac'         => $sacCodeDefault,
                'qty'         => $totalDelivered,
                'rate'        => (float)$rate,
                'value'       => $gross,
            ]
        ]);

        // ---------------- Totals ----------------
        $subtotal = $gross;
        $discount = 0.00;
        $net      = $subtotal - $discount;

        // ---------------- PO number (optional) ----------------
        $poNo = '';

        // ---------------- Decide series & date (preview only) ----------------
        $entityCode  = $customerType === 1 ? 'IN' : 'US';
        $invoiceDate = \Carbon\Carbon::createFromFormat('m-Y', $monthDb)->endOfMonth();
        $billingMonth = $monthHdr;

        try {
            $invoiceNo = InvoiceNumberService::next($entityCode, $invoiceDate);
        } catch (\RuntimeException $e) {
            $invoiceNo = 'AUTO-' . now()->format('Ymd-His');
        }

        // ---------------- Render (IND vs US) ----------------
        $view = $customerType === 1
            ? 'content.invoices.preview_ind'
            : 'content.invoices.preview_us';

        $html = view($view, [
            'project'        => $project,
            'month'          => $billingMonth,
            'invoiceNo'      => $invoiceNo,
            'invoiceDt'      => Helpers::ymd_to_mdy($invoiceDate->toDateString()),
            'poNo'           => $poNo,
            'pricingNote'    => $pricingNote ?? '', // Pass pricing note to template for category 3

            // statutory / config
            'gstin'          => $gstin,
            'pan'            => $pan,
            'lutNo'          => $lutNo,
            'iec'            => $iec,
            'referenceNo'    => $referenceNo,

            // company (bank placeholders for preview)
            'company' => [
                'name'      => $companyName,
                'address'   => $companyAddress,
                'due'       => now()->addDays(7)->format('m-d-Y'),
                'signatory' => $companySignatory,
                'bank'      => [
                    'name'    => '—',
                    'account' => '—',
                    'ifsc'    => '—',
                    'branch'  => '—',
                    'swift'   => '—',
                    'routing' => '—',
                    'aba'     => '—',
                ],
            ],

            // bill to
            'billTo' => [
                'name'    => $customer->name ?? 'Customer Name',
                'address' => $customer->address ?? 'Customer Address',
                'customer_zipcode' => ($customer->location ?? '') . ' ' . ($customer->zip_code ?? ''),
            ],

            'lines'  => $lines,
            'totals' => [
                'gross'    => $subtotal,
                'discount' => $discount,
                'net'      => $net,
            ],

            'currency' => [
                'name'   => $currencyName,
                'symbol' => $currencySymbol,
            ],

            // optional fields for templates
            'invoiceDescription' => $invDescription,
            'placeOfSupply'      => '', // IND
            'destination'        => '', // US
        ])->render();

        return response()->json([
            'html'          => $html,
            'invoice_no'    => $invoiceNo,
            'invoice_date'  => $invoiceDate->toDateString(),
            'billing_month' => $billingMonth, // MM-YYYY
        ]);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'project_id'  => 'required|integer',
            'month'       => 'required|regex:/^\d{4}-(0[1-9]|1[0-2])$/',
            'intake_ids'  => 'required|array|min:1',
            'intake_ids.*' => 'integer',
            'reason'      => 'nullable|string|max:1000'
        ]);

        return response()->json(['ok' => true, 'message' => 'Rejected successfully']);
    }

    // app/Http/Controllers/InvoiceController.php

    /**
     * Create invoice (header + lines + intake links) from preview selections.
     * One-click Approve flow.
     */

    public function approve(Request $request)
    {
        $data = $request->validate([
            'project_id'   => ['required', 'integer', 'exists:projects,id'],
            // Month is MM-YYYY (to match your preview & UI)
            'month'        => ['required', 'regex:/^(0[1-9]|1[0-2])-\d{4}$/'],
            'intake_ids'   => ['nullable', 'array'],
            'intake_ids.*' => ['integer', 'exists:project_intakes,id'],
        ]);

        $projectId  = (int) $data['project_id'];
        $monthHdr   = trim((string) $data['month']);     // MM-YYYY from UI
        [$mmStr, $yyyyStr] = explode('-', $monthHdr);
        $mm     = (int) $mmStr;
        $yyyy   = (int) $yyyyStr;
        $yyyyMm = sprintf('%02d-%04d', $mm, $yyyy);      // store consistently as MM-YYYY

        $intakeIds  = array_values(array_unique($data['intake_ids'] ?? []));
        $userId     = (int) auth()->id();

        // ---------------- Get project category early ----------------
        $projectCategory = DB::table('projects')
            ->where('id', $projectId)
            ->value('project_category');
        $cat = (int) ($projectCategory ?? 0);

        // ---------------- 0) Company info (config/companyinfo.php) ----------------
        $ci = config('companyinfo', []);
        $companyName      = $ci['name']         ?? 'Company Name';
        $companyAddress   = $ci['address']      ?? "Address line 1\nCity, State PIN\nContact";
        $companyPAN       = $ci['pan']          ?? null;
        $companyGST       = $ci['gst_no']       ?? null;
        $companyLUT       = $ci['lut_no']       ?? null;
        $companyIEC       = $ci['iec_code']     ?? null;
        $companyRefNo     = $ci['reference_no'] ?? null;
        $companySignatory = $ci['signatory']    ?? 'Authorized Signatory';

        // ---------------- 1) Duplicate check using invoice_lines (skip for category 3) ----------------
        if ($cat !== 3) {
            if (empty($intakeIds)) {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Please select at least one intake.',
                ], 422);
            }

            $conflicts = DB::table('invoice_lines as il')
                ->join('invoices as inv', 'inv.id', '=', 'il.invoice_id')
                ->whereIn('il.source_intake_id', $intakeIds)
                ->where('il.billing_month', $yyyyMm)
                ->whereNull('inv.deleted_at')
                ->whereNotIn('inv.status', ['rejected'])
                ->pluck('il.source_intake_id')
                ->unique()
                ->values();

            if ($conflicts->isNotEmpty()) {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Some intakes are already invoiced for this month.',
                    'conflicts' => $conflicts,
                ], 422);
            }
        } else {
            // For category 3, check if invoice already exists for this project and month
            $existingInvoice = DB::table('invoices')
                ->where('project_id', $projectId)
                ->where('billing_month', $yyyyMm)
                ->whereNull('deleted_at')
                ->whereNotIn('status', ['rejected'])
                ->first();

            if ($existingInvoice) {
                return response()->json([
                    'status'    => 0,
                    'message'   => 'Invoice already exists for this project and month.',
                ], 422);
            }
        }

        // ---------------- 2) Project (+ pricing & currency) ----------------
        $project = DB::table('projects as p')
            ->leftJoin('pricing_masters as pm', 'pm.id', '=', 'p.pricing_id')
            ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
            ->leftJoin('companies as co', 'co.id', '=', 'p.customer_id')
            ->where('p.id', $projectId)
            ->select([
                'p.id',
                'p.parent_id',
                'p.customer_id',
                'p.industry_vertical_id',
                'p.department_id',
                'p.pricing_id',
                'p.pricing_type',
                'pm.rate as pricing_rate',
                'cur.name as currency_name',
                'co.name as customer_name',
                'co.address as customer_address',
                'co.location as location',
                'co.zip_code as zip_code',
                'co.company_type as customer_type', // 1=India, 2=Non-India
                'co.invoice_type as invoice_type',
            ])
            ->first();

        if (!$project) {
            return response()->json(['status' => 0, 'message' => 'Project not found.'], 404);
        }

        $projectRate     = isset($project->pricing_rate) ? (float) $project->pricing_rate : null;
        $currencyName    = $project->currency_name ?? '';
        $currencyKey     = strtoupper(trim($currencyName));
        $currencySymbol  = config('currency.symbols.' . $currencyKey, '');
        $customerId      = $project->customer_id ?? null;
        $customerName    = $project->customer_name ?? 'Customer';
        $customerAddress = $project->customer_address ?? '';
        $customerType    = (int)($project->customer_type ?? 1);
        $customer_zipcode = ($project->location ?? '') . ' ' . ($project->zip_code ?? '');
        $invoice_type    = $project->invoice_type ?? null;

        // ---------------- 3) Handle category-specific logic ----------------
        $intakes = collect();
        $soNameById = collect();
        $totalDelivered = 0;

        switch ($cat) {
            case 3:
                // Category 3: Get data from API
                try {
                    $apiBaseUrl = env('API_BASE_URL');
                    $apiUrl = rtrim($apiBaseUrl, '/') . '/api/invoice/delivered-company';
                    $response = Http::timeout(15)->get($apiUrl, [
                        'month' => $mm,
                        'year'  => $yyyy,
                    ]);

                    if (!$response->successful()) {
                        return response()->json([
                            'status'  => 0,
                            'message' => 'API request failed: ' . $response->status(),
                        ], 422);
                    }

                    $apiData = $response->json();
                    $totalDelivered = (int) ($apiData['total_delivered'] ?? 0);

                    if ($totalDelivered <= 0) {
                        return response()->json([
                            'status'  => 0,
                            'message' => 'No deliveries found for the selected month.',
                        ], 422);
                    }
                } catch (\Exception $e) {
                    return response()->json([
                        'status'  => 0,
                        'message' => 'API error: ' . $e->getMessage(),
                    ], 500);
                }

                // Category 3: Get pricing from pricing_id directly (no IV/Dept/SO checks)
                if ($project->pricing_id) {
                    $pricingRow = DB::table('pricing_masters as pm')
                        ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                        ->whereNull('pm.deleted_at')
                        ->where('pm.status', 1)
                        ->where('pm.id', $project->pricing_id)
                        ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                        ->orderByDesc('pm.id')
                        ->first();

                    if (!$pricingRow) {
                        return response()->json([
                            'status'  => 0,
                            'message' => 'Pricing not found for this project.',
                        ], 422);
                    }

                    if ($pricingRow->approval_status !== 'approved') {
                        return response()->json([
                            'status'  => 0,
                            'message' => 'Pricing approval is pending. Please approve the pricing before creating invoice.',
                        ], 422);
                    }

                    // Update rate and currency from pricing
                    $projectRate = (float) ($pricingRow->rate ?? 0);
                    if ($pricingRow->currency_name) {
                        $currencyName   = $pricingRow->currency_name;
                        $currencyKey    = strtoupper(trim($currencyName));
                        $currencySymbol = config('currency.symbols.' . $currencyKey, '');
                    }
                } else {
                    return response()->json([
                        'status'  => 0,
                        'message' => 'No pricing configured for this project.',
                    ], 422);
                }
                break;

            default:
                // Category 2 (and others): Fetch the selected intakes
                if (empty($intakeIds)) {
                    return response()->json(['status' => 0, 'message' => 'Please select at least one intake.'], 422);
                }

                $intakes = DB::table('project_intakes')
                    ->whereIn('id', $intakeIds)
                    ->where('billing_month', $yyyyMm)
                    ->get(['id', 'type_of_work_id', 'cost_usd']);

                if ($intakes->isEmpty()) {
                    return response()->json(['status' => 0, 'message' => 'No rows found for the selected month and intakes.'], 422);
                }

                // Service offering names for description
                $soIds = $intakes->pluck('type_of_work_id')->filter()->unique()->values();
                $soNameById = $soIds->isEmpty()
                    ? collect()
                    : DB::table('service_offerings')->whereIn('id', $soIds)->pluck('name', 'id');
                break;
        }

        // ---------------- 3.1) Resolve PO (optional) ----------------
        $poNo = '';
       
        // ---------------- 4) Invoice number (FY-based) ----------------
        $entityCode = $customerType === 1 ? 'IN' : 'US';
        $seriesDate = \Carbon\Carbon::createFromFormat('m-Y', $yyyyMm)->endOfMonth();
        try {
            $invoiceNo = InvoiceNumberService::next($entityCode, $seriesDate);
        } catch (\RuntimeException $e) {
            $invoiceNo = 'AUTO-' . now()->format('Ymd-His');
        }

        // ---------------- Pricing resolver (approved standard) ----------------
        // cache per (iv,dept,so)
        $pricingCache = [];
        $resolveStandardPricing = function ($ivId, $deptId, $serviceOfferingId) use (&$pricingCache,$project) {
            if (!$ivId || !$deptId || !$serviceOfferingId) return null;
            $key = "{$ivId}|{$deptId}|{$serviceOfferingId}";
            if (array_key_exists($key, $pricingCache)) {
                return $pricingCache[$key];
            }
            // $row = DB::table('pricing_masters as pm')
            //     ->leftJoin('currencies as c', 'c.id', '=', 'pm.currency_id')
            //     ->whereNull('pm.deleted_at')
            //     ->where('pm.status', 1)
            //     ->where('pm.approval_status', 'approved')
            //     ->where('pm.pricing_type', 'static') // "standard"
            //     ->where('pm.industry_vertical_id', $ivId)
            //     ->where('pm.department_id', $deptId)
            //     ->where('pm.service_offering_id', $serviceOfferingId)
            //     ->orderByDesc('pm.id')
            //     ->select(['pm.rate', 'c.id as currency_id', 'c.name as currency_name'])
            //     ->first();
             if ($project->pricing_type == "fixed") {
                $row = DB::table('pricing_masters as pm')
                    ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                    ->whereNull('pm.deleted_at')
                    ->where('pm.status', 1)
                    ->where('pm.id', $project->pricing_id)
                    ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                    ->orderByDesc('pm.id')
                    ->first();
            } else {
                $row = DB::table('pricing_masters as pm')
                    ->leftJoin('currencies as cur', 'cur.id', '=', 'pm.currency_id')
                    ->whereNull('pm.deleted_at')
                    ->where('pm.status', 1)
                    ->where('pm.pricing_type', 'static') // STANDARD
                    ->where('pm.industry_vertical_id', $ivId)
                    ->where('pm.department_id', $deptId)
                    ->where('pm.service_offering_id', $serviceOfferingId)
                    ->whereNull('pm.customer_id')
                    ->select('pm.id', 'pm.rate', 'pm.approval_status', 'cur.name as currency_name')
                    ->orderByDesc('pm.id')
                    ->first();
            }

            return $pricingCache[$key] = $row ?: null;
        };

        // If project currency is missing, adopt the first standard pricing currency we find (only for non-category 3)
        if (!$currencyName) {
            if ($cat !== 3) {
                foreach ($intakes as $r) {
                    $pm = $resolveStandardPricing($project->industry_vertical_id, $project->department_id, $r->type_of_work_id);
                    if ($pm && $pm->currency_name) {
                        $currencyName   = $pm->currency_name;
                        $currencyKey    = strtoupper(trim($currencyName));
                        $currencySymbol = config('currency.symbols.' . $currencyKey, '');
                        break;
                    }
                }
            }
        }

        // ---------------- 5) Create Invoice + Lines ----------------
        $invoiceId = DB::transaction(function () use (
            $userId,
            $projectId,
            $customerId,
            $customerName,
            $customerAddress,
            $customerType,
            $invoice_type,
            $yyyyMm,
            $poNo,
            $projectRate,
            $currencyName,
            $currencySymbol,
            $intakes,
            $companyName,
            $companyAddress,
            $companyPAN,
            $companyGST,
            $companyLUT,
            $companyIEC,
            $companyRefNo,
            $companySignatory,
            $invoiceNo,
            $project,
            $soNameById,
            $resolveStandardPricing,
            $customer_zipcode,
            $cat,
            $totalDelivered
        ) {
            $subtotal = 0.0;
            $discount = 0.0;
            $taxTotal = 0.0;

            // ---- Create invoice header ----
            $invId = DB::table('invoices')->insertGetId([
                'project_id'        => $projectId,
                'customer_id'       => $customerId,
                'billing_month'     => $yyyyMm,
                'po_number'         => $poNo,
                'invoice_no'        => $invoiceNo,
                'invoice_date'      => now()->toDateString(),
                'due_date'          => now()->addDays(7)->toDateString(),
                'status'            => 'submitted',
                'created_by'        => $userId,
                'updated_by'        => $userId,
                'assigned_to'       => null,
                'invoice_type'      => $invoice_type,

                // currency
                'currency_name'     => $currencyName,
                'currency_symbol'   => $currencySymbol,

                // money (will be updated)
                'subtotal'          => 0,
                'discount'          => 0,
                'total'             => 0,

                'gross_total'       => 0,
                'discount_total'    => 0,
                'tax_total'         => 0,
                'net_total'         => 0,

                // company snapshot
                'company_name'      => $companyName,
                'company_address'   => $companyAddress,
                'company_pan'       => $companyPAN,
                'company_gstin'     => $companyGST,
                'company_lut_no'    => $companyLUT,
                'company_iec'       => $companyIEC,
                'company_reference_no' => $companyRefNo,
                'company_signatory' => $companySignatory,

                // customer snapshot
                'customer_name'     => $customerName,
                'customer_address'  => $customerAddress,
                'customer_type'     => $customerType,
                'customer_zipcode'  => $customer_zipcode,

                'description'       => null,

                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // ---- Lines (category-specific logic) ----
            $sno = 1;
            
            if ($cat === 3) {
                // Category 3: Single line from API data
                $rate = $projectRate ?? 0.00;
                $qty  = (float) $totalDelivered;
                $val  = $qty * $rate;

                DB::table('invoice_lines')->insert([
                    'invoice_id'       => $invId,
                    'project_id'       => $projectId,
                    'billing_month'    => $yyyyMm,
                    'sno'              => $sno++,
                    'description'      => 'Data Collection of Indian Companies',
                    'sac'              => null,
                    'qty'              => $qty,
                    'rate'             => $rate,
                    'value'            => $val,
                    'source_intake_id' => null, // No intake ID for category 3
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                $subtotal = $val;
            } else {
                // Category 2 (and others): PER INTAKE; description = Service Offering name
                foreach ($intakes as $r) {
                    $soId   = (int)($r->type_of_work_id ?? 0);
                    $soName = $soNameById[$soId] ?? ($soId ? "Service Offering #{$soId}" : "Service Offering");

                    // Resolve approved standard pricing for (IV, Dept, SO)
                    $pm = $resolveStandardPricing(
                        $project->industry_vertical_id,
                        $project->department_id,
                        $soId ?: null
                    );

                    $rate = ($pm ? (float)$pm->rate : null) ?? $projectRate ?? (float)($r->cost_usd ?? 0.00);
                    $qty  = 1.00;
                    $val  = $qty * $rate;

                    DB::table('invoice_lines')->insert([
                        'invoice_id'       => $invId,
                        'project_id'       => $projectId,
                        'billing_month'    => $yyyyMm,
                        'sno'              => $sno++,
                        'description'      => $soName,     // <— ONLY service offering name
                        'sac'              => null,
                        'qty'              => $qty,
                        'rate'             => $rate,
                        'value'            => $val,
                        'source_intake_id' => $r->id,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);

                    $subtotal += $val;
                }
            }

            // ---- Totals ----
            $gstPercent   = (float) config('companyinfo.gst_percent', 18);
            $applyGST     = ((int)$customerType === 1) ? 1 : 0;
            $taxTotal     = $applyGST ? round($subtotal * ($gstPercent / 100), 2) : 0.00;
            $grossTotal   = $subtotal;
            $discTotal    = $discount;
            $netTotal     = round($subtotal - $discTotal + $taxTotal, 2);
            $total        = $netTotal;

            DB::table('invoices')->where('id', $invId)->update([
                'subtotal'       => round($subtotal, 2),
                'discount'       => round($discount, 2),
                'total'          => $total,

                'gross_total'    => round($grossTotal, 2),
                'discount_total' => round($discTotal, 2),
                'tax_total'      => round($taxTotal, 2),
                'net_total'      => $netTotal,

                'updated_by'     => $userId,
                'updated_at'     => now(),
            ]);

            return $invId;
        });
        DB::afterCommit(function () use ($invoiceId) {
        // Pull a compact snapshot for the email
        $inv = DB::table('invoices as i')
            ->leftJoin('projects as p', 'p.id', '=', 'i.project_id')
            ->select([
                'i.id', 'i.invoice_no', 'i.billing_month', 'i.total', 'i.currency_symbol',
                'i.customer_name', 'i.invoice_date', 'i.po_number',
                'p.project_name',
            ])
            ->where('i.id', $invoiceId)
            ->first();

        if (!$inv) return;

        $hash      = Crypt::encryptString((string) $inv->id);
        $reviewUrl = route('invoices.show', ['id' => $hash]);

        // Dummy finance recipients (keep for testing)
        $financeTo = env('FINANCE_EMAIL');
        
        // Optional brand assets (used by your styled template)
        $brandName = config('app.name', 'Springbord');
        $logoUrl   = asset('assets/img/logo.svg');

        Mail::to($financeTo)
            ->queue(new FinanceInvoiceSubmitted(
                invoiceId:      (int)$inv->id,
                invoiceNo:      (string)$inv->invoice_no,
                projectName:    (string)($inv->project_name ?? 'Project'),
                billingMonth:   (string)$inv->billing_month,
                totalFormatted: ($inv->currency_symbol ?: '').number_format((float)$inv->total, 2),
                customerName:   (string)$inv->customer_name,
                poNumber:       (string)($inv->po_number ?? ''),
                invoiceDate:    (string)$inv->invoice_date,
                reviewUrl:      $reviewUrl,          // ← encrypted route
                brandName:      $brandName,          // ← pass brand (new)
                logoUrl:        $logoUrl             // ← pass logo  (new)
            ));
    });

        return response()->json([
            'status'     => 1,
            'message'    => 'Invoice created.',
            'invoice_id' => $invoiceId,
            'redirect'   => route('invoices.general'),
        ], 201);
    }

    public function show($id)
    {
        $decryptedId = Crypt::decryptString($id);

        /** @var \App\Models\Invoice $invoice */
        $invoice = Invoice::with(['project', 'customer'])->findOrFail($decryptedId);

        // ---------------- Billing month window (invoice stores MM-YYYY) ----------------
        $billingMonth = (string) $invoice->billing_month; // MM-YYYY
        try {
            $dtStart = \Carbon\Carbon::createFromFormat('m-Y', $billingMonth)->startOfMonth();
            $dtEnd   = $dtStart->copy()->endOfMonth();
        } catch (\Throwable $e) {
            // Fallback if stored format is off; won’t crash page
            $dtStart = now()->startOfMonth();
            $dtEnd   = now()->endOfMonth();
        }

        // ---------------- Parent/sub-project & POC emails ----------------
        $projectId = (int) $invoice->project_id;

        $projMeta = \App\Models\Project::with([
            'pocs' => function ($q) {
                $q->select('users.id', 'users.email');
            }
        ])->findOrFail($projectId, ['id', 'parent_id']);

        $pocEmailsCsv = $projMeta->pocs
            ->pluck('email')
            ->filter()
            ->unique()
            ->implode(', ');

        $parentId     = $projMeta && $projMeta->parent_id ? (int) $projMeta->parent_id : $projectId;
        $subProjectId = $projMeta && $projMeta->parent_id ? $projectId : null;

        // ---------------- PO number (date overlap + parent/sub-project) ----------------
        $poNo = '';
        try {
            $poQ = \App\Models\PoNumber::query()
                ->whereNull('deleted_at')
                ->where('status', 1)
                ->where('project_id', $parentId)
                ->whereDate('start_date', '<=', $dtEnd->toDateString())
                ->whereDate('end_date',   '>=', $dtStart->toDateString());

            if (is_null($subProjectId)) {
                $poQ->whereNull('sub_project_id');
            } else {
                $poQ->where('sub_project_id', $subProjectId);
            }

            $poNo = (string) ($poQ->orderByDesc('id')->value('po_number') ?? '');
        } catch (\Throwable $e) {
            // keep $poNo = ''
        }

        // ---------------- RAW lines (kept for audit/exports) ----------------
        $rawLines = \App\Models\InvoiceLine::where('invoice_id', $invoice->id)
            ->orderBy('sno')
            ->get();

        /**
         * ---------------- GROUPED display lines ----------------
         * We group by DESCRIPTION + RATE to avoid merging lines that
         * accidentally have different rates for the same service offering.
         * qty = sum(qty), value = sum(value); keep first sno for stable order.
         */
        $grouped = $rawLines
            ->groupBy(function ($r) {
                // Null-safe keys
                $desc = (string) ($r->description ?? '');
                $rate = number_format((float) ($r->rate ?? 0), 6, '.', ''); // normalize as string
                return $desc . '||' . $rate;
            })
            ->map(function ($items) use ($invoice) {
                /** @var \Illuminate\Support\Collection $items */
                $first = $items->first();

                $qtySum   = (float) $items->sum(function ($x) {
                    return (float) ($x->qty ?? 0);
                });
                $valueSum = (float) $items->sum(function ($x) {
                    return (float) ($x->value ?? 0);
                });

                // Keep minimal sno among grouped items for ordering
                $minSno = (int) $items->min('sno');

                // Use invoice-level SAC if line SAC is null/empty
                $sacValue = $first->sac ?? null;
                if (empty($sacValue) && !empty($invoice->sac_number)) {
                    $sacValue = $invoice->sac_number;
                }

                return (object) [
                    'sno'         => $minSno,
                    'description' => (string) ($first->description ?? ''),
                    'sac'         => $sacValue,
                    'qty'         => $qtySum,
                    'rate'        => (float) ($first->rate ?? 0),
                    'value'       => $valueSum,
                ];
            })
            ->sortBy('sno')
            ->values();

        // Banks (unchanged)
        $banks = \App\Models\Bank::where('status', 1)
            ->orderBy('bank_name')
            ->get(['id', 'bank_name']);

        return view('content.invoices.show', [
            'title'           => 'Invoice #' . $invoice->invoice_no,
            'invoice'         => $invoice,
            // Use grouped lines for display
            'lines'           => $grouped,
            // If you still need the originals in the blade (e.g., for a hidden debug tab), pass them too:
            'rawLines'        => $rawLines,
            'banks'           => $banks,
            'poNo'            => $poNo,
            'customer_emails' => $pocEmailsCsv,
        ]);
    }

    /**
     * DataTables JSON feed
     */
    public function invoiceDatatable(InvoiceDataTable $dataTable)
    {
        $this->authorize('list invoice'); // or your can('list invoice')

        // For filter dropdowns
        $projects  = Project::orderBy('project_name')->get(['id', 'project_name']);
        $customers = Company::orderBy('name')->get(['id', 'name']);

        return $dataTable->render('content.invoices.invoicetable', [
            'title'     => 'Invoices',
            'projects'  => $projects,
            'customers' => $customers,
        ]);
    }


    public function financeDecision(Request $request, $invoiceId)
    {

        $user = $request->user();

        /** @var \App\Models\Invoice $invoice */
        $invoice = Invoice::query()
            ->with(['project', 'customer'])
            ->findOrFail($invoiceId);

        if (! in_array($invoice->status, ['submitted'])) {
            return response()->json([
                'status'  => 0,
                'message' => 'Invoice is not in an actionable state.',
            ], 422);
        }

        $data = $request->validate([
            'action'            => ['required', Rule::in(['approve', 'reject'])],
            'bank_id'           => ['nullable', 'integer', 'exists:banks,id', 'required_if:action,approve'],
            'payment_completed' => ['nullable', 'boolean'],
            'sac_number'        => ['nullable', Rule::in(['998311', '998313']), 'required_if:action,approve'],
            'finance_notes'     => ['nullable', 'string', 'max:2000'],
            'poNumber'         => ['nullable'],
            // Accept legacy "note" too; we’ll merge below
            'note'              => ['nullable', 'string', 'max:2000'],
        ], [
            'bank_id.required_if'    => 'Please select a bank before approving.',
            'sac_number.required_if' => 'Please select a SAC number before approving.',
            'sac_number.in'          => 'SAC number must be 998311 or 998313.',
        ]);

        // Merge legacy `note` into `finance_notes` if needed
        $financeNotes = $data['finance_notes'] ?? null;
        if ($financeNotes === null && isset($data['note'])) {
            $financeNotes = $data['note'];
        }

        DB::transaction(function () use ($invoice, $data, $user, $financeNotes) {
            $updates = [
                'updated_by'    => $user?->id,
                'finance_notes' => $financeNotes,                     // <-- save notes always
            ];

            // Persist SAC if sent (approve requires it; reject may optionally send)
            if (array_key_exists('sac_number', $data)) {
                $updates['sac_number'] = $data['sac_number'];
            }

            if ($data['action'] === 'approve') {
                $updates['status']            = 'finance_approved';
                $updates['bank_id']           = (int) $data['bank_id'];
                $updates['payment_completed'] = !empty($data['payment_completed']) ? 1 : 0;
                $updates['po_number']     = $data['poNumber'] ?? null;
                if (empty($invoice->invoice_date)) {
                    $updates['invoice_date'] = now()->toDateString();
                }
                if (empty($invoice->due_date)) {
                    $updates['due_date'] = now()->addDays(7)->toDateString();
                }
            } else {
                // Reject: still save finance_notes (and optional bank/sac if provided)
                $updates['status']            = 'rejected';
                $updates['payment_completed'] = 0;
                if (!empty($data['bank_id'])) {
                    $updates['bank_id'] = (int) $data['bank_id'];
                }
            }

            $invoice->fill($updates)->save();
            if ($data['action'] === 'approve') {
                $this->downloadPdf($invoice->id);
            }

            // Reload invoice with relationships for email notification
            $invoice->refresh();
            $invoice->load(['project', 'customer']);

            // Send email notification to project managers
            $this->notifyProjectManagers($invoice, $data['action'], $financeNotes);
        });

        return response()->json([
            'status'   => 1,
            'message'  => $data['action'] === 'approve' ? 'Invoice approved.' : 'Invoice rejected.',
            'redirect' => route('invoices.show',  Crypt::encryptString($invoice->id)),
        ]);
    }

    /**
     * Send email notification to project managers when invoice is approved/rejected
     */
    private function notifyProjectManagers($invoice, $action, $financeNotes = null)
    {
        try {
            // Get project ID (handle parent/sub-project)
            $projectId = $invoice->project_id;
            $project = Project::with('parent')->find($projectId);
            
            // Use parent project if this is a sub-project
            $parentProjectId = $project && $project->parent_id ? $project->parent_id : $projectId;
            
            // Get project managers from project_user table
            $pmUserIds = DB::table('project_user')
                ->where('project_id', $parentProjectId)
                ->pluck('user_id')
                ->unique()
                ->values()
                ->all();

            if (empty($pmUserIds)) {
                return; // No project managers to notify
            }

            // Get project manager users with emails
            $projectManagers = User::whereIn('id', $pmUserIds)
                ->whereNotNull('email')
                ->get();

            if ($projectManagers->isEmpty()) {
                return; // No valid emails found
            }

            // Prepare invoice data
            $invoiceNo = $invoice->invoice_no ?? 'N/A';
            $projectName = $invoice->project->project_name ?? 'N/A';
            $billingMonth = $invoice->billing_month ?? 'N/A';
            $customerName = $invoice->customer_name ?? 'N/A';
            
            // Format total
            $totalFormatted = ($invoice->currency_symbol ?? '$') . ' ' . number_format((float)($invoice->net_total ?? $invoice->total ?? 0), 2);
            
            // Review URL
            $reviewUrl = route('invoices.show', Crypt::encryptString($invoice->id));
            
            // Brand info
            $cfg = config('companyinfo');
            $brand = Arr::get($cfg, 'brand', []);
            $brandName = $cfg['name'] ?? 'Springbord';
            $logoUrl = $cfg['logo_url'] ?? asset('assets/img/logo.svg');

            // Send email to each project manager
            foreach ($projectManagers as $pm) {
                if ($pm->email) {
                    Mail::to($pm->email)->queue(new FinanceInvoiceDecision(
                        invoiceId: $invoice->id,
                        invoiceNo: $invoiceNo,
                        projectName: $projectName,
                        billingMonth: $billingMonth,
                        totalFormatted: $totalFormatted,
                        customerName: $customerName,
                        action: $action === 'approve' ? 'approved' : 'rejected',
                        financeNotes: $financeNotes,
                        reviewUrl: $reviewUrl,
                        brandName: $brandName,
                        logoUrl: $logoUrl
                    ));
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail the transaction
            Log::error('Failed to send invoice decision notification to project managers', [
                'invoice_id' => $invoice->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function downloadPdf($id)
    {
        $invoice = Invoice::with([
            'project:id,project_name,project_category',
            'lines' => fn($q) => $q->orderBy('sno'),
            'bank'
        ])->findOrFail($id);

        // Get project category
        $projectCategory = (int) ($invoice->project->project_category ?? 0);

        // ==== GROUP LINES FOR DISPLAY (Description + Rate) ====
        $groupedLines = $invoice->lines
            ->groupBy(function ($r) {
                $desc = (string) ($r->description ?? '');
                $rate = number_format((float) ($r->rate ?? 0), 6, '.', '');
                return $desc . '||' . $rate;
            })
            ->map(function ($items) {
                $first    = $items->first();
                $qtySum   = (float) $items->sum(fn($x) => (float) ($x->qty ?? 0));
                $valueSum = (float) $items->sum(fn($x) => (float) ($x->value ?? 0));
                $minSno   = (int) $items->min('sno');

                return (object)[
                    'sno'         => $minSno,
                    'description' => (string) ($first->description ?? ''),
                    'sac'         => $first->sac ?? null,
                    'qty'         => $qtySum,
                    'rate'        => (float) ($first->rate ?? 0),
                    'value'       => $valueSum,
                ];
            })
            ->sortBy('sno')
            ->values();

        // Resolve company info (freeze from invoice if present, else config)
        $cfg   = config('companyinfo');
        $brand = Arr::get($cfg, 'brand', []);
        $bank  = $invoice->bank ?? '';
        
        // For category 3, create billing details rows from invoice lines (not from project_intakes)
        if ($projectCategory === 3) {
            $rows = collect();
            $sno = 1;
            foreach ($invoice->lines as $line) {
                $rows->push((object)[
                    'sno'            => $sno++,
                    'project_name'  => $line->description ?? 'Data Collection of Indian Companies',
                    'billable_units' => (float) ($line->qty ?? 0),
                    'unit_rate'     => (float) ($line->rate ?? 0),
                    'billable_value' => (float) ($line->value ?? 0),
                ]);
            }
        } else {
            // For other categories, use the existing detailed rows from project_intakes
            $rows = DB::table('invoices as inv')
                ->join('invoice_lines as il', 'il.invoice_id', '=', 'inv.id')
                ->leftJoin('project_intakes as pi', 'pi.id', '=', 'il.source_intake_id')
                ->leftJoin('service_offerings as so', 'so.id', '=', 'pi.type_of_work_id')
                ->leftJoin('projects as pr', 'pr.id', '=', 'inv.project_id')
                ->leftJoin('companies as co', 'co.id', '=', 'inv.customer_id')
                // comment this join if you don't have the table
                ->leftJoin('intake_statuses as sm', 'sm.id', '=', 'pi.status_master_id')
                ->where('inv.id', $id) // <- set $invoiceId
                ->orderBy('il.sno')
                ->orderBy('il.id')
                ->get([
                    DB::raw('inv.billing_month AS month_year'),
                    DB::raw('co.name AS client_name'),
                    DB::raw('pr.project_name AS project_name'),
                    DB::raw('pi.property_name AS property_name'),
                    DB::raw('pi.tenant_name AS tenant_name'),
                    DB::raw('pi.premises_address AS address'),
                    DB::raw("'' AS city"),    // not stored
                    DB::raw("'' AS state"),   // not stored
                    DB::raw("'' AS country"), // not stored
                    // If status_masters exists:
                    DB::raw("COALESCE(sm.name, CONCAT('ID:', pi.status_master_id)) AS intake_status"),
                    // Else: DB::raw('pi.status_master_id AS intake_status'),
                    DB::raw('pi.delivered_date AS delivery_date'),
                    DB::raw('so.name AS type_of_work'),
                    DB::raw('pi.language_code AS language'),
                    DB::raw('il.rate AS cost'),
                ]);
        }
        $company = [
            'name'        => $invoice->company_name     ?: ($cfg['name'] ?? ''),
            'address'     => $invoice->company_address  ?: ($cfg['address'] ?? ''),
            'pan'         => $invoice->company_pan      ?: ($cfg['pan'] ?? null),
            'gst'         => $invoice->company_gstin    ?: ($cfg['gst_no'] ?? null),
            'lut'         => $invoice->company_lut_no   ?: ($cfg['lut_no'] ?? null),
            'iec'         => $invoice->company_iec      ?: ($cfg['iec_code'] ?? null),
            'reference'   => $invoice->company_reference_no ?: ($cfg['reference_no'] ?? null),
            'signatory'   => $invoice->company_signatory    ?: ($cfg['signatory'] ?? 'Authorized Signatory'),
            'logo_url'    => $cfg['logo_url'] ?? null,
            'brand'       => [
                'primary' => $brand['primary'] ?? '#e35205',
                'accent'  => $brand['accent']  ?? '#f1f5ff',
                'text'    => $brand['text']    ?? '#333333',
                'muted'   => $brand['muted']   ?? '#6c757d',
                'border'  => $brand['border']  ?? '#e5e7eb',
            ],
        ];

        // Currency (fallbacks)
        $currencySymbol = $invoice->currency_symbol ?: '$';
        $currencyName   = $invoice->currency_name   ?: 'USD';

        // Totals (prefer header; else sum of GROUPED values)
        $subtotal = $invoice->subtotal > 0
            ? (float) $invoice->subtotal
            : (float) $groupedLines->sum('value');

        $discount = (float) ($invoice->discount_total ?: $invoice->discount ?: 0);

        $cfgGstPct = (float) (config('companyinfo.gst_percent') ?? 18);
        $taxBase   = max(0, $subtotal - $discount);

        $gstAmount = $invoice->tax_total > 0
            ? (float) $invoice->tax_total
            : round($taxBase * ($cfgGstPct / 100), 2);

        $grandTotal = $invoice->net_total > 0
            ? (float) $invoice->net_total
            : round($taxBase + $gstAmount, 2);

        $viewData = [
            'invoice'        => $invoice,
            'lines'          => $groupedLines,
            'company'        => $company,
            'currencySymbol' => $currencySymbol,
            'currencyName'   => $currencyName,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'bank'           => $bank,
            'gstPercent'     => $invoice->tax_total > 0 && $taxBase > 0
                ? round(($gstAmount / $taxBase) * 100, 2)
                : $cfgGstPct,
            'gstAmount'      => $gstAmount,
            'grandTotal'     => $grandTotal,
            'rows'           => $rows,
            'projectCategory' => $projectCategory
        ];

        // Build PDF
        $pdf = Pdf::loadView('content.invoices.pdf_unified_in', $viewData)
            ->setPaper('A4', 'portrait')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', true)
            ->set_option('chroot', public_path());

        // Render once so we can both save and stream
        $pdf->render();

        // --- LAST-PAGE FOOTER ONLY ---
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->get_canvas();

        $w = $canvas->get_width();
        $h = $canvas->get_height();
        $r = 0.89;
        $g = 0.32;
        $b = 0.02;
        $footerText = 'Thank you for doing business with us. We look forward to serving you again.';
        $barHeight = 40;
        $fontSize = 12;

        $canvas->page_script('
    // draw only on the FIRST page
    if ($PAGE_NUM == 1) {
        $font = $fontMetrics->get_font("helvetica", "bold");
        $text = "' . addslashes($footerText) . '";
        $fs   = ' . $fontSize . ';
        $barH = ' . $barHeight . ';
        $W    = ' . $w . ';
        $H    = ' . $h . ';

        // bar across the bottom
        $pdf->filled_rectangle(0, $H - $barH, $W, $barH, array(' . $r . ',' . $g . ',' . $b . '));

        // center text in the bar
        $textWidth = $fontMetrics->getTextWidth($text, $font, $fs);
        $x = ($W - $textWidth) / 2;

        $ascent  = method_exists($fontMetrics,"getFontAscent") ? $fontMetrics->getFontAscent($font, $fs) : $fs * 0.8;
        $descent = method_exists($fontMetrics,"getFontDescent") ? abs($fontMetrics->getFontDescent($font, $fs)) : $fs * 0.2;

        $lineHeight = 3;
        $block      = ($ascent + $descent) * $lineHeight;
        $y = ($H - $barH) + (($barH - $block) / 2) + $ascent;

        $pdf->text($x, $y, $text, $font, $fs, array(1,1,1));
    }
');

        // Save a copy (optional)
        try {
            $bytes = $pdf->output();
            $base    = $invoice->invoice_no ?: ('Invoice_' . $invoice->id);
            $safe    = preg_replace('/[^A-Za-z0-9_\-\.]/', '-', $base) . '.pdf';
            $relPath = 'invoices/' . $safe;

            Storage::disk('public')->put($relPath, $bytes);

            if (Schema::hasColumn('invoices', 'pdf_path')) {
                $invoice->pdf_path = $relPath;
            }
            if (Schema::hasColumn('invoices', 'pdf_generated_at')) {
                $invoice->pdf_generated_at = now();
            }
            if (Schema::hasColumn('invoices', 'pdf_path') || Schema::hasColumn('invoices', 'pdf_generated_at')) {
                $invoice->save();
            }
        } catch (\Throwable $e) {
            \Log::warning('Invoice PDF save failed', [
                'invoice_id' => $invoice->id,
                'error'      => $e->getMessage(),
            ]);
        }

        return $pdf->stream('invoice.pdf');
    }


    public function emailToClient(Request $request, Invoice $invoice)
    {
        // Only allow when approved
        if ($invoice->status !== 'finance_approved') {
            return back()->with('error', 'Invoice must be finance approved before emailing.');
        }

        // Basic validation
        $validated = $request->validate([
            'to'      => ['required', 'string', 'max:2000'],
            'cc'      => ['nullable', 'string', 'max:2000'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
        ]);

        // Parse comma-separated emails
        $toList = collect(explode(',', $validated['to']))
            ->map(fn($e) => trim($e))
            ->filter()
            ->values();

        $ccList = collect(explode(',', $validated['cc'] ?? ''))
            ->map(fn($e) => trim($e))
            ->filter()
            ->values();

        // Validate each email roughly
        foreach ($toList as $em) {
            if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', "Invalid To email: {$em}")->withInput();
            }
        }
        foreach ($ccList as $em) {
            if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                return back()->with('error', "Invalid CC email: {$em}")->withInput();
            }
        }

        if (empty($invoice->pdf_path) || !Storage::disk('public')->exists($invoice->pdf_path)) {
            // You already wrote this helper earlier; re-use it
            if (method_exists($this, 'generateAndStoreInvoicePdf')) {
                $this->generateAndStoreInvoicePdf($invoice->id);
                $invoice->refresh();
            }
        }

        if (empty($invoice->pdf_path) || !Storage::disk('public')->exists($invoice->pdf_path)) {
            return back()->with('error', 'PDF not available to attach. Please generate the PDF first.');
        }

        $absPath  = Storage::disk('public')->path($invoice->pdf_path);
        $filename = basename($absPath);

        try {
            Mail::send('content.emails.invoice_default', [
                'invoice' => $invoice,
                'body'    => $validated['message'],
            ], function ($m) use ($toList, $ccList, $validated, $absPath, $filename) {
                $m->subject($validated['subject']);
                $m->to($toList->all());
                if ($ccList->isNotEmpty()) $m->cc($ccList->all());
                $m->attach($absPath, ['as' => $filename, 'mime' => 'application/pdf']);
            });
        } catch (\Throwable $e) {
            \Log::error('Invoice email sending failed', [
                'invoice_id' => $invoice->id,
                'error'      => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to send email. Please try again.');
        }
        // Update status to 'sent' and optionally mark paid
        DB::table('invoices')->where('id', $invoice->id)->update([
            'status'            => 'sent',
            'updated_by'        => $request->user()?->id,
            'updated_at'        => now(),
        ]);

        return back()->with('success', 'Email sent successfully. Invoice marked as sent.');
    }

    // app/Http/Controllers/InvoiceController.php

    public function markPaid(Request $request, Invoice $invoice)
    {
        // Allow only if already sent (or relax this to include finance_approved)
        if ($invoice->status !== 'sent') {
            return response()->json([
                'status'  => 0,
                'message' => 'Invoice must be in "sent" status to mark payment collected.',
            ], 422);
        }

        $data = $request->validate([
            'payment_completed' => ['required', 'in:1'], // only allow turning ON
        ]);

        if ($invoice->payment_completed) {
            return response()->json([
                'status'  => 1,
                'message' => 'Payment already marked as collected.',
            ]);
        }

        $invoice->update([
            'payment_completed' => 1,
            'updated_by'        => $request->user()?->id,
        ]);

        return response()->json([
            'status'  => 1,
            'message' => 'Payment marked as collected.',
        ]);
    }

    public function completePayment(Request $request, $encryptedId)
    {
        try {
            $invoiceId = Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Invalid invoice ID.',
            ], 422);
        }
        
        $invoice = Invoice::findOrFail($invoiceId);

        // Allow only if status is 'finance_approved'
        if ($invoice->status !== 'finance_approved') {
            return response()->json([
                'status'  => 0,
                'message' => 'Invoice must be in "finance_approved" status to mark payment completed.',
            ], 422);
        }

        if ($invoice->payment_completed) {
            return response()->json([
                'status'  => 0,
                'message' => 'Payment already marked as completed.',
            ], 422);
        }

        $data = $request->validate([
            'payment_completed_date' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $invoice->update([
            'payment_completed' => 1,
            'payment_completed_date' => $data['payment_completed_date'],
            'status' => 'payment_completed',
            'updated_by' => $request->user()?->id,
        ]);

        return response()->json([
            'status'  => 1,
            'message' => 'Payment marked as completed successfully.',
        ]);
    }

    public function generalInvoice(InvoiceBrowseDataTable $dataTable)
    {
        // Permission gate for seeing the page at all
        // $this->authorize('list invoice');

        // For filter dropdowns
        $projects  = Project::orderBy('project_name')->get(['id', 'project_name']);
        $customers = Company::orderBy('name')->get(['id', 'name']);

        return $dataTable->render('content.invoices.general', [
            'title'     => 'Invoices',
            'projects'  => $projects,
            'customers' => $customers,
        ]);
    }

    public function previewPdf(string $encId)
    {
        $id = Crypt::decryptString($encId);

        $invoice = Invoice::with([
            'project:id,project_name,project_category',
            'lines' => fn($q) => $q->orderBy('sno'),
            'bank'
        ])->findOrFail($id);

        // Get project category
        $projectCategory = (int) ($invoice->project->project_category ?? 0);

        // For category 3, create billing details rows from invoice lines (not from project_intakes)
        if ($projectCategory === 3) {
            $rows = collect();
            $sno = 1;
            foreach ($invoice->lines as $line) {
                $rows->push((object)[
                    'sno'            => $sno++,
                    'project_name'  => $line->description ?? 'Data Collection of Indian Companies',
                    'billable_units' => (float) ($line->qty ?? 0),
                    'unit_rate'     => (float) ($line->rate ?? 0),
                    'billable_value' => (float) ($line->value ?? 0),
                ]);
            }
        } else {
            // For other categories, use the existing detailed rows from project_intakes
            $rows = DB::table('invoices as inv')
                ->join('invoice_lines as il', 'il.invoice_id', '=', 'inv.id')
                ->leftJoin('project_intakes as pi', 'pi.id', '=', 'il.source_intake_id')
                ->leftJoin('service_offerings as so', 'so.id', '=', 'pi.type_of_work_id')
                ->leftJoin('projects as pr', 'pr.id', '=', 'inv.project_id')
                ->leftJoin('companies as co', 'co.id', '=', 'inv.customer_id')
                // comment this join if you don't have the table
                ->leftJoin('intake_statuses as sm', 'sm.id', '=', 'pi.status_master_id')
                ->where('inv.id', $id) // <- set $invoiceId
                ->orderBy('il.sno')
                ->orderBy('il.id')
                ->get([
                    DB::raw('inv.billing_month AS month_year'),
                    DB::raw('co.name AS client_name'),
                    DB::raw('pr.project_name AS project_name'),
                    DB::raw('pi.property_name AS property_name'),
                    DB::raw('pi.tenant_name AS tenant_name'),
                    DB::raw('pi.premises_address AS address'),
                    DB::raw("'' AS city"),    // not stored
                    DB::raw("'' AS state"),   // not stored
                    DB::raw("'' AS country"), // not stored
                    // If status_masters exists:
                    DB::raw("COALESCE(sm.name, CONCAT('ID:', pi.status_master_id)) AS intake_status"),
                    // Else: DB::raw('pi.status_master_id AS intake_status'),
                    DB::raw('pi.delivered_date AS delivery_date'),
                    DB::raw('so.name AS type_of_work'),
                    DB::raw('pi.language_code AS language'),
                    DB::raw('il.rate AS cost'),
                ]);
        }

        // ==== GROUP LINES FOR DISPLAY (Description + Rate) ====
        $groupedLines = $invoice->lines
            ->groupBy(function ($r) {
                $desc = (string) ($r->description ?? '');
                $rate = number_format((float) ($r->rate ?? 0), 6, '.', '');
                return $desc . '||' . $rate;
            })
            ->map(function ($items) {
                $first    = $items->first();
                $qtySum   = (float) $items->sum(fn($x) => (float) ($x->qty ?? 0));
                $valueSum = (float) $items->sum(fn($x) => (float) ($x->value ?? 0));
                $minSno   = (int) $items->min('sno');

                return (object) [
                    'sno'         => $minSno,
                    'description' => (string) ($first->description ?? ''),
                    'sac'         => $first->sac ?? null,
                    'qty'         => $qtySum,
                    'rate'        => (float) ($first->rate ?? 0),
                    'value'       => $valueSum,
                ];
            })
            ->sortBy('sno')
            ->values();

        // ----- Company info (freeze from invoice, else config) -----
        $cfg   = config('companyinfo');
        $brand = Arr::get($cfg, 'brand', []);
        $bank  = $invoice->bank ?? '';

        $company = [
            'name'        => $invoice->company_name     ?: ($cfg['name'] ?? ''),
            'address'     => $invoice->company_address  ?: ($cfg['address'] ?? ''),
            'pan'         => $invoice->company_pan      ?: ($cfg['pan'] ?? null),
            'gst'         => $invoice->company_gstin    ?: ($cfg['gst_no'] ?? null),
            'lut'         => $invoice->company_lut_no   ?: ($cfg['lut_no'] ?? null),
            'iec'         => $invoice->company_iec      ?: ($cfg['iec_code'] ?? null),
            'reference'   => $invoice->company_reference_no ?: ($cfg['reference_no'] ?? null),
            'signatory'   => $invoice->company_signatory    ?: ($cfg['signatory'] ?? 'Authorized Signatory'),
            'logo_url'    => $cfg['logo_url'] ?? null,
            'brand'       => [
                'primary' => $brand['primary'] ?? '#e35205',
                'accent'  => $brand['accent']  ?? '#f1f5ff',
                'text'    => $brand['text']    ?? '#333333',
                'muted'   => $brand['muted']   ?? '#6c757d',
                'border'  => $brand['border']  ?? '#e5e7eb',
            ],
        ];

        // Currency (fallbacks)
        $currencySymbol = $invoice->currency_symbol ?: '$';
        $currencyName   = $invoice->currency_name   ?: 'USD';

        // Totals — prefer header; else sum of GROUPED values
        $subtotal = $invoice->subtotal > 0
            ? (float) $invoice->subtotal
            : (float) $groupedLines->sum('value');

        $discount = (float) ($invoice->discount_total ?: $invoice->discount ?: 0);

        $cfgGstPct = (float) (config('companyinfo.gst_percent') ?? 18);
        $taxBase   = max(0, $subtotal - $discount);

        $gstAmount = $invoice->tax_total > 0
            ? (float) $invoice->tax_total
            : round($taxBase * ($cfgGstPct / 100), 2);

        $grandTotal = $invoice->net_total > 0
            ? (float) $invoice->net_total
            : round($taxBase + $gstAmount, 2);

        $viewData = [
            'invoice'        => $invoice,
            // ⬇️ Use GROUPED lines in the PDF
            'lines'          => $groupedLines,
            'company'        => $company,
            'currencySymbol' => $currencySymbol,
            'currencyName'   => $currencyName,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'bank'           => $bank,
            'gstPercent'     => $invoice->tax_total > 0 && $taxBase > 0
                ? round(($gstAmount / $taxBase) * 100, 2)
                : $cfgGstPct,
            'gstAmount'      => $gstAmount,
            'grandTotal'     => $grandTotal,
            'rows'           => $rows,
            'projectCategory' => $projectCategory
        ];

        // Build PDF (inline preview)
        $pdf = Pdf::loadView('content.invoices.pdf_unified_in', $viewData)
            ->setPaper('A4', 'portrait')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', true)
            ->set_option('chroot', public_path());

        // Optional footer (last page only)
        $pdf->render();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->get_canvas();

        $w = $canvas->get_width();
        $h = $canvas->get_height();
        $r = 0.89;
        $g = 0.32;
        $b = 0.02;
        $footerText = 'Thank you for doing business with us. We look forward to serving you again.';
        $barHeight  = 40;
        $fontSize = 12;

        $canvas->page_script('
    // draw only on the FIRST page
    if ($PAGE_NUM == 1) {
        $font = $fontMetrics->get_font("helvetica", "bold");
        $text = "' . addslashes($footerText) . '";
        $fs   = ' . $fontSize . ';
        $barH = ' . $barHeight . ';
        $W    = ' . $w . ';
        $H    = ' . $h . ';

        // bar across the bottom
        $pdf->filled_rectangle(0, $H - $barH, $W, $barH, array(' . $r . ',' . $g . ',' . $b . '));

        // center text in the bar
        $textWidth = $fontMetrics->getTextWidth($text, $font, $fs);
        $x = ($W - $textWidth) / 2;

        $ascent  = method_exists($fontMetrics,"getFontAscent") ? $fontMetrics->getFontAscent($font, $fs) : $fs * 0.8;
        $descent = method_exists($fontMetrics,"getFontDescent") ? abs($fontMetrics->getFontDescent($font, $fs)) : $fs * 0.2;

        $lineHeight = 3;
        $block      = ($ascent + $descent) * $lineHeight;
        $y = ($H - $barH) + (($barH - $block) / 2) + $ascent;

        $pdf->text($x, $y, $text, $font, $fs, array(1,1,1));
    }
');


        $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '-', ($invoice->invoice_no ?: "Invoice_{$invoice->id}")) . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $safeName . '"',
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }
}
