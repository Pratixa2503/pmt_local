<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\DataTables\CompanyDataTable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use App\Models\Project;
use Yajra\DataTables\Facades\DataTables;
 
class CompanyController extends Controller
{

    public function index(CompanyDataTable $dataTable)
    {
        if (!auth()->user()->can('view customer')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.companies.index');
    }
    /**
     * Show create form.
     */
    public function create()
    {
        $title = 'Create Customer';
        $type  = 'create';

        // form expects $company (nullable) and $team (array of rows)
        $company = null;
        $team = [
            ['first_name' => '', 'last_name' => '', 'contact_no' => '', 'email' => '', 'status' => 'active']
        ];

        return view('content.companies.form', compact('title', 'type', 'company', 'team'));
    }

    /**
     * Persist company + team.
     */
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        DB::transaction(function () use ($data) {
            // 1) Company
            $company = Company::create([
                'name'       => $data['name'],
                'address'    => $data['address']     ?? null,
                'location'   => $data['location']    ?? null,
                'contact_no' => $data['contact_no']  ?? null,
                'website'    => $data['website']     ?? null,
                'company_type' => $data['company_type'] ?? 1,
                'zip_code'    => $data['zip_code'] ?? null
            ]);

            // 2) Team (users)
            $this->upsertTeam($company, $data['team'] ?? []);
        });

        return redirect()->route('companies.index')->with('success', 'Customer and team saved successfully.');
    }

    protected function resolveCompanyFromEncrypted(string $encryptedId): Company
    {
        try {
            $id = Crypt::decryptString($encryptedId);
        } catch (DecryptException $e) {
            abort(404);
        }

        return Company::with('users')->findOrFail($id);
    }
    /**
     * Show edit form.
     */
    public function edit($encryptedId)
    {

        $title = 'Edit Customer';
        $type  = 'edit';
        $company =  $this->resolveCompanyFromEncrypted($encryptedId);
        // Pre-fill team rows from existing users for this company
        $team = $company->users()
            ->orderBy('id', 'asc')
            ->get(['first_name', 'last_name', 'email', 'contact_no', 'status','is_billing_contact','is_project_contact'])
            ->map(function ($u) {
                return [
                    'first_name' => $u->first_name ?? '',
                    'last_name'  => $u->last_name  ?? '',
                    'contact_no' => $u->contact_no ?? '', // if your column is 'phone', we remapped above
                    'email'      => $u->email      ?? '',
                    'status'     => $u->status     ?? 'active',
                    'is_billing_contact' => $u->is_billing_contact ?? '',
                    'is_project_contact' => $u->is_project_contact ?? '',
                ];
            })->toArray();

        if (empty($team)) {
            $team = [
                ['first_name' => '', 'last_name' => '', 'contact_no' => '', 'email' => '', 'status' => 'active','is_billing_contact'=>'','is_project_contact'=>'']
            ];
        }
        //dd($team);
        return view('content.companies.form', compact('title', 'type', 'company', 'team'));
    }

    /**
     * Update company + (append/update) team.
     */
    public function update(Request $request, string $encryptedId)
    {
        $company = $this->resolveCompanyFromEncrypted($encryptedId);
        $request->merge(['company_id' => $company->id]);
        $data = $this->validateRequest($request, isUpdate: true);
        
        DB::transaction(function () use ($company, $data) {
            // 1) Company
            $company->update([
                'name'       => $data['name'],
                'address'    => $data['address']     ?? null,
                'location'   => $data['location']    ?? null,
                'contact_no' => $data['contact_no']  ?? null,
                'website'    => $data['website']     ?? null,
                'company_type' => $data['company_type'] ?? 1,
                'zip_code'    => $data['zip_code'] ?? null
            ]);

            // 2) Team (users): upsert new/changed rows; do not delete missing ones by default
            $this->upsertTeam($company, $data['team'] ?? []);

            /**
             * If you prefer to "replace" team on update (i.e., delete users not present in the payload),
             * uncomment the block below to sync by email:
             *
             * $incomingEmails = collect($data['team'] ?? [])->pluck('email')->filter()->values()->all();
             * $company->users()->whereNotIn('email', $incomingEmails)->delete();
             */
        });

        return redirect()->route('companies.index')->with('success', 'Customer and team updated successfully.');
    }

