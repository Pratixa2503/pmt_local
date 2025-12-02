<?php

namespace App\Http\Controllers;

use App\Models\PricingMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\PricingMasterDataTable;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use App\Models\PricingMasterSkillLine;
use App\Models\Note;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PricingMasterApprovalRequest;
use App\Mail\PricingMasterApprovedNotification;
use App\Mail\PricingMasterRejectedNotification;
use App\Mail\PricingMasterModificationRequest;
class PricingMasterController extends Controller
{

    public function index(PricingMasterDataTable $dataTable)
    {
        if (!auth()->user()->can('view pricing master')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.pricing-master.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create pricing master')) {
            abort(403, 'Unauthorized action.');
        }
        $title = 'Create Pricing Master';
        $type = 'create';
        return view('content.pricing-master.form', compact('title', 'type'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create pricing master')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $this->validateForm($request);

        DB::transaction(function () use ($request, $validated, &$pricing) {
            $pricing = new PricingMaster($validated);

            if ($request->hasFile('document')) {
                $pricing->document_path = $request->file('document')->store('pricing_masters', 'public');
            }

            $pricing->created_by      = Auth::id();
            $pricing->updated_by      = Auth::id();
            $pricing->approval_status = 'pending';
            $pricing->submitted_at    = now();
            $pricing->save();

            // Sync skills only for Custom + Variable
            if (
                ($validated['pricing_type'] ?? null) === 'custom' &&
                ($validated['custom_pricing_type'] ?? null) === 'variable'
            ) {
                $this->syncSkillLines(
                    $pricing,
                    $request->input('skills', []),
                    $request->input('average_handling_time', [])
                );
            }
        });

        Note::create([
            'pricing_master_id' => $pricing->id,
            'note_type'         => 3,
            'price'             => $pricing->rate,
            'create_by'         => Auth::id()
        ]);
        
       
         // Build encrypted token and computed fields
        $token = Crypt::encryptString((string) $pricing->id);

        $row = $this->findPricingWithComputed($pricing->id); // returns stdClass

        $serviceOfferingNames = [];
        if (!empty($row->service_offerings)) {
            $serviceOfferingNames = array_values(
                array_filter(array_map('trim', explode(',', $row->service_offerings)))
            );
        }

        $currencyLabel = $row->currency_label ?? 'N/A';

        // Send to super admin from ENV
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
        if (!empty($adminEmail)) {
            Mail::to($adminEmail)->queue(
                new PricingMasterApprovalRequest($pricing, $token, $serviceOfferingNames, $currencyLabel)
            );
        }
        return redirect()->route('pricing-master.index')->with('success', 'Pricing Master created successfully.');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit pricing master')) {
            abort(403, 'Unauthorized action.');
        }

        $data = PricingMaster::with('skillLines')->findOrFail(Crypt::decryptString($id));

        // If approved, only super admin can edit OR if modification was requested (modification_notes exists)
        if ($data->approval_status === 'approved') {
            $isSuperAdmin = auth()->user()->hasRole('super admin');
            $hasModificationRequest = !empty($data->modification_notes);
            
            if (!$isSuperAdmin && !$hasModificationRequest) {
                abort(403, 'Cannot edit approved pricing master. Only super admin can request modifications.');
            }
        }

        $title = 'Edit Pricing Master';
        $type = 'edit';

        return view('content.pricing-master.form', compact('data', 'title', 'type'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit pricing master')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
        $pricing   = PricingMaster::findOrFail($id);
        $validated = $this->validateForm($request, $id);

        DB::transaction(function () use ($pricing, $validated, $request) {
            // Fill new values from validated data (do NOT freeze rate)
            $pricing->fill($validated);
            if ($request->boolean('remove_document') && $pricing->document_path) {
                try {
                    Storage::delete($pricing->document_path);
                } catch (\Throwable $e) {
                    // log if you want
                }
                $pricing->document_path = null;
            }
            if ($request->hasFile('document')) {
                if ($pricing->document_path && Storage::disk('public')->exists($pricing->document_path)) {
                    Storage::disk('public')->delete($pricing->document_path);
                }
                $pricing->document_path = $request->file('document')->store('pricing_masters', 'public');
            }

            $pricing->updated_by = Auth::id();

            // If status was approved and user edits (after modification request) -> reset to pending
            // If rejected, keep it rejected so "Send for Approval" button shows
            if ($pricing->approval_status === 'approved') {
                // Clear modification fields when user updates after modification request
                $pricing->modification_notes = null;
                $pricing->modification_parameter = null;
                // Reset to pending for re-approval
                $pricing->approval_status = 'pending';
                $pricing->submitted_at    = now();
                $pricing->approved_by     = null;
                $pricing->approved_at     = null;
                $pricing->approval_note   = null;
            } elseif ($pricing->approval_status === 'rejected' || $pricing->approval_status === 'pending') {
                // When form is updated after rejection or pending, clear submitted_at to show button again
                $pricing->submitted_at = null;
            }
            // If rejected, keep rejected status - user will use "Send for Approval" button

            $pricing->save();

            // Reset and (re)sync skills only when Custom + Variable
            $pricing->skillLines()->delete();
            if (
                ($validated['pricing_type'] ?? null) === 'custom' &&
                ($validated['custom_pricing_type'] ?? null) === 'variable'
            ) {
                $this->syncSkillLines(
                    $pricing,
                    $request->input('skills', []),
                    $request->input('average_handling_time', [])
                );
            }
        });

        // Update / upsert Note (type 3) with the new rate
        $note = Note::where('pricing_master_id', $pricing->id)
            ->where('note_type', 3)
            ->first();

        if ($note) {
            $note->update(['price' => $request->rate]);
        } else {
            Note::create([
                'pricing_master_id' => $pricing->id,
                'note_type'         => 3,
                'price'             => $request->rate,
                'create_by'         => Auth::id()
            ]);
        }

        // If was rejected or pending, redirect to show page
        if ($pricing->approval_status === 'rejected' || $pricing->approval_status === 'pending') {
            return redirect()->route('pricing-master.show', Crypt::encryptString($pricing->id))
                ->with('success', 'Pricing Master updated successfully.');
        }

        return redirect()->route('pricing-master.index')->with('success', 'Pricing Master updated successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete pricing master')) {
            abort(403, 'Unauthorized action.');
        }
        $pricingMaster = PricingMaster::findOrFail(Crypt::decryptString($id));
        $pricingMaster->delete();
        $status     = true;
        $message    = "Pricing Master deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }

    public function submit(string $encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $pricing = PricingMaster::findOrFail($id);

        if ($pricing->approval_status === 'approved') {
            return back()->with('info', 'Already approved.');
        }

        $pricing->approval_status = 'pending';
        $pricing->submitted_at = now();
        $pricing->updated_by = Auth::id();
        $pricing->save();

        return back()->with('success', 'Submitted for approval.');
    }

    public function approve(Request $request, string $encryptedId)
    {

        $request->validate(['approval_note' => 'required|string|max:2000']);
        $id = Crypt::decryptString($encryptedId);
        $pricing = PricingMaster::findOrFail($id);

        $note = Note::where('pricing_master_id', $id)
            ->where('note_type', 3)
            ->first();
        
        $pricing->rate =  $note->price;
        $pricing->approval_status = 'approved';
        $pricing->approved_by = Auth::id();
        $pricing->approved_at = now();
        $pricing->approval_note = $request->input('approval_note');
        $pricing->updated_by = Auth::id();
        $pricing->save();
        //dd($pricing);
        if ($note) {
            $note->update([
                'note_type'           => 1,
                'approve_rejected_by' => Auth::id(),
                'approval_note' => $request->approval_note ?? null
            ]);
        }
         $creator = $pricing->creator()->first();
        if ($creator && !empty($creator->email)) {
            Mail::to($creator->email)->queue(
                new PricingMasterApprovedNotification($pricing, $encryptedId)
            );
        }

        return back()->with('success', 'Approved.');
    }

    public function reject(Request $request, string $encryptedId)
    {
        $request->validate(['approval_note' => 'required|string|max:2000']);

        $id = Crypt::decryptString($encryptedId);
        $pricing = PricingMaster::findOrFail($id);

        if ($pricing->approval_status !== 'pending') {
            return back()->with('warning', 'Record is not pending.');
        }

        $pricing->approval_status = 'rejected';
        $pricing->approved_by = Auth::id();
        $pricing->approved_at = now();
        $pricing->approval_note = $request->input('approval_note');
        $pricing->updated_by = Auth::id();
        $pricing->save();
        
        $creator = $pricing->creator()->first(); 
        if ($creator && !empty($creator->email)) {
            Mail::to($creator->email)->queue(
                new PricingMasterRejectedNotification($pricing, $encryptedId)
            );
        }

        return back()->with('success', 'Rejected.');
    }

    public function sendForApproval(string $encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $pricing = PricingMaster::findOrFail($id);

        // if ($pricing->approval_status !== 'rejected') {
        //     return back()->with('error', 'Can only send for approval after rejection.');
        // }

        $pricing->approval_status = 'pending';
        $pricing->submitted_at = now();
        $pricing->updated_by = Auth::id();
        $pricing->approved_by = null;
        $pricing->approved_at = null;
        $pricing->approval_note = null;
        $pricing->save();

        // Build encrypted token and computed fields
        $token = Crypt::encryptString((string) $pricing->id);
        $row = $this->findPricingWithComputed($pricing->id);

        $serviceOfferingNames = [];
        if (!empty($row->service_offerings)) {
            $serviceOfferingNames = array_values(
                array_filter(array_map('trim', explode(',', $row->service_offerings)))
            );
        }

        $currencyLabel = $row->currency_label ?? 'N/A';

        // Send to super admin from ENV
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
        if (!empty($adminEmail)) {
            Mail::to($adminEmail)->queue(
                new PricingMasterApprovalRequest($pricing, $token, $serviceOfferingNames, $currencyLabel)
            );
        }

        // Return JSON for AJAX requests, otherwise redirect back
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Your request is sent for approval.',
            ]);
        }

        return back()->with('success', 'Pricing Master sent for approval.');
    }

