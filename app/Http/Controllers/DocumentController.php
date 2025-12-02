<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helpers;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentAlert;
use App\Models\DocumentContract;
use App\DataTables\DocumentDataTable;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DocumentDataTable $dataTable)
    {
        if (!auth()->user()->can('view document')) {
            abort(403, 'Unauthorized action.');
        }
        $customers = \App\Models\Company::orderBy('name')->get(['id', 'name']);
        return $dataTable->render('content.document.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!auth()->user()->can('create document')) {
            abort(403, 'Unauthorized action.');
        }

        $title = 'Create Document';
        $type  = 'create';
        $users = Helpers::getUsersByRole('project manager');

        // Try to decrypt preselected customer from ?customer=ENC
        $presetCustomerId = null;
        if ($enc = $request->query('customer')) {
            try {
                // 1) direct decrypt
                $presetCustomerId = (int) Crypt::decryptString($enc);
            } catch (\Throwable $e) {
                // 2) if the encrypted string was URL-safe base64 wrapped, unwrap then decrypt
                try {
                    $cipher = base64_decode(strtr($enc, '-_', '+/'));
                    $presetCustomerId = (int) Crypt::decryptString($cipher);
                } catch (\Throwable $e2) {
                    $presetCustomerId = null; // ignore if not decryptable
                }
            }
        }

        // (Optional) ensure the company exists; if not, null it out
        if ($presetCustomerId && !Company::whereKey($presetCustomerId)->exists()) {
            $presetCustomerId = null;
        }

        // Masters & lists for the form
        $data      = Helpers::getDocumentMasterData();
        $customers = Company::orderBy('name')->get();
        $document  = null;

        // Pass $presetCustomerId so the form can preselect/lock the customer
        return view('content.document.form', compact(
            'title',
            'type',
            'customers',
            'users',
            'document',
            'presetCustomerId'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create document')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $this->validateData($request);

        // handle array → single id
        // Ensure project_manager_id key exists
        if (!isset($data['project_manager_id'])) {
            $data['project_manager_id'] = null;
        }

        // handle array → single id
        if (is_array($data['project_manager_id'])) {
            $data['project_manager_id'] = $data['project_manager_id'][0] ?? null;
        }

        // Remove alerts and contracts from data array (handled separately)
        $alertsData = $data['alerts'] ?? [];
        $contractsData = $data['contracts'] ?? [];
        unset($data['alerts'], $data['contracts']);

        // Create document first to get the ID
        $document = Document::create($data);

        // ✅ handle file upload with document ID in filename
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // Sanitize filename - remove special characters
            $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
            $extension = $file->getClientOriginalExtension();
            $uniqueId = uniqid();
            $fileName = "document_{$document->id}_{$originalName}_{$uniqueId}.{$extension}";
            $data['file_path'] = $file->storeAs('documents', $fileName, 'public');
            $document->update(['file_path' => $data['file_path']]);
        }

        // ✅ handle multiple contracts and their alerts
        $alertFiles = $request->allFiles();
        if (!empty($contractsData)) {
            foreach ($contractsData as $index => $contractData) {
                if (!empty($contractData['contract_start_date']) && !empty($contractData['contract_end_date'])) {
                    $contract = DocumentContract::create([
                        'document_id' => $document->id,
                        'contract_start_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $contractData['contract_start_date'])->format('Y-m-d'),
                        'contract_end_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $contractData['contract_end_date'])->format('Y-m-d'),
                        'is_active' => isset($contractData['is_active']) ? (bool)$contractData['is_active'] : true,
                    ]);

                    // Create alerts for this contract if active AND (alert days are selected OR file is uploaded)
                    $hasAlertDays = !empty($alertsData[$index]['alert_days']);
                    $hasFile = isset($alertFiles['alerts'][$index]['alert_file']);
                    
                    if ($contract->is_active && ($hasAlertDays || $hasFile)) {
                        $alertDays = [];
                        if ($hasAlertDays) {
                            $alertDays = $alertsData[$index]['alert_days'];
                            if (!is_array($alertDays)) {
                                $alertDays = [$alertDays];
                            }
                        }
                        
                        // Create one alert record with all selected days (can be empty if only file is uploaded)
                        $alert = new DocumentAlert([
                            'document_id' => $document->id,
                            'contract_id' => $contract->id,
                            'file_alert' => true,
                            'alert_days' => $alertDays,
                        ]);

                        // Handle file upload for this alert with document ID in filename
                        if ($hasFile) {
                            $file = $alertFiles['alerts'][$index]['alert_file'];
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            // Sanitize filename - remove special characters
                            $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                            $extension = $file->getClientOriginalExtension();
                            $uniqueId = uniqid();
                            $fileName = "alert_{$document->id}_{$originalName}_{$uniqueId}.{$extension}";
                            $alert->alert_file = $file->storeAs('documents/alerts', $fileName, 'public');
                        }

                        $alert->save();
                    }
                }
            }
        }

        return redirect()->route('document.index')->with('success', 'Document created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encryptedId)
    {
        $id = Crypt::decryptString($encryptedId);
        $document = Document::with(['contracts.alerts', 'contracts', 'alerts.contract'])->findOrFail($id);
        $title = 'Document Details';
        $customers = Company::orderBy('name')->get(['id', 'name']);
        $users = Helpers::getUsersByRole('project manager');
        $projectManager = $users->firstWhere('id', $document->project_manager_id);
        $customerId = old('customer_id', $document->customer_id);
        $selectedPm = old('project_manager_id', $document->project_manager_id);

        return view('content.document.show', compact('document', 'title', 'customers', 'projectManager', 'customerId', 'selectedPm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($encryptedId)
    {
        // dd($id);
        if (!auth()->user()->can('edit document')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($encryptedId);
        $document = Document::with(['alerts.contract', 'contracts.alerts'])->findOrFail($id);
        // dd($document);
        //$data  = Helpers::getDocumentMasterData();
        $title = 'Edit Document';
        $type = 'edit';
        $customers = Company::orderBy('name')->get(['id', 'name']);
        // $customers = Company::get();
        $users = Helpers::getUsersByRole('project manager');
        $customerId = old('customer_id', $document->customer_id);
        $selectedPm = old('project_manager_id', $document->project_manager_id);

        return view('content.document.form', compact('document', 'title', 'type', 'customers', 'users', 'customerId', 'selectedPm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($id);
        abort_unless(auth()->user()->can('edit document'), 403, 'Unauthorized action.');
        $document = Document::findOrFail($id);

        // Validate request
        $data = $this->validateData($request);

        if (is_array($data['project_manager_id'])) {
            $data['project_manager_id'] = $data['project_manager_id'][0] ?? null;
        }

        // Handle file upload with document ID in filename
        if ($request->hasFile('file_path')) {
            // Delete old file if it exists
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Store new file with document ID in filename
            $file = $request->file('file_path');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // Sanitize filename - remove special characters
            $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
            $extension = $file->getClientOriginalExtension();
            $uniqueId = uniqid();
            $fileName = "document_{$document->id}_{$originalName}_{$uniqueId}.{$extension}";
            $data['file_path'] = $file->storeAs('documents', $fileName, 'public');
        }

        // Remove alerts and contracts from data array (handled separately)
        $alertsData = $data['alerts'] ?? [];
        $contractsData = $data['contracts'] ?? [];
        unset($data['alerts'], $data['contracts']);

        // Update document
        $document->update($data);

        // ✅ Sync multiple contracts and their alerts
        $alertFiles = $request->allFiles();
        
        // Get existing alerts to delete their files if replaced
        $existingAlerts = $document->alerts;
        
        // Delete old contracts and alerts (will recreate from submitted data)
        $document->contracts()->delete();
        $document->alerts()->delete();

        // Create new contracts and alerts from submitted data
        if (!empty($contractsData)) {
            foreach ($contractsData as $index => $contractData) {
                if (!empty($contractData['contract_start_date']) && !empty($contractData['contract_end_date'])) {
                    $contract = DocumentContract::create([
                        'document_id' => $document->id,
                        'contract_start_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $contractData['contract_start_date'])->format('Y-m-d'),
                        'contract_end_date' => \Carbon\Carbon::createFromFormat('m/d/Y', $contractData['contract_end_date'])->format('Y-m-d'),
                        'is_active' => isset($contractData['is_active']) ? (bool)$contractData['is_active'] : true,
                    ]);

                    // Create alerts for this contract if active AND (alert days are selected OR file is uploaded OR existing file exists)
                    $hasAlertDays = !empty($alertsData[$index]['alert_days']);
                    $hasFile = isset($alertFiles['alerts'][$index]['alert_file']);
                    $hasExistingFile = !empty($alertsData[$index]['existing_file']);
                    
                    if ($contract->is_active && ($hasAlertDays || $hasFile || $hasExistingFile)) {
                        $alertDays = [];
                        if ($hasAlertDays) {
                            $alertDays = $alertsData[$index]['alert_days'];
                            if (!is_array($alertDays)) {
                                $alertDays = [$alertDays];
                            }
                        }
                        
                        $alert = new DocumentAlert([
                            'document_id' => $document->id,
                            'contract_id' => $contract->id,
                            'file_alert' => true,
                            'alert_days' => $alertDays,
                        ]);

                        // Handle file upload - check if new file is uploaded
                        if ($hasFile) {
                            // New file uploaded - delete old file if exists
                            if ($hasExistingFile && Storage::disk('public')->exists($alertsData[$index]['existing_file'])) {
                                Storage::disk('public')->delete($alertsData[$index]['existing_file']);
                            }
                            // Store new file with document ID in filename
                            $file = $alertFiles['alerts'][$index]['alert_file'];
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            // Sanitize filename - remove special characters
                            $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
                            $extension = $file->getClientOriginalExtension();
                            $uniqueId = uniqid();
                            $fileName = "alert_{$document->id}_{$originalName}_{$uniqueId}.{$extension}";
                            $alert->alert_file = $file->storeAs('documents/alerts', $fileName, 'public');
                        } elseif ($hasExistingFile) {
                            // Keep existing file
                            $alert->alert_file = $alertsData[$index]['existing_file'];
                        }

                        $alert->save();
                    }
                }
            }
        }
        
        // Delete files from alerts that were removed
        foreach ($existingAlerts as $oldAlert) {
            if ($oldAlert->alert_file && Storage::disk('public')->exists($oldAlert->alert_file)) {
                // Check if this file is still being used
                $stillInUse = false;
                if (!empty($alertsData)) {
                    foreach ($alertsData as $alertData) {
                        if (!empty($alertData['existing_file']) && $alertData['existing_file'] === $oldAlert->alert_file) {
                            $stillInUse = true;
                            break;
                        }
                    }
                }
                if (!$stillInUse) {
                    Storage::disk('public')->delete($oldAlert->alert_file);
                }
            }
        }

        return redirect()->route('document.index')->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete document')) {
            abort(403, 'Unauthorized action.');
        }
        $pricingMaster = Document::findOrFail(Crypt::decryptString($id));
        $pricingMaster->delete();
        $status     = true;
        $message    = "Document deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }

    /**
     * Download or view a document file.
     */
    public function downloadFile($encryptedId, $type = 'document')
    {
        if (!auth()->user()->can('view document')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $document = Document::findOrFail($id);

        $filePath = null;
        if ($type === 'document' && $document->file_path) {
            $filePath = $document->file_path;
        } elseif ($type === 'alert') {
            // For alert files, we need to get the alert file path
            // This would need the alert ID passed as well, or we can get the first alert
            $alert = $document->alerts()->whereNotNull('alert_file')->first();
            if ($alert && $alert->alert_file) {
                $filePath = $alert->alert_file;
            }
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->response($filePath);
    }

    /**
     * Download or view an alert file.
     */
    public function downloadAlertFile($encryptedDocumentId, $encryptedAlertId)
    {
        if (!auth()->user()->can('view document')) {
            abort(403, 'Unauthorized action.');
        }

        $documentId = Crypt::decryptString($encryptedDocumentId);
        $alertId = Crypt::decryptString($encryptedAlertId);

        $document = Document::findOrFail($documentId);
        $alert = $document->alerts()->findOrFail($alertId);

        if (!$alert->alert_file || !Storage::disk('public')->exists($alert->alert_file)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('public')->response($alert->alert_file);
    }

    protected function validateData(Request $request, ?int $projectId = null, bool $debug = false): array
    {
        $rules = [
            'customer_id'   => ['required', 'exists:companies,id'],
            'description'   => ['required', 'string', 'max:1000'],
            'contracts' => ['required', 'array', 'min:1'],
            'contracts.*.contract_start_date' => ['required', 'date_format:m/d/Y'],
            'contracts.*.contract_end_date' => ['required', 'date_format:m/d/Y'],
            'project_manager_id' => ['nullable', 'array'],
            'project_manager_id.*' => ['nullable', 'exists:users,id'],
            'status'             => ['required'],
            'industry_vertical_id'      => ['required', 'exists:industry_verticals,id'],
            'department_id'             => ['required', 'exists:departments,id'],
            'file_path' => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'],
            'contracts.*.is_active' => ['nullable', 'boolean'],
            'alerts' => ['nullable', 'array'],
            'alerts.*.alert_days' => ['nullable', 'array'],
            'alerts.*.alert_days.*' => ['required_with:alerts.*.alert_days', 'integer', 'in:7,15,30,60'],
            'alerts.*.alert_file' => ['required_without:alerts.*.existing_file', 'file', 'max:5120', 'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg'],
            'alerts.*.existing_file' => ['nullable', 'string'],



        ];

        $messages = [
            'contracts.required' => 'At least one contract date range is required.',
            'contracts.min' => 'At least one contract date range is required.',
            'contracts.*.contract_end_date.after_or_equal' => 'Contract end date must be the same or after start date.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($debug) {
            if ($validator->fails()) {
                dd([
                    'validation' => 'failed',
                    'errors'     => $validator->errors()->toArray(),
                    'input'      => $request->all(),
                ]);
            }

            $data = $validator->validated();
            // Normalize booleans to ensure consistent storage
            $data['is_recurring'] = $request->boolean('is_recurring');
            dd([
                'validation' => 'passed',
                'validated'  => $data,
            ]);
        }

        if ($validator->fails()) {
            // Normal Laravel behavior (redirect back with errors & old input)
            throw new ValidationException($validator);
        }

        $data = $validator->validated();

        // Normalize booleans (Blade switch -> true/false)
        $data['is_recurring'] = $request->boolean('is_recurring');

        return $data;
    }
}