    /**
     * Common validation rules for store/update.
     */
    protected function validateRequest(Request $request, bool $isUpdate = false): array
    {
        // Use the company_id we just merged (update) or any provided (create)
        $companyId = $request->input('company_id');

        $rules = [
            'name'       => ['required', 'string', 'max:255'],
            'address'    => ['nullable', 'string', 'max:255'],
            'location'   => ['nullable', 'string', 'max:255'],
            'contact_no' => ['nullable', 'string', 'max:10'],
            'website' => [
                'nullable',
                'max:255',
                function ($attribute, $value, $fail) {
                    $v = trim((string) $value);
                    if ($v === '') return;

                    $hasScheme = (bool) preg_match('#^[a-z][a-z0-9+\-.]*://#i', $v);

                    // If user omitted scheme, require at least a dot (e.g. example.com) then validate with http://
                    if (! $hasScheme && strpos($v, '.') === false) {
                        return $fail("The {$attribute} must be a valid URL (e.g., example.com or https://example.com).");
                    }

                    $candidate = $hasScheme ? $v : "http://{$v}";
                    if (filter_var($candidate, FILTER_VALIDATE_URL) === false) {
                        return $fail("The {$attribute} must be a valid URL (e.g., example.com or https://example.com).");
                    }
                },
            ],
            'zip_code'   => ['nullable', 'string', 'max:20'],
            'company_type' => ['nullable', 'integer', 'in:1,2'],
            'team'              => ['nullable', 'array'],
            'team.*.id'         => ['nullable', 'integer', 'exists:users,id'],
            'team.*.first_name' => ['required_with:team', 'string', 'max:100'],
            'team.*.last_name'  => ['required_with:team', 'string', 'max:100'],
            'team.*.contact_no' => ['nullable', 'string', 'max:10'],
            'team.*.status'     => ['required_with:team', Rule::in(['1', '0'])],
            'team.*.is_billing' => ['nullable', Rule::in(['1','0','on','off','true','false'])],
            'team.*.is_project' => ['nullable', Rule::in(['1','0','on','off','true','false'])],
            // prevent duplicates inside the *payload* itself
            'team.*.email' => [
                'required_with:team',
                'string',
                'max:320',
                // Reject domains without a real TLD, e.g. "ex"
                function ($attribute, $value, $fail) {
                    $email = trim((string) $value);
                    if ($email === '') return;

                    // IDN-safe: convert domain to ASCII for validation if possible
                    if (strpos($email, '@') !== false && function_exists('idn_to_ascii')) {
                        [$local, $domain] = explode('@', $email, 2);
                        $ascii = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);
                        if ($ascii !== false) {
                            $email = $local . '@' . $ascii;
                        }
                    }

                    // Basic RFC format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        return $fail('Please enter a valid email address.');
                    }

                    // Enforce dotted domain and a real-looking TLD (2–63 letters)
                    $domain = substr(strrchr($email, '@'), 1);
                    if (!preg_match('/^[A-Za-z0-9.-]+\.[A-Za-z]{2,63}$/', $domain)) {
                        return $fail('Email domain must include a valid TLD (e.g. ".com").');
                    }
                },
                'distinct',
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        // Per-row: the email must be unique *across other companies*, but allowed within the same company.
        $team = $request->input('team', []);
        foreach ($team as $i => $row) {
            $memberId = data_get($row, 'id');

            $emailRule = Rule::unique('users', 'email')
                ->where(fn($q) => $q->where('company_id', '!=', $companyId));

            if ($isUpdate && $memberId) {
                $emailRule->ignore($memberId);
            }

            // Always apply this rule to the specific row’s email
            $validator->sometimes("team.$i.email", [$emailRule], fn() => true);
        }

        return $validator->validate();
    }