    public function needModification(Request $request, string $encryptedId)
    {
        if (!auth()->user()->hasRole('super admin')) {
            abort(403, 'Only super admin can request modifications.');
        }

        $request->validate([
            'modification_notes' => 'required|string|max:2000',
            'modification_parameter' => 'required|string|max:2000',
        ]);

        $id = Crypt::decryptString($encryptedId);
        $pricing = PricingMaster::findOrFail($id);

        if ($pricing->approval_status !== 'approved') {
            return back()->with('error', 'Can only request modifications for approved pricing.');
        }

        // Store modification notes but don't change approval status
        $pricing->modification_notes = $request->input('modification_notes');
        $pricing->modification_parameter = $request->input('modification_parameter');
        $pricing->updated_by = Auth::id();
        $pricing->save();

        // Send email to project manager (creator)
        $creator = $pricing->creator()->first();
        if ($creator && !empty($creator->email)) {
            Mail::to($creator->email)->queue(
                new PricingMasterModificationRequest(
                    $pricing,
                    $encryptedId,
                    $request->input('modification_notes'),
                    $request->input('modification_parameter')
                )
            );
        }

        return back()->with('success', 'Modification request sent to project manager.');
    }

    public function showHistory($id)
    {
        $pricing = PricingMaster::findOrFail($id);

        $activities = Activity::where('subject_type', PricingMaster::class)
            ->where('subject_id', $pricing->id)
            ->latest()
            ->get();

        return view('content.pricing-master.history', compact('pricing', 'activities'));
    }

