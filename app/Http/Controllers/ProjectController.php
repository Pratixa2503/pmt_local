<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Project;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\ProjectDataTable;
use App\Models\User;
use App\Models\PricingMaster;
use App\Http\Requests\StoreProjectIntakeRequest; // FormRequest (below)
use App\Models\ProjectIntake;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Models\ProjectMemberAssignment;
use App\Http\Requests\GenericFileUploadRequest;
use App\Imports\ProjectIntakesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\ServiceOffering;
use App\Mail\StageNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use App\Models\MainTask;

class ProjectController extends Controller
{
    public function index(ProjectDataTable $dataTable)
    {
        return $dataTable->render('content.project.index'); // the Blade we created earlier
    }

    public function create(Request $request)
    {
        $title  = "Create Project";
        $type   = "create";
        $data   = Helpers::getProjectMasterData();
        $users  = Helpers::getUsersByRole('project manager');
        $customers = Company::get();

        // ── Inputs (encrypted)
        $parentEncrypted   = $request->query('parent');
        $customerEncrypted = $request->query('customer');

        $parentId          = null;
        $presetCustomerId  = null;   // <-- what we'll pass to the view to preselect the customer
        $parentProjectCategory = null; // <-- parent's project_category for sub-projects

        // If parent is supplied, prefer parent's customer_id and project_category
        if (!empty($parentEncrypted)) {
            try {
                $parentId = Crypt::decryptString($parentEncrypted);
                $parent   = Project::select('id', 'customer_id', 'project_category')->find($parentId);

                if ($parent) {
                    $presetCustomerId = (int) $parent->customer_id;
                    $parentProjectCategory = $parent->project_category;
                    $title = "Create Sub Project";
                }
            } catch (DecryptException $e) {
                // ignore bad parent id (stay in "Create Project")
            }
        }

        // If no parent (or parent didn't resolve), allow explicit customer param
        if ($presetCustomerId === null && !empty($customerEncrypted)) {
            try {
                $presetCustomerId = (int) Crypt::decryptString($customerEncrypted);
                // (optional) ensure customer exists
                if (! Company::whereKey($presetCustomerId)->exists()) {
                    $presetCustomerId = null; // reset if invalid
                }
            } catch (DecryptException $e) {
                // ignore bad customer id
            }
        }

        // You can also pass the full model if you want the form to lock/read-only the select
        $presetCustomer = $presetCustomerId ? Company::find($presetCustomerId) : null;

        // Get main tasks for the multi-select field (only task_type = 1, Productive)
        $mainTasks = MainTask::where('status', 1)->where('task_type', 1)->orderBy('name')->get(['id', 'name']);

        return view(
            'content.project.form',
            compact('data', 'title', 'type', 'customers', 'users', 'parentId', 'presetCustomerId', 'presetCustomer', 'parentProjectCategory', 'mainTasks')
        );
    }

    public function store(Request $request)
    {

        $data = $this->validateData($request);
        $project = Project::create($data);

        $project->pocs()->sync($data['pocs']);
        $project->pms()->sync($data['pm_ids']);
        
        // Sync main tasks (optional field)
        $mainTaskIds = collect($request->input('main_task_ids', []))
            ->filter(fn($v) => filled($v))
            ->map(fn($v) => (int) $v)
            ->values()
            ->all();
        $project->mainTasks()->sync($mainTaskIds);

        $this->syncMemberAssignments($project, $request);

        return redirect()->route('projects.index')->with('success', 'Project created.');
    }