    /**
     * Create or update users for the given company.
     * - Uses email as a stable identifier.
     * - Assigns company_id.
     * - Sets a random password if creating a new user (and password column exists).
     */
   protected function upsertTeam(Company $company, array $team): void
    {
        // 1) Normalize incoming rows and collect identifiers we’ll keep
        $normalized = [];
        $keepIds = [];
        $keepEmails = [];

        // helper to coerce checkbox-ish values to boolean (1/0)
        $toBool = static function ($v): int {
            if ($v === null) return 0;
            if (is_string($v)) {
                $v = strtolower(trim($v));
                return in_array($v, ['1','true','on','yes'], true) ? 1 : 0;
            }
            return in_array($v, [1, true], true) ? 1 : 0;
        };

        foreach ($team as $row) {
            $first  = trim($row['first_name'] ?? '');
            $last   = trim($row['last_name'] ?? '');
            $email  = strtolower(trim($row['email'] ?? ''));
            if ($email === '' || $first === '' || $last === '') {
                continue; // skip invalid rows
            }

            $normalized[] = [
                'id'                   => $row['id'] ?? null,
                'first_name'           => $first,
                'last_name'            => $last,
                'email'                => $email,
                'contact_no'           => $row['contact_no'] ?? null,
                'status'               => $row['status'] ?? 1,

                // NEW: map form fields -> user columns; accept either form names or direct column names
                'is_billing_contact'   => $toBool($row['is_billing'] ?? $row['is_billing'] ?? 0),
                'is_project_contact'   => $toBool($row['is_project'] ?? $row['is_project'] ?? 0),
            ];

            if (!empty($row['id'])) {
                $keepIds[] = (int) $row['id'];
            }
            $keepEmails[] = $email;
        }
        //dd($normalized);
        // 2) Upsert each incoming member (prefer ID, fallback to email)
        foreach ($normalized as $row) {
            /** @var \App\Models\User $user */
            if (!empty($row['id'])) {
                // Try update by ID within the same company
                $user = $company->users()->whereKey($row['id'])->first();
                // If not found (id not in this company), fallback to email
                if (!$user) {
                    $user = \App\Models\User::firstOrNew(['email' => $row['email']]);
                }
            } else {
                $user = \App\Models\User::firstOrNew(['email' => $row['email']]);
            }

            if (!$user->exists) {
                $password = 'Brisk@123';
                $user->password = \Illuminate\Support\Facades\Hash::make($password);
            }

            $user->first_name = $row['first_name'];
            $user->last_name  = $row['last_name'];
            if (array_key_exists('contact_no', $row)) {
                $user->contact_no = $row['contact_no'] ?: null;
            }
            $user->status     = $row['status'];
            $user->email      = $row['email']; // allow email updates
            $user->company_id = $company->id;

            // NEW: persist the two flags
            $user->is_billing_contact = (int) $row['is_billing_contact'];
            $user->is_project_contact = (int) $row['is_project_contact'];

            $user->save();

            // Ensure role assignment (idempotent)
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('customer');
            }
        }

        // 3) Remove users not present in the payload (SYNC behavior)
        //    We match by ID when available, otherwise by email, and scope to this company.
        $query = $company->users();

        // Optional: restrict to a role so you don't delete admins/employees accidentally.
        // $query->role('customer');

        $query->where(function ($q) use ($keepIds, $keepEmails) {
            if (!empty($keepIds)) {
                $q->whereNotIn('id', $keepIds);
            }
            if (!empty($keepEmails)) {
                $q->whereNotIn(\DB::raw('LOWER(email)'), $keepEmails);
            }
        })
        ->when(auth()->check(), function ($q) {
            $q->where('id', '!=', auth()->id());
        })
        ->get()
        ->each(function ($user) {
            $user->delete();               // or $user->update(['status' => 0]);
        });
    }



    public function destroy(Request $request, string $encryptedId)
    {
        $company = $this->resolveCompanyFromEncrypted($encryptedId);

        // (optional) authorization
        // $this->authorize('delete', $company);

        DB::transaction(function () use ($company) {
            $query = $company->users()
                ->when(auth()->check(), fn($q) => $q->where('id', '!=', auth()->id()));

            $query->get()->each(function ($user) {
                $user->delete(); // soft or hard depending on User model
            });

            $company->poNumbers()->delete();
            $company->projects()->delete();



            // Finally delete the company
            $company->delete(); // soft or hard depending on Company model
        });
        return response()->json(['status' => 1, 'message' => 'Customer deleted successfully.']);
    }

 