    // private function validateForm(Request $request): array
    // {
    //     return $request->validate([
    //         // Always required
    //         'pricing_type'            => 'required|in:static,custom',
    //         'industry_vertical_id'    => 'required|integer',
    //         'department_id'           => 'required|integer',
    //         'service_offering_id'     => 'required|integer',
    //         'unit_of_measurement_id'  => 'required|integer',
    //         'description_id'          => 'required|integer',
    //         'currency_id'             => 'required|integer',
    //         'rate'                    => 'required|numeric|min:0.01',
    //         'name'                    => 'required|string|max:255',
    //         'status'                  => 'required|in:0,1',
    //         'document'                => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'],

    //         // Custom-only: completely skip these unless pricing_type=custom
    //         'project_management_cost' => 'exclude_unless:pricing_type,custom|nullable|numeric|min:0.01',
    //         'vendor_cost'             => 'exclude_unless:pricing_type,custom|nullable|numeric|min:0.01',
    //         'infrastructure_cost'     => 'exclude_unless:pricing_type,custom|nullable|numeric|min:0.01',
    //         'overhead_percentage'     => 'exclude_unless:pricing_type,custom|nullable|numeric|between:0,100',
    //         'margin_percentage'       => 'exclude_unless:pricing_type,custom|nullable|numeric|between:0,100',
    //         'volume'                  => 'exclude_unless:pricing_type,custom|nullable|numeric|min:0.01',
    //         'volume_based_discount'   => 'exclude_unless:pricing_type,custom|nullable|numeric',
    //         'conversion_rate'         => 'exclude_unless:pricing_type,custom|nullable|numeric|min:0.0001',

