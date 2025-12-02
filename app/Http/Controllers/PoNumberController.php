<?php

namespace App\Http\Controllers;

use App\Models\PoNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Helpers\Helpers;
use App\DataTables\PoNumberDataTable;

class PoNumberController extends Controller
{
    public function index(PoNumberDataTable $dataTable)
    {
        if (!auth()->user()->can('view po')) {
            abort(403);
        }

        if (!auth()->user()->can('view po')) abort(403);
        return $dataTable->render('content.po_numbers.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create po')) {
            abort(403);
        }

        $customers = DB::table('companies')->orderBy('name')->whereNull('deleted_at')->get(['id','name']);
        $projects  = DB::table('projects')->whereNull('parent_id')->whereNull('deleted_at')->where('project_status_id',2)->orderBy('id')->get(['id','project_name','parent_id']);
       
        // Optionally pre-filter sub-projects to only children
        $subProjects = DB::table('projects')->whereNotNull('parent_id')->orderBy('project_name')->get(['id','project_name','parent_id']);

        return view('content.po_numbers.form', [
            'title'       => 'Add PO Number',
            'edit'        => false,
            'customers'   => $customers,
            'projects'    => $projects,
            'subProjects' => $subProjects,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create po')) {
            abort(403);
        }

        $request->validate([
            'customer_id'   => ['required','integer','exists:companies,id'],
            'project_id'    => ['required','integer','exists:projects,id'],
            'sub_project_id'=> ['nullable','integer','exists:projects,id'],
            'start_date'    => ['nullable','date'],
            'end_date'      => ['nullable','date','after_or_equal:start_date'],
            'po_number'     => [
                'required','string','max:191',
                Rule::unique('po_numbers', 'po_number')
                    ->where(fn($q) => $q->where('project_id', $request->project_id)
                                         ->where('sub_project_id', $request->sub_project_id)),
            ],
            'status'        => ['required','in:0,1'],
        ]);

        PoNumber::create(array_merge(
            $request->only([
                'customer_id','project_id','sub_project_id','start_date','end_date','po_number','status'
            ]),
            [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]
        ));

        return redirect()->route('po-numbers.index')->with('success', 'PO Number created successfully.');
    }

    public function edit(string $encryptedId)
    {
        if (!auth()->user()->can('edit po')) {
            abort(403);
        }

        $id  = Crypt::decryptString($encryptedId);
        $row = PoNumber::findOrFail($id);

        $customers  = DB::table('companies')->orderBy('name')->get(['id','name']);
        $projects  = DB::table('projects')->whereNull('parent_id')->whereNull('deleted_at')->where('project_status_id',2)->orderBy('id')->get(['id','project_name','parent_id']);
        $subProjects= DB::table('projects')->whereNotNull('parent_id')->orderBy('project_name')->get(['id','project_name','parent_id']);

        return view('content.po_numbers.form', [
            'title'       => 'Edit PO Number',
            'edit'        => true,
            'row'         => $row,
            'customers'   => $customers,
            'projects'    => $projects,
            'subProjects' => $subProjects,
            'encryptedId' => $encryptedId,
        ]);
    }

    public function update(Request $request, string $encryptedId)
    {
        if (!auth()->user()->can('edit po')) {
            abort(403);
        }

        $id  = Crypt::decryptString($encryptedId);
        $row = PoNumber::findOrFail($id);

        $request->validate([
            'customer_id'   => ['sometimes','required','integer','exists:companies,id'],
            'project_id'    => ['sometimes','required','integer','exists:projects,id'],
            'sub_project_id'=> ['nullable','integer','exists:projects,id'],
            'start_date'    => ['nullable','date'],
            'end_date'      => ['nullable','date','after_or_equal:start_date'],
            'po_number'     => [
                'sometimes','required','string','max:191',
                Rule::unique('po_numbers', 'po_number')
                    ->ignore($row->id)
                    ->where(fn($q) => $q->where('project_id', $request->project_id ?? $row->project_id)
                                         ->where('sub_project_id', $request->sub_project_id ?? $row->sub_project_id)),
            ],
            'status'        => ['required','in:0,1'],
        ]);

        $row->update(array_merge(
            $request->only([
                'customer_id','project_id','sub_project_id','start_date','end_date','po_number','status'
            ]),
            ['updated_by' => auth()->id()]
        ));

        return redirect()->route('po-numbers.index')->with('success', 'PO Number updated successfully.');
    }

    public function destroy(string $encryptedId)
    {
        if (!auth()->user()->can('delete po')) {
            abort(403);
        }

        $id  = Crypt::decryptString($encryptedId);
        $row = PoNumber::findOrFail($id);
        $row->delete();

        return response()->json(['status' => 1, 'message' => 'PO Number deleted successfully.']);
    }

    public function subProjects($projectId){
        
         $masters = Helpers::subProjects($projectId);
         return $masters;
    }

    public function projectsByCustomer($customerId)
    {
        $projects = DB::table('projects')
            ->where('customer_id', $customerId)
            ->whereNull('parent_id')
            ->whereNull('deleted_at')
            // ->where('project_status_id', 2)
            ->orderBy('project_name')
            ->get(['id', 'project_name']);

        return response()->json($projects);
    }
}