public function projects(string $encrypted)
{
    if (! auth()->user()->can('view customer')) abort(403);

    try {
        $customerId = Crypt::decryptString($encrypted);
    } catch (DecryptException $e) {
        abort(404);
    }

    $user = auth()->user();
    
    $query = Project::with(['category','status'])
        ->where('projects.customer_id', $customerId)
        ->select([
            'projects.id',
            'projects.parent_id',
            'projects.project_name',
            'projects.project_category',
            'projects.project_status_id',
            'projects.start_date',
            'projects.end_date',
            'projects.description',
        ])
        // root_order groups a parent with its children, sort_bucket ensures parent shows before children
        ->selectRaw('COALESCE(projects.parent_id, projects.id) AS root_order')
        ->selectRaw('CASE WHEN projects.parent_id IS NULL THEN 0 ELSE 1 END AS sort_bucket');

    // Apply role-based filtering (same as ProjectDataTable)
    if ($user) {
        $isAdmin = $user->hasRole('super admin');
        
        if (!$isAdmin) {
            // Join project_intakes for category 2 role filtering
            $query->leftJoin('project_intakes as pi', 'pi.parent_id', '=', 'projects.id');
            
            // Map roles that are determined via project_intakes (cat=2)
            $roleColumns = [
                'abstractor'  => 'pi.abstractor_id',
                'reviewer'    => 'pi.reviewer_id',
                'sense check' => 'pi.sense_check_ddr_id',
                'customer'    => 'pi.property_manager_id',
            ];

            $columnsToFilter = [];
            foreach ($roleColumns as $role => $col) {
                if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                    $columnsToFilter[] = $col;
                }
            }

            $query->where(function ($root) use ($user, $columnsToFilter) {
                // ---------- Category 2 (LA) ----------
                $root->orWhere(function ($cat2) use ($user, $columnsToFilter) {
                    $cat2->where('projects.project_category', 2)
                        ->where(function ($q) use ($user, $columnsToFilter) {
                            // OR across intake role columns
                            foreach ($columnsToFilter as $col) {
                                $q->orWhere($col, $user->id);
                            }

                            // Project Manager access (via pivot project_user)
                            if (method_exists($user, 'hasRole') && $user->hasRole('project manager')) {
                                $q->orWhereExists(function ($sub) use ($user) {
                                    $sub->selectRaw(1)
                                        ->from('project_user as pu')
                                        ->whereColumn('pu.project_id', 'projects.id')
                                        ->where('pu.user_id', $user->id);
                                });
                            }
                        });
                });

                // ---------- Category 1 and 3 (General) ----------
                $root->orWhere(function ($cat1) use ($user) {
                    $cat1->whereIn('projects.project_category', [1, 3])
                        ->where(function ($q) use ($user) {
                            // PMs see their projects
                            if (method_exists($user, 'hasRole') && $user->hasRole('project manager')) {
                                $q->orWhereExists(function ($sub) use ($user) {
                                    $sub->selectRaw(1)
                                        ->from('project_user as pu')
                                        ->whereColumn('pu.project_id', 'projects.id')
                                        ->where('pu.user_id', $user->id);
                                });
                            }

                            // Members assigned (project_member_assignments.member_id = user)
                            $q->orWhereExists(function ($sub) use ($user) {
                                $sub->selectRaw(1)
                                    ->from('project_member_assignments as pma')
                                    ->whereColumn('pma.project_id', 'projects.id')
                                    ->where('pma.member_id', $user->id);
                            });
                        });
                });
            });
            
            // Add distinct to avoid duplicates from the join
            $query->distinct();
        }
    }

    return DataTables::of($query)
        // force our hierarchical order regardless of client sorting
        ->order(function ($q) {
            $q->orderBy('root_order', 'asc')
              ->orderBy('sort_bucket', 'asc')
              ->orderBy('id', 'asc');   // or 'project_name' if you prefer
        })
        ->addColumn('id_encrypted', fn($p) => Crypt::encryptString($p->id))
        ->editColumn('project_category', fn($p) => optional($p->category)->name ?: '-')
        ->editColumn('start_date', fn($p) => $p->start_date ? $p->start_date->format('Y-m-d') : null)
        ->editColumn('end_date', fn($p) => $p->end_date ? $p->end_date->format('Y-m-d') : null)
        ->addColumn('status', fn($p) => optional($p->status)->name ?: '-')
        ->addColumn('actions', function ($p) {
            $encrypted = Crypt::encryptString($p->id);
            $user      = auth()->user();
            $actions   = [];

            if ($user && $user->can('view intake form')) {
                $isCat2 = (int) ($p->project_category ?? 0) === 2;
                $url = $title = $icon = null;

                if ($user->hasRole('super admin')) {
                    $url   = route('projects.admin.tracking', $encrypted);
                    $title = 'Admin Tracking';
                    $icon  = 'fa-solid fa-user-shield';
                } elseif (! $user->hasRole('customer')) {
                    $url   = route('projects.tasks.track', $encrypted);
                    $title = 'Project Tracker';
                    $icon  = 'fa-solid fa-list-check';
                }

                if ($url) {
                    $actions[] = '<a href="'.$url.'" class="me-2" title="'.e($title).'"><i class="'.$icon.'"></i></a>';
                }
                if ($isCat2) {
                    $url = route('projects.fileView', ['parent' => $encrypted]);
                    $actions[] = '<a href="'.$url.'" class="me-2" title="View Files"><i class="fa-solid fa-file-excel"></i></a>';
                }
            }

            if ($user && $user->can('create project') && empty($p->parent_id)) {
                $url = route('projects.create', ['parent' => $encrypted]);
                $actions[] = '<a href="'.$url.'" class="me-2" title="Add Subproject"><i class="fa-solid fa-diagram-project"></i></a>';
            }

            if ($user && $user->can('edit project')) {
                $url = route('projects.edit', $encrypted);
                $actions[] = '<a href="'.$url.'" class="me-2" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
            }

            if ($user && $user->can('delete project')) {
                $actions[] = '<a href="javascript:void(0)" data-id="'.$encrypted.'" class="delete-project" title="Delete"><i class="fa-solid fa-trash"></i></a>';
            }

            return $actions ? implode('', $actions) : '-';
        })
        ->rawColumns(['actions'])
        ->make(true);
}

}