    public function edit(string $encryptedId)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decryptString($encryptedId);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404);
        }

        // Load the project with selected PM/POC ids for preselect in the form
        $project = Project::with(['pms:id', 'pocs:id', 'mainTasks:id'])->findOrFail($id);

        $parentId = $project->parent_id ?? null;
        $title    = $parentId ? 'Edit Sub Project' : 'Edit Project';
        $type     = 'edit';
        
        // Get parent's project_category if this is a sub-project
        $parentProjectCategory = null;
        if ($parentId) {
            $parent = Project::select('project_category')->find($parentId);
            if ($parent) {
                $parentProjectCategory = $parent->project_category;
            }
        }

        // (Customers list is no longer shown, but harmless to keep;
        // you can remove it if truly unused elsewhere in the form.)
        $customers = Company::orderBy('name')->get(['id', 'name']);

        // PM list
        $users = Helpers::getUsersByRole('project manager');

        // Pricing options (preloaded for the project's department so the select is populated on load)
        $pricing = PricingMaster::when($project->department_id, fn($q) => $q->where('department_id', $project->department_id))
            ->orderBy('name')
            ->get(['id', 'name']);

        // This is what the Blade uses as the value for the hidden <input name="customer_id" id="customer_id">
        $presetCustomerId = (string) old('customer_id', $project->customer_id);

        // 🔁 Seed POCs for the selected/hidden customer so the repeater can render immediately
        $pocsForCustomer = User::query()
            ->when($presetCustomerId, fn($q) => $q->where('company_id', $presetCustomerId))
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'email']);

        // 👥 Members-by-PM only for category == 1 (so the Members multi-selects can pre-populate)
        $membersByPm = [];
        if ((int) ($project->project_category ?? 1) === 1) {
            $membersByPm = ProjectMemberAssignment::where('project_id', $project->id)
                ->get(['pm_id', 'member_id'])
                ->groupBy('pm_id')
                ->map(fn($g) => $g->pluck('member_id')->unique()->values()->all())
                ->toArray();
        }

        // Get main tasks for the multi-select field (only task_type = 1, Productive)
        $mainTasks = MainTask::where('status', 1)->where('task_type', 1)->orderBy('name')->get(['id', 'name']);

        return view('content.project.form', compact(
            'id',
            'title',
            'type',
            'project',
            'customers',
            'users',
            'pricing',
            'pocsForCustomer',   // used to seed window.__pocSeed
            'presetCustomerId',  // used to fill the hidden #customer_id
            'parentId',
            'parentProjectCategory',
            'membersByPm',
            'mainTasks'
        ));
    }

    public function update(Request $request, int $id)
    {
        $project = Project::findOrFail($id);
        $data = $this->validateData($request, $id);

        $project->update($data);
        $project->pocs()->sync($data['pocs']);
        $project->pms()->sync($data['pm_ids']);
        
        // Sync main tasks (optional field)
        $mainTaskIds = collect($request->input('main_task_ids', []))
            ->filter(fn($v) => filled($v))
            ->map(fn($v) => (int) $v)
            ->values()
            ->all();
        $project->mainTasks()->sync($mainTaskIds);

        $this->syncMemberAssignments($project, $request); // << applies only if category == 1

        return redirect()->route('projects.index')->with('success', 'Project updated.');
    }



    public function destroy($encryptedId)
    {

        $id = Crypt::decryptString($encryptedId);
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project Deleted.');
    }

    // --- Helpers ---

    protected function formData()
    {
        // $customers = Customer::orderBy('name')->get(['id','name']);
        // $users     = User::orderBy('first_name')->get(['id','first_name','last_name','email']);

        // // You likely fetch these from each master model; adapt as needed.
        // $masters = [
        //     'project_types'           => \App\Models\ProjectType::orderBy('name')->get(),
        //     'departments'             => \App\Models\Department::orderBy('name')->get(),
        //     'input_output_formats'    => \App\Models\InputOutputFormat::orderBy('name')->get(),
        //     'modes_of_delivery'       => \App\Models\ModeOfDelivery::orderBy('name')->get(),
        //     'frequencies_of_delivery' => \App\Models\ProjectDeliveryFrequency::orderBy('name')->get(),
        //     'project_priorities'      => \App\Models\ProjectPriority::orderBy('name')->get(),
        //     'project_statuses'        => \App\Models\ProjectStatus::orderBy('name')->get(),
        // ];

        // $pricing = \App\Models\Pricing::orderBy('name')->get();

        //return [$customers, $users, $masters, $pricing];
    }


    protected function validateData(Request $request, ?int $projectId = null): array
    {
        // Start with all inputs
        $input = $request->all();

        // --- Normalize dates from DD-MM-YYYY to YYYY-MM-DD ---
        $input['start_date'] = $this->dmyToYmd($request->input('start_date'));
        $input['end_date']   = $this->dmyToYmd($request->input('end_date'));

        // --- Normalize booleans ---
        $input['is_recurring'] = (bool) $request->boolean('is_recurring');

        // If not recurring, null-out recurring_type so it won't fail rules
        if (!$input['is_recurring']) {
            $input['recurring_type'] = null;
        }

        // --- Sanitize repeater arrays (drop blanks, cast to int) ---
        $input['pocs'] = collect($request->input('pocs', []))
            ->filter(fn($v) => filled($v))
            ->map(fn($v) => (int) $v)
            ->values()
            ->all();

        $input['pm_ids'] = collect($request->input('pm_ids', []))
            ->filter(fn($v) => filled($v))
            ->map(fn($v) => (int) $v)
            ->values()
            ->all();

        // --- Unique rule for project_name (ignore soft-deleted; ignore current row on update) ---
        $uniqueProjectName = Rule::unique('projects', 'project_name')->whereNull('deleted_at');
        if ($projectId) {
            $uniqueProjectName = $uniqueProjectName->ignore($projectId);
        }

        // --- Build validation rules ---
        $rules = [
            'project_category'          => ['required', Rule::in([1, 2, 3, 8])],
            'project_name'              => ['required', 'string', 'max:255', $uniqueProjectName],
            'description'               => ['required', 'string', 'max:10000'],

            'is_recurring'              => ['nullable', 'boolean'],
            'recurring_type'            => [
                Rule::requiredIf(fn() => (bool) $input['is_recurring']),
                Rule::in(['weekly', 'monthly', 'yearly', 'biweekly']),
                'nullable' // allow null when not recurring
            ],

            // Dates are in Y-m-d now
            'start_date'                => ['required', 'date'],
            'end_date'                  => ['required', 'date', 'after_or_equal:start_date'],

            'customer_id'               => ['required', 'exists:companies,id'],
            //'project_type_id'           => ['required', 'exists:project_types,id'],
            'department_id'             => ['required', 'exists:departments,id'],
            'pricing_id'                => ['nullable', 'exists:pricing_masters,id'],
            //'pricing_id'                => ['required_unless:project_category,2', 'integer', 'exists:pricing_masters,id'],
            'pricing_type'              => ['required'],
            'input_format_id'           => ['required', 'exists:input_output_formats,id'],
            'output_format_id'          => ['required', 'exists:input_output_formats,id'],
            'mode_of_delivery_id'       => ['required', 'exists:mode_of_deliveries,id'],
            'frequency_of_delivery_id'  => ['required', 'exists:project_delivery_frequencies,id'],
            'project_priority_id'       => ['required', 'exists:project_priorities,id'],
            'project_status_id'         => ['required', 'exists:project_statuses,id'],
            'parent_id'                 => ['nullable', 'integer', 'exists:projects,id'],
            'industry_vertical_id'      => ['required', 'exists:industry_verticals,id'],
            'service_offering_id'       => ['required', 'exists:service_offerings,id'],
            // Repeaters (already sanitized)
            'pocs'                      => ['required', 'array', 'min:1'],
            'pocs.*'                    => ['integer', 'distinct', 'exists:users,id'],
            'pm_ids'                    => ['required', 'array', 'min:1'],
            'pm_ids.*'                  => ['integer', 'distinct', 'exists:users,id'],

            'suite_id'                  => ['nullable', 'string', 'max:255'],
        ];

        $messages = [
            'pocs.min'                  => 'Please select at least one POC.',
            'pm_ids.min'                => 'Please select at least one Project Manager.',
            'end_date.after_or_equal'   => 'End date must be the same or after Start date.',
            'recurring_type.required'   => 'Please choose a repeat cadence.',
            'industry_vertical_id'      => 'Please choose a Industry vertical.',
            'pricing_id'                => 'Please choose a Pricing.',
        ];

        // Validate AGAINST THE NORMALIZED $input (not $request->all())
        $validator = Validator::make($input, $rules, $messages);

        if ($validator->fails()) {
            // dd(throw new ValidationException($validator));
            throw new ValidationException($validator);
        }

        $data = $validator->validated();
        // dd($data);
        // Make sure the boolean is a real bool in the returned payload
        $data['is_recurring'] = (bool) $input['is_recurring'];

        return $data;
    }


    public function subprojects(string $encryptedParentId)
    {
        try {
            $parentId = Crypt::decryptString($encryptedParentId);
        } catch (\Throwable $e) {
            return response()->json(['html' => '<div class="p-2 text-danger">Invalid parent.</div>']);
        }

        $subs = Project::query()
            ->leftJoin('companies as c', 'c.id', '=', 'projects.customer_id')
            // ->leftJoin('project_types as pt', 'pt.id', '=', 'projects.project_type_id')
            ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
            ->where('projects.parent_id', $parentId)
            ->select([
                'projects.id',
                'projects.project_name',
                'projects.start_date',
                'projects.end_date',
                'c.name as customer_name',
                // 'pt.name as project_type_name',
                'ps.name as status_name',
            ])
            ->orderByDesc('projects.id');

        // 🚀 Role-based restrictions
        $user = auth()->user();
        if ($user && ! $user->hasRole('super admin')) {

            // Join intakes and dedupe (needed for the intake-based roles)
            $subs->join('project_intakes as pi', 'pi.parent_id', '=', 'projects.id')
                ->distinct();

            // Map roles to intake columns (intentionally excluding project manager here)
            $roleColumns = [
                // 'project manager' => 'pi.property_manager_id', // handled via project_user pivot
                'abstractor'  => 'pi.abstractor_id',
                'reviewer'    => 'pi.reviewer_id',
                'sense check' => 'pi.sense_check_ddr_id',
                'customer'    => 'pi.property_manager_id',
            ];

            // Collect columns for roles the user actually has
            $columnsToFilter = [];
            foreach ($roleColumns as $role => $col) {
                if ($user->hasRole($role)) {
                    $columnsToFilter[] = $col;
                }
            }

            // Apply a grouped OR across matching role columns,
            // plus OR an exists() on project_user when user is a project manager.
            $shouldApplyAnyFilter = !empty($columnsToFilter) || $user->hasRole('project manager');

            if ($shouldApplyAnyFilter) {
                $subs->where(function ($q) use ($columnsToFilter, $user) {
                    // Intake-based role matches
                    foreach ($columnsToFilter as $col) {
                        $q->orWhere($col, $user->id);
                    }

                    // Project manager role via project_user pivot
                    if ($user->hasRole('project manager')) {
                        $q->orWhereExists(function ($sub) use ($user) {
                            $sub->selectRaw(1)
                                ->from('project_user as pu')
                                ->whereColumn('pu.project_id', 'projects.id')
                                ->where('pu.user_id', $user->id);
                        });
                    }
                });
            }
        }

        $subs = $subs->get();

        $html = view('content.project.subprojects_table', compact('subs'))->render();

        return response()->json(['html' => $html]);
    }


    public function show() {}

    public function fileView(Request $request, string $encryptedParentId)
    {
        // dd(phpinfo());
        $parentId = null;
        if ($encryptedParentId) {
            try {
                $parentId = Crypt::decryptString($encryptedParentId);
            } catch (\Throwable $e) {
                $parentId = null;
            }
        }

        $masters  = Helpers::getProjectMasterData();
        $rows     = collect();

        if ($parentId) {
            $parent = Project::query()
                ->select('id', 'department_id', 'industry_vertical_id')
                ->whereKey((int) $parentId)
                ->first();

            $serviceOfferings = ServiceOffering::query()
                ->where('department_id', (int) $parent->department_id)
                ->orderBy('name')
                ->get(['id', 'name']);
            $masters['work_types'] = $serviceOfferings;

            //  dd($serviceOfferings);
            $rowsQ = ProjectIntake::query()
                ->with(['queries' => function ($q) {
                    $q->orderBy('id');
                }])
                ->withCount([
                    'queries as open_queries_count' => function ($q) {
                        // Use the correct column name in your table:
                        // if your column is `resolved_date`, change the line below accordingly.
                        $q->whereNull('query_resolved_date');
                    },
                ])
                ->where('parent_id', $parentId)
                ->orderBy('id');

            $user = auth()->user();
            if ($user && ! ($user->hasRole('super admin') || $user->hasRole('project manager'))) {
                if ($user->hasRole('abstractor')) {
                    $rowsQ->where('abstractor_id', $user->id);
                } elseif ($user->hasRole('reviewer')) {
                    $rowsQ->where('reviewer_id', $user->id);
                } elseif ($user->hasRole('customer')) {
                    $rowsQ->where('property_manager_id', $user->id);
                } elseif ($user->hasRole('sense check')) {
                    $rowsQ->where('sense_check_ddr_id', $user->id);
                }
            }

            // 🔎 apply intake filters here
            $this->applyIntakeFiltersOnIntakes($rowsQ, $request);

            $rows = $rowsQ->get();

            // Calculate readonly states for each row based on cascading date logic
            $rows = $rows->map(function ($row) {
                $row->dateReadonlyFlags = $this->calculateDateReadonlyFlags($row);
                return $row;
            });
        }

        $project_info     = Project::query()
            ->leftJoin('companies as c', 'c.id', '=', 'projects.customer_id')
            ->select('projects.project_name', 'c.name as customer_name')
            ->where('projects.id', $parentId)
            ->first();

        $projectManagers  = Helpers::customer_point_of_contact($parentId);
        $project_manager_id = Helpers::project_managers($parentId);
        $pro_Id           = $project_manager_id->pluck('id')->toArray();
        $abstractor       = Helpers::getProjectManagersByIdsAndRole($pro_Id, 'abstractor');
        $reviewer         = Helpers::getProjectManagersByIdsAndRole($pro_Id, 'reviewer');
        $sense_check      = Helpers::getProjectManagersByIdsAndRole($pro_Id, 'sense check');

        return view('content.project.table', [
            'title'            => $rows->isEmpty() ? 'Create Intake' : 'Edit Intake',
            'type'             => $rows->isEmpty() ? 'create' : 'edit',
            'parentId'         => $parentId,
            'rows'             => $rows,
            'masters'          => $masters,
            'project_managers' => $projectManagers,
            'abstractor_users' => $abstractor,
            'project_info'     => $project_info,
            'reviewer'         => $reviewer,
            'sense_check'      => $sense_check,
            'action'           => route('project.file.store'),
        ]);
    }

    /**
     * Calculate readonly flags for date fields based on cascading date logic
     * 
     * Logic:
     * 1. End dates are readonly until their corresponding start date is set:
     *    - Abstract Completion Date is readonly if Abstraction Start Date is not set
     *    - Review Completion Date is readonly if Review Start Date is not set
     *    - Sense Check Completion Date is readonly if Sense Check Start Date is not set
     * 2. Reviewer Start Date and Reviewer End Date are readonly if Abstractor End Date (abstract_completion_date) is not set
     * 3. Sense Check Start Date and Sense Check End Date are readonly if Reviewer End Date (review_completion_date) is not set
     */
    private function calculateDateReadonlyFlags($row): array
    {
        $flags = [
            'abstract_completion_date' => false,
            'review_start_date' => false,
            'review_completion_date' => false,
            'sense_check_start_date' => false,
            'sense_check_completion_date' => false,
        ];

        // Helper to check if date is set
        $hasDate = function ($date) {
            return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
        };

        // 1. End dates are readonly until their start date is set
        $hasAbstractionStart = $hasDate($row->abstraction_start_date);
        $flags['abstract_completion_date'] = !$hasAbstractionStart;

        $hasReviewStart = $hasDate($row->review_start_date);
        $flags['review_completion_date'] = !$hasReviewStart;

        $hasSenseCheckStart = $hasDate($row->sense_check_start_date);
        $flags['sense_check_completion_date'] = !$hasSenseCheckStart;

        // 2. Reviewer Start Date and Reviewer End Date are readonly if Abstractor End Date is not set
        $hasAbstractCompletion = $hasDate($row->abstract_completion_date);
        $flags['review_start_date'] = !$hasAbstractCompletion;
        // Review completion date should be readonly if either review start is not set OR abstract completion is not set
        if (!$hasAbstractCompletion) {
            $flags['review_completion_date'] = true;
        }

        // 3. Sense Check Start Date and Sense Check End Date are readonly if Reviewer End Date is not set
        $hasReviewCompletion = $hasDate($row->review_completion_date);
        $flags['sense_check_start_date'] = !$hasReviewCompletion;
        // Sense check completion date should be readonly if either sense check start is not set OR review completion is not set
        if (!$hasReviewCompletion) {
            $flags['sense_check_completion_date'] = true;
        }

        return $flags;
    }

    // public function store2(Request $request)
    // {
    //     $parentId = $request->input('parent_id');
    //     $all      = $request->all();

    //     $count = count($request->input('property_manager_id', []));
    //     $rows  = [];

    //     // 1) All form field names we understand
    //     $formFields = [
    //         'property_manager_id',
    //         'request_received_date',
    //         'delivered_date',
    //         'priority_id',
    //         'status_master',
    //         'property_id',
    //         'property_name',
    //         'tenant_name',
    //         'tenant_or_lease_id',
    //         'premises_address',
    //         'no_of_documents',
    //         'pdf_names',
    //         'sb_queries',
    //         'type_of_queries',
    //         'client_response',
    //         'query_status',
    //         'query_raised_date',
    //         'query_resolved_date',
    //         'abstractor',
    //         'abstraction_start_date',
    //         'abstract_completion_date',
    //         'reviewer',
    //         // NEW
    //         'review_start_date',
    //         'review_completion_date',
    //         'sense_check_ddr',
    //         // NEW
    //         'sense_check_start_date',
    //         'sense_check_completion_date',
    //         'proposed_delivery_date',
    //         'actual_delivered_date',
    //         'feedback_received_date',
    //         'feedback_completion_date',
    //         'billing_month',
    //         'non_english_pages',
    //         'invoice_format',
    //         'fb_date_received',
    //         'fb_customer_name',
    //         'fb_category_id',
    //         'fb_customer_comments',
    //         'fb_sb_response',
    //         'fb_feedback_completion_date',
    //         'cost_usd',
    //         'type_of_lease',
    //         'type_of_work',
    //         'language',
    //         'suite_id'
    //     ];

    //     // 2) Map input name -> DB column for UPDATE clause
    //     $inputToColumn = [
    //         'status_master'      => 'status_master_id',
    //         'query_status'       => 'query_status_id',
    //         'abstractor'         => 'abstractor_id',
    //         'reviewer'           => 'reviewer_id',
    //         'sense_check_ddr'    => 'sense_check_ddr_id',
    //         'invoice_format'     => 'invoice_format_id',
    //         'type_of_lease'      => 'type_of_lease_id',
    //         'type_of_work'       => 'type_of_work_id',
    //         'language'           => 'language_code',

    //         // 1:1 fields
    //         'property_manager_id'         => 'property_manager_id',
    //         'request_received_date'       => 'request_received_date',
    //         'delivered_date'              => 'delivered_date',
    //         'priority_id'                 => 'priority_id',
    //         'property_id'                 => 'property_id',
    //         'property_name'               => 'property_name',
    //         'tenant_name'                 => 'tenant_name',
    //         'tenant_or_lease_id'          => 'tenant_or_lease_id',
    //         'premises_address'            => 'premises_address',
    //         'no_of_documents'             => 'no_of_documents',
    //         'pdf_names'                   => 'pdf_names',
    //         'sb_queries'                  => 'sb_queries',
    //         'type_of_queries'             => 'type_of_queries',
    //         'client_response'             => 'client_response',
    //         'query_raised_date'           => 'query_raised_date',
    //         'query_resolved_date'         => 'query_resolved_date',
    //         'abstraction_start_date'      => 'abstraction_start_date',
    //         'abstract_completion_date'    => 'abstract_completion_date',
    //         // NEW
    //         'review_start_date'           => 'review_start_date',
    //         'review_completion_date'      => 'review_completion_date',
    //         // NEW
    //         'sense_check_start_date'      => 'sense_check_start_date',
    //         'sense_check_completion_date' => 'sense_check_completion_date',
    //         'proposed_delivery_date'      => 'proposed_delivery_date',
    //         'actual_delivered_date'       => 'actual_delivered_date',
    //         'feedback_received_date'      => 'feedback_received_date',
    //         'feedback_completion_date'    => 'feedback_completion_date',
    //         'billing_month'               => 'billing_month',
    //         'non_english_pages'           => 'non_english_pages',
    //         'fb_date_received'            => 'fb_date_received',
    //         'fb_customer_name'            => 'fb_customer_name',
    //         'fb_category_id'              => 'fb_category_id',
    //         'fb_customer_comments'        => 'fb_customer_comments',
    //         'fb_sb_response'              => 'fb_sb_response',
    //         'fb_feedback_completion_date' => 'fb_feedback_completion_date',
    //         'cost_usd'                    => 'cost_usd',
    //         'suite_id'                    => 'suite_id',
    //     ];

    //     // 3) Build row payloads
    //     for ($i = 0; $i < $count; $i++) {
    //         $rowAllValues = array_map(fn($f) => data_get($all, "{$f}.{$i}"), $formFields);
    //         $intakeId = data_get($all, "intake_id.{$i}");

    //         $hasAny = collect($rowAllValues)->filter(function ($v) {
    //             if (is_string($v)) return trim($v) !== '';
    //             return !is_null($v);
    //         })->isNotEmpty();

    //         if (!$hasAny && !$intakeId) continue;

    //         $rows[] = [
    //             'parent_id'                     => $parentId,
    //             'property_manager_id'           => data_get($all, "property_manager_id.{$i}"),
    //             'request_received_date'         => $this->dmyToMdY(data_get($all, "request_received_date.{$i}")),
    //             'delivered_date'                => $this->dmyToMdY(data_get($all, "delivered_date.{$i}")),
    //             'priority_id'                   => data_get($all, "priority_id.{$i}"),
    //             'status_master_id'              => data_get($all, "status_master.{$i}"),
    //             'property_id'                   => data_get($all, "property_id.{$i}"),
    //             'property_name'                 => data_get($all, "property_name.{$i}"),
    //             'tenant_name'                   => data_get($all, "tenant_name.{$i}"),
    //             'tenant_or_lease_id'            => data_get($all, "tenant_or_lease_id.{$i}"),
    //             'premises_address'              => data_get($all, "premises_address.{$i}"),
    //             'no_of_documents'               => (int) (data_get($all, "no_of_documents.{$i}", 0) ?? 0),
    //             'pdf_names'                     => data_get($all, "pdf_names.{$i}"),
    //             'sb_queries'                    => data_get($all, "sb_queries.{$i}"),
    //             'type_of_queries'               => data_get($all, "type_of_queries.{$i}"),
    //             'client_response'               => data_get($all, "client_response.{$i}"),
    //             'query_status_id'               => data_get($all, "query_status.{$i}"),
    //             'query_raised_date'             => $this->dmyToMdY(data_get($all, "query_raised_date.{$i}")),
    //             'query_resolved_date'           => $this->dmyToMdY(data_get($all, "query_resolved_date.{$i}")),
    //             'abstractor_id'                 => data_get($all, "abstractor.{$i}"),
    //             'abstraction_start_date'        => $this->dmyToMdY(data_get($all, "abstraction_start_date.{$i}")),
    //             'abstract_completion_date'      => $this->dmyToMdY(data_get($all, "abstract_completion_date.{$i}")),
    //             'reviewer_id'                   => data_get($all, "reviewer.{$i}"),
    //             // NEW
    //             'review_start_date'             => $this->dmyToMdY(data_get($all, "review_start_date.{$i}")),
    //             'review_completion_date'        => $this->dmyToMdY(data_get($all, "review_completion_date.{$i}")),
    //             'sense_check_ddr_id'            => data_get($all, "sense_check_ddr.{$i}"),
    //             // NEW
    //             'sense_check_start_date'        => $this->dmyToMdY(data_get($all, "sense_check_start_date.{$i}")),
    //             'sense_check_completion_date'   => $this->dmyToMdY(data_get($all, "sense_check_completion_date.{$i}")),
    //             'proposed_delivery_date'        => $this->dmyToMdY(data_get($all, "proposed_delivery_date.{$i}")),
    //             'actual_delivered_date'         => $this->dmyToMdY(data_get($all, "actual_delivered_date.{$i}")),
    //             'feedback_received_date'        => $this->dmyToMdY(data_get($all, "feedback_received_date.{$i}")),
    //             'feedback_completion_date'      => $this->dmyToMdY(data_get($all, "feedback_completion_date.{$i}")),
    //             'billing_month'                 => data_get($all, "billing_month.{$i}"),
    //             'non_english_pages'             => (int) (data_get($all, "non_english_pages.{$i}", 0) ?? 0),
    //             'invoice_format_id'             => data_get($all, "invoice_format.{$i}"),
    //             'fb_date_received'              => $this->dmyToMdY(data_get($all, "fb_date_received.{$i}")),
    //             'fb_customer_name'              => data_get($all, "fb_customer_name.{$i}"),
    //             'fb_category_id'                => data_get($all, "fb_category_id.{$i}"),
    //             'fb_customer_comments'          => data_get($all, "fb_customer_comments.{$i}"),
    //             'fb_sb_response'                => data_get($all, "fb_sb_response.{$i}"),
    //             'fb_feedback_completion_date'   => $this->dmyToMdY(data_get($all, "fb_feedback_completion_date.{$i}")),
    //             'cost_usd'                      => (float) (data_get($all, "cost_usd.{$i}", 0) ?? 0),
    //             'type_of_lease_id'              => data_get($all, "type_of_lease.{$i}"),
    //             'type_of_work_id'               => data_get($all, "type_of_work.{$i}"),
    //             'language_code'                 => data_get($all, "language.{$i}"),
    //             'suite_id'                      => data_get($all, "suite_id.{$i}"),
    //             'id'                            => $intakeId ? (int) $intakeId : null,
    //         ];
    //     }

    //     $now = now();

    //     // 4) Normalize rows for insert (consistent columns)
    //     $normalized = [];
    //     if (!empty($rows)) {
    //         $allKeys = [];
    //         foreach ($rows as $r) $allKeys = array_unique(array_merge($allKeys, array_keys($r)));
    //         foreach (['id', 'created_at', 'updated_at'] as $k) {
    //             if (!in_array($k, $allKeys, true)) $allKeys[] = $k;
    //         }
    //         $normalized = array_map(function ($r) use ($allKeys, $now) {
    //             foreach ($allKeys as $k) {
    //                 if (!array_key_exists($k, $r)) $r[$k] = null;
    //             }
    //             $r['created_at'] = $r['created_at'] ?? $now;
    //             $r['updated_at'] = $now;
    //             return $r;
    //         }, $rows);
    //     }

    //     // 5) Figure out which inputs actually came from the browser
    //     $presentInputs = array_values(array_intersect($formFields, array_keys($all)));
    //     $presentColumns = array_values(array_unique(array_map(function ($in) use ($inputToColumn) {
    //         return $inputToColumn[$in] ?? $in;
    //     }, $presentInputs)));

    //     // 6) Role-based narrowing (extra safety)
    //     $user = auth()->user();
    //     $hasElevated = $user && $user->hasAnyRole(['super admin', 'admin', 'project manager']);

    //     if (!$hasElevated) {
    //         $isAbs   = $user && $user->hasAnyRole(['abstractor', 'Abstractor']);
    //         $isRev   = $user && $user->hasAnyRole(['reviewer', 'Reviewer']);
    //         $isSense = $user && $user->hasAnyRole(['sense check', 'sense_check', 'Sense Check / DDR']);

    //         $roleAllowed = [];
    //         if ($isAbs) {
    //             $roleAllowed = ['abstractor_id', 'abstraction_start_date', 'abstract_completion_date'];
    //         }
    //         if ($isRev) {
    //             // allow start date same as end date permissions
    //             $roleAllowed = array_merge($roleAllowed, ['reviewer_id', 'review_start_date', 'review_completion_date']);
    //         }
    //         if ($isSense) {
    //             // allow start date same as end date permissions
    //             $roleAllowed = array_merge($roleAllowed, [
    //                 'sense_check_ddr_id',
    //                 'sense_check_start_date',
    //                 'sense_check_completion_date',
    //                 'actual_delivered_date',
    //                 'feedback_received_date',
    //                 'feedback_completion_date'
    //             ]);
    //         }

    //         if (!empty($roleAllowed)) {
    //             $presentColumns = array_values(array_intersect($presentColumns, $roleAllowed));
    //         }
    //     }

    //     // Always allow timestamp if we will upsert
    //     if (!empty($normalized) && !in_array('updated_at', $presentColumns, true)) {
    //         $presentColumns[] = 'updated_at';
    //     }

    //     $canPrune = $user && ($user->hasRole('super admin') || $user->hasRole('project manager'));

    //     // Determine if filters were active on the page when user clicked Update.
    //     // Prefer the explicit flag from the form; fall back to query check just in case.
    //     $hasActiveFilters = $request->boolean('has_active_filters', null);
    //     if ($hasActiveFilters === null) {
    //         $filters = $request->query('filter', []);
    //         $hasActiveFilters = collect($filters)
    //             ->flatten()
    //             ->filter(fn($v) => !is_null($v) && $v !== '')
    //             ->isNotEmpty();
    //     }

    //     DB::transaction(function () use ($normalized, $parentId, $canPrune, $presentColumns, $hasActiveFilters) {
    //         $idsToKeep = array_values(array_filter(array_map(fn($r) => $r['id'] ?? null, $normalized)));

    //         //Prune only when NO filters are active
    //         if ($canPrune && $parentId !== null && !$hasActiveFilters) {
    //             ProjectIntake::where('parent_id', $parentId)
    //                 ->when(!empty($idsToKeep), fn($q) => $q->whereNotIn('id', $idsToKeep))
    //                 ->delete();
    //         }

    //         if (!empty($normalized)) {
    //             ProjectIntake::upsert($normalized, ['id'], $presentColumns);
    //         }
    //     });

    //     $existingById = [];
    //     $existingIds = array_values(array_filter(array_map(fn($r) => $r['id'] ?? null, $normalized)));
    //     if (!empty($existingIds)) {
    //         $existingById = ProjectIntake::whereIn('id', $existingIds)
    //             ->get(['id','reviewer_id','parent_id','property_name','tenant_name','tenant_or_lease_id','abstract_completion_date'])
    //             ->keyBy('id');
    //     }

    // // Preload project name
    // $parentId = $request->input('parent_id');
    // $projectName = DB::table('projects')->where('id', $parentId)->value('project_name') ?? '(Project)';

    // // Prepare notifications bucket: sense_check_user_id => [items...]
    // $abstractionCompleted = [];

    // DB::transaction(function () use ($normalized, $parentId, $canPrune, $presentColumns, $hasActiveFilters, $existingById, $projectName, &$abstractionCompleted) {
    //     $idsToKeep = array_values(array_filter(array_map(fn($r) => $r['id'] ?? null, $normalized)));

    //     if ($canPrune && $parentId !== null && !$hasActiveFilters) {
    //         ProjectIntake::where('parent_id', $parentId)
    //             ->when(!empty($idsToKeep), fn($q) => $q->whereNotIn('id', $idsToKeep))
    //             ->delete();
    //     }

    //     if (!empty($normalized)) {
    //         ProjectIntake::upsert($normalized, ['id'], $presentColumns);
    //     }

    //     // Re-fetch the just-updated/inserted rows that have a completion date now
    //     $affectedIds = array_values(array_filter(array_map(fn($r) => $r['id'] ?? null, $normalized)));
    //     $fresh = ProjectIntake::when(!empty($affectedIds), fn($q) => $q->whereIn('id', $affectedIds))
    //         ->orWhere(function($q) use ($normalized) {
    //             // include any newly inserted (id null earlier) with non-null completion date
    //             $withNoId = array_filter($normalized, fn($r) => empty($r['id']) && !empty($r['abstract_completion_date']));
    //             if (!empty($withNoId)) {
    //                 // best-effort: narrow by parent_id + property_name + tenant_or_lease_id
    //                 foreach ($withNoId as $r) {
    //                     $q->orWhere(function($q2) use ($r) {
    //                         $q2->where('parent_id', $r['parent_id'] ?? null)
    //                            ->where('property_name', $r['property_name'] ?? null)
    //                            ->where('tenant_or_lease_id', $r['tenant_or_lease_id'] ?? null);
    //                     });
    //                 }
    //             }
    //         })
    //         ->get([
    //             'id','parent_id','property_name','tenant_name','tenant_or_lease_id',
    //             'reviewer_id','abstract_completion_date'
    //         ]);

    //     foreach ($fresh as $row) {
    //         $prev = $existingById[$row->id] ?? null;
    //         $nowCompleted = !empty($row->abstract_completion_date);
    //         $wasCompleted = $prev?->abstract_completion_date ? true : false;

    //         // We only notify when it transitions from null -> non-null OR was new+has date
    //         if ($nowCompleted && !$wasCompleted) {
    //             $senseId = $row->reviewer_id;
    //             if (!$senseId) continue;

    //             $abstractionCompleted[$senseId]['items'][] = [
    //                 'project_name' => $projectName,
    //                 'tenant_id'    => $row->tenant_or_lease_id,
    //                 'property'     => $row->property_name,
    //                 'tenant'       => $row->tenant_name,
    //                 'completed_at' => $row->abstract_completion_date,
    //             ];
    //             $abstractionCompleted[$senseId]['count'] =
    //                 ($abstractionCompleted[$senseId]['count'] ?? 0) + 1;
    //         }
    //     }

    //     // Send mails only after COMMIT
    //     DB::afterCommit(function () use ($abstractionCompleted, $parentId) {
    //         $this->notifySenseAndPm($abstractionCompleted, $parentId);
    //     });
    // });

    //     return back()->with('success', 'Intake rows saved successfully.');
    // }

    public function store2(Request $request)
    {
        $parentId = $request->input('parent_id');
        $all = $request->all();

        // === 0) Date pairs definition ===
        $datePairs = [
            'request_received_date'     => 'delivered_date',
            'abstraction_start_date'    => 'abstract_completion_date',
            'review_start_date'         => 'review_completion_date',
            'sense_check_start_date'    => 'sense_check_completion_date',
            'fb_date_received'          => 'fb_feedback_completion_date',
        ];

        $rules    = [];
        $messages = [];

        // Normalizer: from MM-DD-YYYY (or anything Carbon can parse) → Y-m-d
        $normDate = function ($v) {
            if ($v === null || $v === '') return null;
            $s = trim((string)$v);
            if ($s === '' || $s === '0000-00-00' || $s === '0000-00-00 00:00:00') return null;

            // first try explicit MM-DD-YYYY
            try {
                return \Carbon\Carbon::createFromFormat('m-d-Y', $s)->format('Y-m-d');
            } catch (\Throwable $e) {}

            // fallback
            try {
                return \Carbon\Carbon::parse($s)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        };

        // 1) Build a *normalized* copy of input that we will validate AND send back
        $input = $all;

        foreach ($datePairs as $startField => $endField) {
            foreach ([$startField, $endField] as $field) {
                if (!isset($input[$field]) || !is_array($input[$field])) {
                    continue;
                }

                foreach ($input[$field] as $idx => $val) {
                    $n = $normDate($val);
                    if ($n) {
                        // store in normalized Y-m-d
                        $input[$field][$idx] = $n;
                    }
                }
            }
        }

        // 2) Figure out how many rows
        $counts = [];
        foreach (array_keys($datePairs) as $f) {
            $v = data_get($input, $f, []);
            $counts[] = is_array($v) ? count($v) : 0;
        }
        $rowCount = max($counts ?: [0]);

        // 3) Build rules using normalized $input (dates are Y-m-d now)
        for ($i = 0; $i < $rowCount; $i++) {
            foreach ($datePairs as $startField => $endField) {
                $startKey = "{$startField}.{$i}";
                $endKey   = "{$endField}.{$i}";

                $startVal = data_get($input, $startKey);
                $endVal   = data_get($input, $endKey);

                // start: just a nullable date (Y-m-d)
                $rules[$startKey] = ['nullable', 'date'];

                // Only validate end if provided (not required)
                if (!empty($endVal) && trim((string)$endVal) !== '') {
                    $rules[$endKey] = [
                        'nullable',
                        'date',
                        function ($attribute, $value, $fail) use ($startField, $i, $input) {
                            $startDate = data_get($input, "{$startField}.{$i}");
                            $endDate   = $value;

                            if (!$startDate || !$endDate) {
                                return;
                            }

                            if ($endDate < $startDate) {
                                $startLabel = str_replace('_', ' ', ucwords($startField, '_'));
                                $fail("The end date must be on or after the {$startLabel}.");
                            }
                        }
                    ];
                }
            }

            // Cross-stage validations
            // 1) Review start date should be >= Abstractor completion date
            $reviewStartKey = "review_start_date.{$i}";
            $abstractCompletionKey = "abstract_completion_date.{$i}";
            $reviewStartVal = data_get($input, $reviewStartKey);
            $abstractCompletionVal = data_get($input, $abstractCompletionKey);

            if (!empty($reviewStartVal) && trim((string)$reviewStartVal) !== '' && 
                !empty($abstractCompletionVal) && trim((string)$abstractCompletionVal) !== '') {
                // Ensure the key exists in rules array
                if (!isset($rules[$reviewStartKey])) {
                    $rules[$reviewStartKey] = ['nullable', 'date'];
                }
                $rules[$reviewStartKey][] = function ($attribute, $value, $fail) use ($abstractCompletionVal) {
                    if ($value < $abstractCompletionVal) {
                        $fail("The review start date must be on or after the abstract completion date.");
                    }
                };
            }

            // 2) Sense check start date should be >= Review completion date
            $senseStartKey = "sense_check_start_date.{$i}";
            $reviewCompletionKey = "review_completion_date.{$i}";
            $senseStartVal = data_get($input, $senseStartKey);
            $reviewCompletionVal = data_get($input, $reviewCompletionKey);

            if (!empty($senseStartVal) && trim((string)$senseStartVal) !== '' && 
                !empty($reviewCompletionVal) && trim((string)$reviewCompletionVal) !== '') {
                // Ensure the key exists in rules array
                if (!isset($rules[$senseStartKey])) {
                    $rules[$senseStartKey] = ['nullable', 'date'];
                }
                $rules[$senseStartKey][] = function ($attribute, $value, $fail) use ($reviewCompletionVal) {
                    if ($value < $reviewCompletionVal) {
                        $fail("The sense check start date must be on or after the review completion date.");
                    }
                };
            }
        }

        // 4) Validate the *normalized* data
        if (!empty($rules)) {
            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                // IMPORTANT: send normalized Y-m-d back to the view
                return back()
                    ->withErrors($validator)
                    ->withInput($input);
            }
        }
        // === 1) Field map ===
        $formFields = [
            'property_manager_id',
            'request_received_date',
            'delivered_date',
            'priority_id',
            'status_master',
            'property_id',
            'property_name',
            'tenant_name',
            'tenant_or_lease_id',
            'premises_address',
            'no_of_documents',
            'pdf_names',
            'sb_queries',
            'type_of_queries',
            'client_response',
            'query_status',
            'query_raised_date',
            'query_resolved_date',
            'abstractor',
            'abstraction_start_date',
            'abstract_completion_date',
            'reviewer',
            'review_start_date',
            'review_completion_date',
            'sense_check_ddr',
            'sense_check_start_date',
            'sense_check_completion_date',
            'proposed_delivery_date',
            'actual_delivered_date',
            'feedback_received_date',
            'feedback_completion_date',
            'billing_month',
            'non_english_pages',
            'invoice_format',
            'fb_date_received',
            'fb_customer_name',
            'fb_category_id',
            'fb_customer_comments',
            'fb_sb_response',
            'fb_feedback_completion_date',
            'cost_usd',
            'type_of_lease',
            'type_of_work',
            'language',
            'suite_id',
        ];

        $inputToColumn = [
            'status_master'      => 'status_master_id',
            'query_status'       => 'query_status_id',
            'abstractor'         => 'abstractor_id',
            'reviewer'           => 'reviewer_id',
            'sense_check_ddr'    => 'sense_check_ddr_id',
            'invoice_format'     => 'invoice_format_id',
            'type_of_lease'      => 'type_of_lease_id',
            'type_of_work'       => 'type_of_work_id',
            'language'           => 'language_code',

            // passthroughs
            'property_manager_id'         => 'property_manager_id',
            'request_received_date'       => 'request_received_date',
            'delivered_date'              => 'delivered_date',
            'priority_id'                 => 'priority_id',
            'property_id'                 => 'property_id',
            'property_name'               => 'property_name',
            'tenant_name'                 => 'tenant_name',
            'tenant_or_lease_id'          => 'tenant_or_lease_id',
            'premises_address'            => 'premises_address',
            'no_of_documents'             => 'no_of_documents',
            'pdf_names'                   => 'pdf_names',
            'sb_queries'                  => 'sb_queries',
            'type_of_queries'             => 'type_of_queries',
            'client_response'             => 'client_response',
            'query_raised_date'           => 'query_raised_date',
            'query_resolved_date'         => 'query_resolved_date',
            'abstraction_start_date'      => 'abstraction_start_date',
            'abstract_completion_date'    => 'abstract_completion_date',
            'review_start_date'           => 'review_start_date',
            'review_completion_date'      => 'review_completion_date',
            'sense_check_start_date'      => 'sense_check_start_date',
            'sense_check_completion_date' => 'sense_check_completion_date',
            'proposed_delivery_date'      => 'proposed_delivery_date',
            'actual_delivered_date'       => 'actual_delivered_date',
            'feedback_received_date'      => 'feedback_received_date',
            'feedback_completion_date'    => 'feedback_completion_date',
            'billing_month'               => 'billing_month',
            'non_english_pages'           => 'non_english_pages',
            'fb_date_received'            => 'fb_date_received',
            'fb_customer_name'            => 'fb_customer_name',
            'fb_category_id'              => 'fb_category_id',
            'fb_customer_comments'        => 'fb_customer_comments',
            'fb_sb_response'              => 'fb_sb_response',
            'fb_feedback_completion_date' => 'fb_feedback_completion_date',
            'cost_usd'                    => 'cost_usd',
            'suite_id'                    => 'suite_id',
        ];

        // === 2) Helpers ===
        $normDate = function ($v) {
            if ($v === null) return null;
            $s = trim((string)$v);
            if ($s === '' || $s === '0000-00-00' || $s === '0000-00-00 00:00:00') return null;
            $fmts = ['m-d-Y'];
            foreach ($fmts as $f) {
                try {
                    return \Carbon\Carbon::createFromFormat($f, $s)->format('Y-m-d');
                } catch (\Throwable $e) {
                }
            }
            try {
                return \Carbon\Carbon::parse($s)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        };

        // === 3) Determine row count robustly ===
        $counts = [];
        foreach ($formFields as $f) {
            $v = data_get($all, $f, []);
            $counts[] = is_array($v) ? count($v) : 0;
        }
        $counts[] = is_array(data_get($all, 'intake_id', [])) ? count($all['intake_id']) : 0;
        $count = max($counts ?: [0]);

        // === 4) Build rows + per-row submission flags ===
        $rows = [];
        $submittedById = []; // [id] => ['abs_submitted'=>bool,'abs_new'=>Y-m-d,'rev_submitted'=>bool,'rev_new'=>Y-m-d,'sense_submitted'=>bool,'sense_new'=>Y-m-d]

        for ($i = 0; $i < $count; $i++) {
            $intakeId = data_get($all, "intake_id.{$i}");
            $rowAllValues = array_map(fn($f) => data_get($all, "{$f}.{$i}"), $formFields);
            $hasAny = collect($rowAllValues)->filter(fn($v) => is_string($v) ? trim($v) !== '' : !is_null($v))->isNotEmpty();
            if (!$hasAny && !$intakeId) continue;

            // consider "submitted" only if the field index exists AND has non-empty value
            $wasSubmitted = function (string $key) use ($all, $i) {
                return array_key_exists($key, $all)
                    && is_array($all[$key])
                    && array_key_exists($i, $all[$key])
                    && trim((string)$all[$key][$i]) !== '';
            };

            $absSubmitted   = $wasSubmitted('abstract_completion_date');
            $revSubmitted   = $wasSubmitted('review_completion_date');
            $senseSubmitted = $wasSubmitted('sense_check_completion_date');

            $absNew   = $absSubmitted   ? $normDate(data_get($all, "abstract_completion_date.{$i}"))      : null;
            $revNew   = $revSubmitted   ? $normDate(data_get($all, "review_completion_date.{$i}"))        : null;
            $senseNew = $senseSubmitted ? $normDate(data_get($all, "sense_check_completion_date.{$i}"))   : null;

            if ($intakeId) {
                $submittedById[(int)$intakeId] = [
                    'abs_submitted'   => $absSubmitted,
                    'abs_new'         => $absNew,
                    'rev_submitted'   => $revSubmitted,
                    'rev_new'         => $revNew,
                    'sense_submitted' => $senseSubmitted,
                    'sense_new'       => $senseNew,
                ];
            }

            $rows[] = [
                'parent_id'                     => $parentId,
                'property_manager_id'           => data_get($all, "property_manager_id.{$i}"),
                'request_received_date'         => $normDate(data_get($all, "request_received_date.{$i}")),
                'delivered_date'                => $normDate(data_get($all, "delivered_date.{$i}")),
                'priority_id'                   => data_get($all, "priority_id.{$i}"),
                'status_master_id'              => data_get($all, "status_master.{$i}"),
                'property_id'                   => data_get($all, "property_id.{$i}"),
                'property_name'                 => data_get($all, "property_name.{$i}"),
                'tenant_name'                   => data_get($all, "tenant_name.{$i}"),
                'tenant_or_lease_id'            => data_get($all, "tenant_or_lease_id.{$i}"),
                'premises_address'              => data_get($all, "premises_address.{$i}"),
                'no_of_documents'               => (int) (data_get($all, "no_of_documents.{$i}", 0) ?? 0),
                'pdf_names'                     => data_get($all, "pdf_names.{$i}"),
                'sb_queries'                    => data_get($all, "sb_queries.{$i}"),
                'type_of_queries'               => data_get($all, "type_of_queries.{$i}"),
                'client_response'               => data_get($all, "client_response.{$i}"),
                'query_status_id'               => data_get($all, "query_status.{$i}"),
                'query_raised_date'             => $normDate(data_get($all, "query_raised_date.{$i}")),
                'query_resolved_date'           => $normDate(data_get($all, "query_resolved_date.{$i}")),
                'abstractor_id'                 => data_get($all, "abstractor.{$i}"),
                'abstraction_start_date'        => $normDate(data_get($all, "abstraction_start_date.{$i}")),
                'abstract_completion_date'      => $absNew,     // if not submitted, we'll restore old
                'reviewer_id'                   => data_get($all, "reviewer.{$i}"),
                'review_start_date'             => $normDate(data_get($all, "review_start_date.{$i}")),
                'review_completion_date'        => $revNew,     // if not submitted, we'll restore old
                'sense_check_ddr_id'            => data_get($all, "sense_check_ddr.{$i}"),
                'sense_check_start_date'        => $normDate(data_get($all, "sense_check_start_date.{$i}")),
                'sense_check_completion_date'   => $senseNew,   // if not submitted, we'll restore old
                'proposed_delivery_date'        => $normDate(data_get($all, "proposed_delivery_date.{$i}")),
                'actual_delivered_date'         => $normDate(data_get($all, "actual_delivered_date.{$i}")),
                'feedback_received_date'        => $normDate(data_get($all, "feedback_received_date.{$i}")),
                'feedback_completion_date'      => $normDate(data_get($all, "feedback_completion_date.{$i}")),
                'billing_month'                 => data_get($all, "billing_month.{$i}"),
                'non_english_pages'             => (int) (data_get($all, "non_english_pages.{$i}", 0) ?? 0),
                'invoice_format_id'             => data_get($all, "invoice_format.{$i}"),
                'fb_date_received'              => $normDate(data_get($all, "fb_date_received.{$i}")),
                'fb_customer_name'              => data_get($all, "fb_customer_name.{$i}"),
                'fb_category_id'                => data_get($all, "fb_category_id.{$i}"),
                'fb_customer_comments'          => data_get($all, "fb_customer_comments.{$i}"),
                'fb_sb_response'                => data_get($all, "fb_sb_response.{$i}"),
                'fb_feedback_completion_date'   => $normDate(data_get($all, "fb_feedback_completion_date.{$i}")),
                'cost_usd'                      => (float) (data_get($all, "cost_usd.{$i}", 0) ?? 0),
                'type_of_lease_id'              => data_get($all, "type_of_lease.{$i}"),
                'type_of_work_id'               => data_get($all, "type_of_work.{$i}"),
                'language_code'                 => data_get($all, "language.{$i}"),
                'suite_id'                      => data_get($all, "suite_id.{$i}"),
                'id'                            => $intakeId ? (int) $intakeId : null,
                'created_at'                    => now(),
                'updated_at'                    => now(),
            ];
        }

        // === 5) Which columns are posted (for upsert list) ===
        $presentInputs  = array_values(array_intersect($formFields, array_keys($all)));
        $presentColumns = array_values(array_unique(array_map(fn($in) => $inputToColumn[$in] ?? $in, $presentInputs)));
        if (!in_array('updated_at', $presentColumns, true)) $presentColumns[] = 'updated_at';

        // === 6) Role-based narrowing ===
        $user = auth()->user();
        $hasElevated = $user && $user->hasAnyRole(['super admin', 'admin', 'project manager']);
        if (!$hasElevated) {
            $isAbs   = $user && $user->hasAnyRole(['abstractor', 'Abstractor']);
            $isRev   = $user && $user->hasAnyRole(['reviewer', 'Reviewer']);
            $isSense = $user && $user->hasAnyRole(['sense check', 'sense_check', 'Sense Check / DDR']);
            $roleAllowed = [];
            if ($isAbs)  $roleAllowed = ['abstractor_id', 'abstraction_start_date', 'abstract_completion_date'];
            if ($isRev)  $roleAllowed = array_merge($roleAllowed, ['reviewer_id', 'review_start_date', 'review_completion_date']);
            if ($isSense) $roleAllowed = array_merge($roleAllowed, ['sense_check_ddr_id', 'sense_check_start_date', 'sense_check_completion_date', 'actual_delivered_date', 'feedback_received_date', 'feedback_completion_date']);
            if (!empty($roleAllowed)) $presentColumns = array_values(array_intersect($presentColumns, $roleAllowed));
        }

        // === 7) Filters flag ===
        $hasActiveFilters = $request->boolean('has_active_filters', null);
        if ($hasActiveFilters === null) {
            $filters = $request->query('filter', []);
            $hasActiveFilters = collect($filters)->flatten()->filter(fn($v) => !is_null($v) && $v !== '')->isNotEmpty();
        }

        // === 8) Load existing state (+ notified flags) ===
        $existingById = [];
        $existingIds  = array_values(array_filter(array_map(fn($r) => $r['id'] ?? null, $rows)));
        if (!empty($existingIds)) {
            $existingById = \App\Models\ProjectIntake::whereIn('id', $existingIds)->get([
                'id',
                'parent_id',
                'property_name',
                'tenant_name',
                'tenant_or_lease_id',
                'abstract_completion_date',
                'review_completion_date',
                'sense_check_completion_date',
                'abstract_notified_on',
                'review_notified_on',
                'sense_notified_on',
                'reviewer_id',
                'sense_check_ddr_id',
            ])->keyBy('id');
        }

        // === 9) Protect non-submitted completion fields from being overwritten ===
        foreach ($rows as &$nr) {
            $id = $nr['id'] ?? null;
            if ($id && isset($existingById[$id])) {
                $flags = $submittedById[$id] ?? [];
                $prev  = $existingById[$id];

                if (!($flags['abs_submitted']   ?? false)) $nr['abstract_completion_date']    = $prev->abstract_completion_date;
                if (!($flags['rev_submitted']   ?? false)) $nr['review_completion_date']      = $prev->review_completion_date;
                if (!($flags['sense_submitted'] ?? false)) $nr['sense_check_completion_date'] = $prev->sense_check_completion_date;
            }
        }
        unset($nr);

        // === 10) Notification buckets + mark-to-update lists ===
        $notifyReviewer    = []; // reviewer_id => payload
        $notifySense       = []; // sense_id    => payload
        $notifyPmOnSense   = []; // PM summary for sense completion
        $markAbsNotified   = []; // [intake_id => Y-m-d]
        $markRevNotified   = [];
        $markSenseNotified = [];

        $projectName = DB::table('projects')->where('id', $parentId)->value('project_name') ?? '(Project)';

        // === 11) Transaction: prune, detect, upsert, then mail after commit ===
        DB::transaction(function () use (
            $rows,
            $parentId,
            $existingById,
            $submittedById,
            $projectName,
            &$notifyReviewer,
            &$notifySense,
            &$notifyPmOnSense,
            &$markAbsNotified,
            &$markRevNotified,
            &$markSenseNotified,
            $presentColumns,
            $hasActiveFilters
        ) {
            // Optional prune
            $user = auth()->user();
            $canPrune = $user && ($user->hasRole('super admin') || $user->hasRole('project manager'));
            $idsToKeep = array_values(array_filter(array_map(fn($r) => $r['id'] ?? null, $rows)));
            if ($canPrune && $parentId !== null && !$hasActiveFilters) {
                \App\Models\ProjectIntake::where('parent_id', $parentId)
                    ->when(!empty($idsToKeep), fn($q) => $q->whereNotIn('id', $idsToKeep))
                    ->delete();
            }

            // TRIGGERS (only for submitted non-empty values & never re-send once *_notified_on set)
            foreach ($rows as $r) {
                $id = $r['id'] ?? null;
                if (!$id || !isset($existingById[$id])) continue;

                $prev  = $existingById[$id];
                $flags = $submittedById[$id] ?? [];

                $absSubmitted   = (bool)($flags['abs_submitted']   ?? false);
                $revSubmitted   = (bool)($flags['rev_submitted']   ?? false);
                $senseSubmitted = (bool)($flags['sense_submitted'] ?? false);

                $absNew   = $flags['abs_new']   ?? null; // Y-m-d
                $revNew   = $flags['rev_new']   ?? null; // Y-m-d
                $senseNew = $flags['sense_new'] ?? null; // Y-m-d

                $absAlreadyNotified   = !empty($prev->abstract_notified_on);
                $revAlreadyNotified   = !empty($prev->review_notified_on);
                $senseAlreadyNotified = !empty($prev->sense_notified_on);

                $reviewerId = $r['reviewer_id']        ?? $prev->reviewer_id;
                $senseId    = $r['sense_check_ddr_id'] ?? $prev->sense_check_ddr_id;

                // 1) Abstract -> Reviewer (once only)
                if ($absSubmitted && $absNew && !$absAlreadyNotified && $reviewerId) {
                    $notifyReviewer[$reviewerId]['count'] = ($notifyReviewer[$reviewerId]['count'] ?? 0) + 1;
                    $notifyReviewer[$reviewerId]['items'][] = [
                        'project_name' => $projectName,
                        'tenant_id'    => $r['tenant_or_lease_id'] ?? $prev->tenant_or_lease_id,
                        'property'     => $r['property_name']      ?? $prev->property_name,
                        'tenant'       => $r['tenant_name']        ?? $prev->tenant_name,
                        'completed_at' => $absNew,
                        'stage'        => 'Abstraction Completed',
                        'next_action'  => 'Review',
                    ];
                    $markAbsNotified[$id] = $absNew;
                }

                // 2) Reviewer -> Sense (once only) — send to Sense checker (PMs in CC)
                if ($revSubmitted && $revNew && !$revAlreadyNotified && $senseId) {
                    $notifySense[$senseId]['count'] = ($notifySense[$senseId]['count'] ?? 0) + 1;
                    $notifySense[$senseId]['items'][] = [
                        'project_name' => $projectName,
                        'tenant_id'    => $r['tenant_or_lease_id'] ?? $prev->tenant_or_lease_id,
                        'property'     => $r['property_name']      ?? $prev->property_name,
                        'tenant'       => $r['tenant_name']        ?? $prev->tenant_name,
                        'completed_at' => $revNew,
                        'stage'        => 'Review Completed',
                        'next_action'  => 'Sense Check',
                    ];
                    $markRevNotified[$id] = $revNew;
                }

                // 3) Sense -> PMs ONLY (once only)
                if ($senseSubmitted && $senseNew && !$senseAlreadyNotified) {
                    $notifyPmOnSense['count'] = ($notifyPmOnSense['count'] ?? 0) + 1;
                    $notifyPmOnSense['items'][] = [
                        'project_name' => $projectName,
                        'tenant_id'    => $r['tenant_or_lease_id'] ?? $prev->tenant_or_lease_id,
                        'property'     => $r['property_name']      ?? $prev->property_name,
                        'tenant'       => $r['tenant_name']        ?? $prev->tenant_name,
                        'completed_at' => $senseNew,
                        'stage'        => 'Sense Check Completed',
                        'next_action'  => '—',
                    ];
                    $markSenseNotified[$id] = $senseNew;
                }
            }

            // Upsert after detection
            if (!empty($rows)) {
                ProjectIntake::upsert($rows, ['id'], $presentColumns);
            }

            // AFTER COMMIT: send emails & update *_notified_on
            DB::afterCommit(function () use (
                $notifyReviewer,
                $notifySense,
                $notifyPmOnSense,
                $parentId,
                $markAbsNotified,
                $markRevNotified,
                $markSenseNotified
            ) {
                // PM list (TO/CC) — from project_user; keep your dummy CC/TO as requested
                $pmUserIds = DB::table('project_user')->where('project_id', $parentId)->pluck('user_id')->all();
                $pmEmails  = User::whereIn('id', $pmUserIds)->pluck('email')->filter()->values()->all();
                // Keep a dummy PM address present for verification
                $pmEmails  = array_values(array_unique(array_merge($pmEmails, ['gambhir.makwana@yopmail.com'])));

                // 1) Reviewers (dummy TO preserved)
                foreach ($notifyReviewer as $reviewerId => $payload) {
                    $to = User::find($reviewerId)?->email;
                    if ($to) {
                        Mail::to($to)->cc($pmEmails)->queue(new StageNotificationMail(
                            title: 'Abstraction Completed',
                            subtitle: 'Items ready for Review',
                            count: (int)($payload['count'] ?? 0),
                            items: $payload['items'] ?? [],
                            ctaText: 'Open Dashboard',
                            ctaUrl: route('dashboard')
                        ));
                    }
                }

                // 2) Sense checkers (dummy TO, PMs in CC)
                foreach ($notifySense as $senseId => $payload) {
                    $to = User::find($senseId)?->email;
                    if ($to) {
                        Mail::to($to)->cc($pmEmails)->queue(new StageNotificationMail(
                            title: 'Review Completed',
                            subtitle: 'Items ready for Sense Check',
                            count: (int)($payload['count'] ?? 0),
                            items: $payload['items'] ?? [],
                            ctaText: 'Open Dashboard',
                            ctaUrl: route('projects.index')
                        ));
                    }
                }

                // 3) PMs ONLY for sense completion (dummy reviewer CC kept for visibility)
                if (!empty($notifyPmOnSense['count'])) {
                    Mail::to($pmEmails)->queue(new StageNotificationMail(
                        title: 'Sense Check Completed',
                        subtitle: 'Sense Check updates',
                        count: (int)$notifyPmOnSense['count'],
                        items: $notifyPmOnSense['items'] ?? [],
                        ctaText: 'Open Dashboard',
                        ctaUrl: route('projects.index')
                    ));
                }

                // Mark notified_on only for rows actually mailed (dates already Y-m-d)
                if (!empty($markAbsNotified)) {
                    foreach ($markAbsNotified as $id => $date) {
                        ProjectIntake::where('id', $id)->update(['abstract_notified_on' => $date]);
                    }
                }
                if (!empty($markRevNotified)) {
                    foreach ($markRevNotified as $id => $date) {
                        ProjectIntake::where('id', $id)->update(['review_notified_on' => $date]);
                    }
                }
                if (!empty($markSenseNotified)) {
                    foreach ($markSenseNotified as $id => $date) {
                        ProjectIntake::where('id', $id)->update(['sense_notified_on' => $date]);
                    }
                }
            });
        });

        return back()->with('success', 'Intake rows saved successfully.');
    }

    private function applyIntakeFiltersOnIntakes(Builder $q, Request $request): void
    {
        $f = (array) $request->input('filter', []);

        // ---- Simple equals/select filters ----
        if (!empty($f['property_manager_id'])) {
            $q->where('property_manager_id', $f['property_manager_id']);
        }
        if (!empty($f['status_id'])) {
            $q->where('status_master_id', $f['status_id']);
        }
        if (!empty($f['type_of_work_id'])) {
            $q->where('type_of_work_id', $f['type_of_work_id']);
        }
        // client/customer: in fileView we're scoped to one parent project already,
        // so this is effectively fixed. Keep for API compatibility; becomes no-op.
        // If you really want to enforce, you can assert the parent project's customer here.

        if (!empty($f['abstractor_id'])) {
            $q->where('abstractor_id', $f['abstractor_id']);
        }
        if (!empty($f['reviewer_id'])) {
            $q->where('reviewer_id', $f['reviewer_id']);
        }
        if (!empty($f['sense_check_ddr_id'])) {
            $q->where('sense_check_ddr_id', $f['sense_check_ddr_id']);
        }

        // ---- Member-wise (role + member user) ----
        if (!empty($f['member_role']) && !empty($f['member_user_id'])) {
            $map = [
                'abstractor'       => 'abstractor_id',
                'reviewer'         => 'reviewer_id',
                'sense_check'      => 'sense_check_ddr_id',
                'property_manager' => 'property_manager_id',
            ];
            if (isset($map[$f['member_role']])) {
                $q->where($map[$f['member_role']], $f['member_user_id']);
            }
        }

        // ---- Date range helper (expects 'YYYY-MM-DD' strings) ----
        $between = function (Builder $qb, string $col, ?string $from, ?string $to) {
            if ($from && $to)        $qb->whereBetween($col, [$from, $to]);
            elseif ($from)           $qb->where($col, '>=', $from);
            elseif ($to)             $qb->where($col, '<=', $to);
        };

        // Received Date
        $between(
            $q,
            'request_received_date',
            $f['request_received_date_from'] ?? null,
            $f['request_received_date_to']   ?? null
        );

        // Delivered Date
        $between(
            $q,
            'delivered_date',
            $f['delivered_date_from'] ?? null,
            $f['delivered_date_to']   ?? null
        );

        // Actual Delivered Date
        $between(
            $q,
            'actual_delivered_date',
            $f['actual_delivered_date_from'] ?? null,
            $f['actual_delivered_date_to']   ?? null
        );

        // Feedback received date
        $between(
            $q,
            'feedback_received_date',
            $f['feedback_received_date_from'] ?? null,
            $f['feedback_received_date_to']   ?? null
        );

        // ---- Billing Month (supports 'YYYY-MM' or 'YYYY-MM-DD' stored) ----
        if (!empty($f['billing_month'])) {
            $q->where('billing_month', 'like', $f['billing_month'] . '%');
        }

        // ---- Invoice Method ----
        if (!empty($f['invoice_format_id'])) {
            $q->where('invoice_format_id', $f['invoice_format_id']);
        }

        // ---- Customer Feedback category ----
        if (!empty($f['fb_category_id'])) {
            $q->where('fb_category_id', $f['fb_category_id']);
        }

        // ---- Has Feedback (1/0) ----
        if (array_key_exists('has_feedback', $f) && $f['has_feedback'] !== '') {
            if ((string)$f['has_feedback'] === '1') {
                $q->where(function ($qq) {
                    $qq->whereNotNull('fb_customer_comments')->where('fb_customer_comments', '<>', '')
                        ->orWhereNotNull('fb_sb_response')->where('fb_sb_response', '<>', '')
                        ->orWhereNotNull('fb_category_id');
                });
            } elseif ((string)$f['has_feedback'] === '0') {
                $q->where(function ($qq) {
                    $qq->whereNull('fb_customer_comments')->orWhere('fb_customer_comments', '=', '')
                        ->whereNull('fb_sb_response')->orWhere('fb_sb_response', '=', '')
                        ->whereNull('fb_category_id');
                });
            }
        }
    }

    public function pmMembers(Request $request)
    {
        $v = $request->validate([
            'pm_ids'     => 'required|array|min:1',
            'pm_ids.*'   => 'integer|exists:users,id',
            'project_id' => 'nullable|integer|exists:projects,id',
        ]);

        $pmIds     = array_map('intval', $v['pm_ids']);
        $projectId = $v['project_id'] ?? null;

        // Ensure the mapping column exists
        if (!Schema::hasColumn('users', 'project_manager')) {
            return response()->json([
                'data' => [],
                'selected_member_ids' => [],
                'message' => 'Column users.project_manager not found'
            ]);
        }

        // Fetch members where users.project_manager IN (pm_ids)
        $membersRaw = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'email')
            ->whereIn('project_manager', $pmIds)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // Deduplicate & normalize
        $seen  = [];
        $list  = [];
        foreach ($membersRaw as $u) {
            if (isset($seen[$u->id])) continue;
            $seen[$u->id] = true;

            $fullName = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
            if ($fullName === '') $fullName = $u->email ?: ('User #' . $u->id);

            $list[] = [
                'id'    => (int) $u->id,
                'name'  => $fullName,
                'email' => $u->email,
            ];
        }

        // For edit mode: try to return preselected member ids
        $selectedIds = [];
        if ($projectId) {
            if (Schema::hasTable('project_members')) {
                // project_members: project_id, user_id
                $selectedIds = DB::table('project_members')
                    ->where('project_id', $projectId)
                    ->pluck('user_id')
                    ->map(fn($x) => (int) $x)
                    ->all();
            } elseif (Schema::hasTable('project_pm_members')) {
                // project_pm_members: project_id, pm_id, member_id
                $selectedIds = DB::table('project_pm_members')
                    ->where('project_id', $projectId)
                    ->pluck('member_id')
                    ->map(fn($x) => (int) $x)
                    ->all();
            }
        }

        return response()->json([
            'data'                 => $list,             // [{id, name, email}]
            'selected_member_ids'  => $selectedIds,      // [1,2,3] (optional preselect)
        ]);
    }

    protected function syncMemberAssignments(Project $project, Request $request): void
    {
        // Always clear existing rows for this project first
        ProjectMemberAssignment::where('project_id', $project->id)->delete();

        // Only apply when category == 1
        if ((int)$request->input('project_category') !== 1) {
            return; // nothing else to do
        }

        $map = $request->input('members_by_pm', []);
        if (!is_array($map) || empty($map)) return;

        // (Optional) restrict to PMs actually selected on the project
        $validPmIds = $project->pms()->pluck('users.id')->map(fn($v) => (int)$v)->all();
        $validPmSet = array_flip($validPmIds);

        $rows = [];
        $seen = [];

        foreach ($map as $pmId => $memberIds) {
            $pmId = (int)$pmId;
            if ($pmId <= 0 || !isset($validPmSet[$pmId])) continue;

            foreach ((array)$memberIds as $memberId) {
                $memberId = (int)$memberId;
                if ($memberId <= 0) continue;

                $key = $pmId . '-' . $memberId;
                if (isset($seen[$key])) continue;
                $seen[$key] = true;

                $rows[] = [
                    'project_id' => $project->id,
                    'pm_id'      => $pmId,
                    'member_id'  => $memberId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($rows) {
            ProjectMemberAssignment::insert($rows);
        }
    }

    //     public function generalView(string $encryptedId, Request $request)
    // {
    //     $id = Crypt::decryptString($encryptedId);

    //     // Load the project
    //     $project = Project::findOrFail($id);

    //     // Only valid for category 1
    //     if ((int)($project->project_category ?? 0) !== 1) {
    //         abort(404);
    //     }

    //     $user = Auth::user();
    //     $isAdminOrManager = $user->hasRole('super admin') || $user->hasRole('project manager');

    //     // Defaults: current month unless overridden
    //     $defaultStart = now()->startOfMonth();
    //     $defaultEnd   = now()->endOfMonth();

    //     // Accept GET ?start_date=MM/DD/YYYY&end_date=MM/DD/YYYY
    //     $startStr = $request->query('start_date');
    //     $endStr   = $request->query('end_date');

    //     $startDate = $startStr ? Carbon::createFromFormat('m/d/Y', $startStr) : $defaultStart;
    //     $endDate   = $endStr   ? Carbon::createFromFormat('m/d/Y', $endStr)   : $defaultEnd;

    //     // Guard: swap if reversed
    //     if ($startDate->gt($endDate)) {
    //         [$startDate, $endDate] = [$endDate, $startDate];
    //     }

    //     if ($isAdminOrManager) {
    //         // Admin/PM: fetch assignments for all members
    //         $assignments = ProjectMemberAssignment::with('member')->where('project_id', $id)->get();
    //     } else {
    //         // Regular member: only their assignment
    //         $assignments = ProjectMemberAssignment::with('member')->where('project_id', $id)
    //             ->where('member_id', $user->id)
    //             ->get();
    //     }

    //     // TODO: fetch actual project data filtered by $startDate/$endDate and maybe by assignments
    //     // Example:
    //     // $items = SomeModel::where('project_id', $project->id)
    //     //     ->whereBetween('date_col', [$startDate->startOfDay(), $endDate->endOfDay()])
    //     //     ->get();

    //     return view('content.project.general-view', [
    //         'title'        => 'View Project',
    //         'project'      => $project,
    //         'encryptedId'  => $encryptedId,
    //         'startDate'    => $startDate,
    //         'endDate'      => $endDate,
    //         'assignments'  => $assignments,
    //         'isAdminOrManager' => $isAdminOrManager,
    //         // 'items'      => $items,
    //     ]);
    // }

    // public function storeAssignmentDates(Request $request, $encryptedId)
    // {
    //     $projectId = Crypt::decryptString($encryptedId);
    //     $validated = $request->validate([
    //         'member_id'  => ['required', 'integer'],
    //         'start_date' => ['required', 'date_format:m/d/Y'],
    //         'end_date'   => ['required', 'date_format:m/d/Y', 'after_or_equal:start_date'],
    //     ], [
    //         'end_date.after_or_equal' => 'End date must be on or after Start date.',
    //     ]);

    //     $start = Carbon::createFromFormat('m/d/Y', $validated['start_date'])->format('Y-m-d');
    //     $end   = Carbon::createFromFormat('m/d/Y', $validated['end_date'])->format('Y-m-d');

    //     $assignment = ProjectMemberAssignment::where('project_id', $projectId)
    //         ->where('member_id', $validated['member_id'])
    //         ->first();

    //     if ($assignment) {
    //         $assignment->update([
    //             'startdate' => $start,
    //             'enddate'   => $end,
    //         ]);
    //     }

    //     return redirect()
    //         ->route('projects.generalView', $encryptedId)
    //         ->with('success', 'Assignment dates saved.');
    // }

    public function import(Project $project, GenericFileUploadRequest $request)
    {

        Excel::import(new ProjectIntakesImport((int) $project->id), $request->file('bulk_file'));
        //Excel::import(new ProjectIntakesImport((int) $project->id), $file, null, $readerType);
        //return back()->with('success', 'Project intakes imported successfully.');
        $project_id = Crypt::encryptString($project->id);
        return redirect()
            ->route('projects.fileView', $project_id) // or ['project' => $project->id]
            ->with('success', 'Project intakes imported successfully.');
    }

    function dmyToMdY(?string $v): ?string
    {
        if (!$v) return null;
        $v = str_replace('/', '-', trim($v));
        try {
            $fmt = preg_match('/^\d{2}-\d{2}-\d{2}$/', $v) ? 'm-d-y' : 'm-d-Y';
            //dd(Carbon::createFromFormat($fmt, $v)->format('Y-m-d'));
            return Carbon::createFromFormat($fmt, $v)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
    
    private function dmyToYmd(?string $value): ?string
    {
        $v = trim((string) $value);
        if ($v === '') return null;

        // Accept also already-normalized values to be robust
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
            return $v;
        }

        try {
            return Carbon::createFromFormat('m-d-Y', $v)->format('Y-m-d');
        } catch (\Throwable $e) {
            // Let validation catch bad dates later
            return $v;
        }
    }
}