    //         // Skills arrays: also skip entirely unless custom
    //         'skills'                  => 'exclude_unless:pricing_type,custom|array|min:1',
    //         'skills.*'                => 'exclude_unless:pricing_type,custom|integer|distinct',
    //         'average_handling_time'   => 'exclude_unless:pricing_type,custom|array|min:1',
    //         'average_handling_time.*' => 'exclude_unless:pricing_type,custom|integer|min:1',
    //     ]);
    // }
    private function validateForm(Request $request, $excludeId = null): array
    {
        $rules = [
            'customer_id'          => 'nullable|integer|exists:companies,id',
            'pricing_type'         => 'required|in:static,custom',
            'custom_pricing_type'  => 'exclude_unless:pricing_type,custom|required_with:pricing_type|in:fixed,variable',
            // Existing fields → nullable, but required unless custom_pricing_type is fixed
            'industry_vertical_id'   => 'nullable|integer|required_unless:custom_pricing_type,fixed',
            'department_id'          => 'nullable|integer|required_unless:custom_pricing_type,fixed',
            'service_offering_id'    => 'nullable|integer|required_unless:custom_pricing_type,fixed',
            'unit_of_measurement_id' => 'nullable|integer|required_unless:custom_pricing_type,fixed',
            'description_id'         => 'nullable|integer|required_unless:custom_pricing_type,fixed',

            // Always required
            'currency_id'          => 'required|integer',
            'rate'                 => 'required|numeric|min:0.01',
            'name'                 => 'required|string|max:255',
            'status'               => 'required|in:0,1',
            'document'             => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'],

            // ---- Custom-only AND not fixed (i.e., variable) ----
            // Use both exclude_unless:pricing_type,custom AND exclude_if:custom_pricing_type,fixed
            'project_management_cost' => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|min:0.01|required',
            'vendor_cost'             => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|min:0.01',
            'infrastructure_cost'     => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|min:0.01|required',
            'overhead_percentage'     => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|between:0,100|required',
            'margin_percentage'       => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|between:0,100|required',
            'volume'                  => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|min:0.01',
            'volume_based_discount'   => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric',
            'conversion_rate'         => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|nullable|numeric|min:0.0001|required',

            // Skills arrays (Custom + Variable only)
            'skills'                  => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|array|min:1|required',
            'skills.*'                => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|integer|distinct|required',
            'average_handling_time'   => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|array|min:1|required',
            'average_handling_time.*' => 'exclude_unless:pricing_type,custom|exclude_if:custom_pricing_type,fixed|integer|min:1|required',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        // Add custom validation for static pricing type duplicate check
        if ($request->input('pricing_type') === 'static' && 
            $request->filled('industry_vertical_id') && 
            $request->filled('department_id') && 
            $request->filled('service_offering_id')) {
            
            $validator->after(function ($validator) use ($request, $excludeId) {
                $query = \App\Models\PricingMaster::where('pricing_type', 'static')
                    ->where('industry_vertical_id', $request->input('industry_vertical_id'))
                    ->where('department_id', $request->input('department_id'))
                    ->where('service_offering_id', $request->input('service_offering_id'))
                    ->whereNull('deleted_at');
                
                // Exclude current record on update
                if ($excludeId) {
                    $query->where('id', '!=', $excludeId);
                }
                
                if ($query->exists()) {
                    $validator->errors()->add(
                        'industry_vertical_id',
                        'You already have a pricing for that combination of Industry Vertical, Department, and Service Offering.'
                    );
                }
            });
        }

        return $validator->validate();
    }

    private function syncSkillLines(PricingMaster $pricing, array $skills, array $ahts): void
    {
        $lines = [];
        foreach ($skills as $i => $skillId) {
            if (!$skillId) continue;
            $aht = $ahts[$i] ?? null;
            if (!$aht) continue;

            $lines[] = new PricingMasterSkillLine([
                'skill_id' => (int)$skillId,
                'average_handling_time' => (int)$aht,
            ]);
        }
        if (count($lines)) {
            $pricing->skillLines()->saveMany($lines);
        }
    }

    public function show(string $encryptedId)
    {
        $id =  Crypt::decryptString($encryptedId);
        $note = Note::where('pricing_master_id', $id)
            ->where('note_type', 3)
            ->first();


        $data = PricingMaster::with(['skillLines', 'creator', 'updater', 'approver'])->findOrFail($id);
        if ($note) {
            $price = $note->price;
        } else {
            $price = $data->rate;
        }
        $title = 'Pricing Details';
        return view('content.pricing-master.show', compact('title', 'data', 'price'));
    }

    // public function byDepartment($departmentId)
    // {
    //     $items = PricingMaster::query()
    //         ->where('department_id', $departmentId)
    //         ->where('status',1)
    //         ->where('approval_status','approved')
    //         ->orderBy('name')
    //         ->get(['id', 'name']);

    //     return response()->json([
    //         'data' => $items,
    //     ]);
    // }

 public function byDepartment(Request $request, $departmentId)
{
    if (empty($departmentId) || !ctype_digit((string) $departmentId)) {
        return response()->json(['data' => []]);
    }

    // Optional filters
    $industryId = $request->input('industry_vertical_id');
    $serviceId  = $request->input('service_offering_id');
    $uiType     = $request->input('pricing_type'); // 'standard' | 'fixed'
    $customerId = $request->input('customer_id');
    $projectId  = $request->input('project_id');   // optional, only for custom (fixed)

    // Map UI -> DB enum on pricing_masters.pricing_type ('static' | 'custom')
    $typeMap = ['standard' => 'static', 'fixed' => 'custom'];
    $dbType  = isset($typeMap[strtolower((string) $uiType)]) ? $typeMap[strtolower($uiType)] : null;

    // ---------- Base query ----------
    $query = PricingMaster::query()
        ->where('department_id', (int) $departmentId)
        ->where('status', 1)
        ->when($industryId, fn($q) => $q->where('industry_vertical_id', (int) $industryId))
        ->when($serviceId,  fn($q) => $q->where('service_offering_id', (int) $serviceId))
        ->when($dbType,     fn($q) => $q->where('pricing_type', $dbType));

    // ---------- Custom Pricing Only ----------
    if ($dbType === 'custom') {
        // Apply customer filter if provided
        if ($customerId) {
            $query->where('customer_id', (int) $customerId);
        }

        // Exclude pricings already used in projects (except current project)
        $query->whereNotExists(function ($sub) use ($projectId) {
            $sub->select(DB::raw(1))
                ->from('projects')
                ->whereColumn('projects.pricing_id', 'pricing_masters.id')
                ->whereNull('projects.deleted_at');

            if ($projectId) {
                $sub->where('projects.id', '!=', (int) $projectId);
            }
        });
    }

    $items = $query->orderBy('name')->get(['id', 'name']);

    // ---------- Fallback (custom only) ----------
    // if ($dbType === 'custom' && $items->isEmpty() && $customerId) {
    //     $fallback = PricingMaster::query()
    //         ->where('status', 1)
    //         ->where('pricing_type', 'custom')
    //         ->where('customer_id', (int) $customerId)
    //         ->whereNotExists(function ($sub) use ($projectId) {
    //             $sub->select(DB::raw(1))
    //                 ->from('projects')
    //                 ->whereColumn('projects.pricing_id', 'pricing_masters.id')
    //                 ->whereNull('projects.deleted_at');

    //             if ($projectId) {
    //                 $sub->where('projects.id', '!=', (int) $projectId);
    //             }
    //         })
    //         ->orderBy('name')
    //         ->get(['id', 'name']);

    //     if ($fallback->isNotEmpty()) {
    //         $items = $fallback;
    //     }
    // }

    // // ---------- Include project’s own pricing (custom only) ----------
    // if ($dbType === 'custom' && $projectId) {
    //     $project = \App\Models\Project::with('pricing:id,name')->find((int) $projectId);

    //     if ($project && $project->pricing && !$items->contains('id', $project->pricing->id)) {
    //         $items->prepend($project->pricing);
    //     }
    // }

    return response()->json(['data' => $items]);
}

// function findPricingWithComputed(int $id) {
//     return DB::table('pricing_masters as pm')
//         ->leftJoin('currencies as c', 'c.id', '=', 'pm.currency_id')
//         ->select('pm.*')
//         ->selectRaw("
//             CASE
//               WHEN pm.pricing_type = 'custom' THEN (
//                 SELECT GROUP_CONCAT(DISTINCT so.name ORDER BY so.name SEPARATOR ', ')
//                 FROM projects pr
//                 JOIN service_offerings so ON so.id = pr.service_offering_id
//                 WHERE pr.pricing_id = pm.id
//               )
//               ELSE (
//                 SELECT so.name
//                 FROM service_offerings so
//                 WHERE so.id = pm.service_offering_id
//                 LIMIT 1
//               )
//             END AS service_offerings
//         ")
//         ->selectRaw("COALESCE(c.name, 'N/A') AS currency_label")
//         ->where('pm.id', $id)
//         ->first();
// }
function findPricingWithComputed(int $id) {
    return DB::table('pricing_masters as pm')
        ->leftJoin('currencies as c', 'c.id', '=', 'pm.currency_id')
        ->leftJoin('service_offerings as so', 'so.id', '=', 'pm.service_offering_id')
        ->select('pm.*')
        ->selectRaw('COALESCE(so.name, "") AS service_offerings')
        ->selectRaw("COALESCE(c.name, 'N/A') AS currency_label")
        ->where('pm.id', $id)
        ->first();
}


}
