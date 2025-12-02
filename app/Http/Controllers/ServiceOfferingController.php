<?php

// app/Http/Controllers/ServiceOfferingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOffering;
use App\DataTables\ServiceOfferingDataTable;
use Illuminate\Support\Facades\Crypt;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
class ServiceOfferingController extends Controller
{
    public function index(ServiceOfferingDataTable $dataTable)
    {
        if ( !auth()->user()->can('view service offering')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.service-offerings.index');
    }

    public function create()
    {
        $title = "Create Service Offering";
        $type = "create";
        $department = Department::query()
        ->leftJoin('industry_verticals as iv', 'iv.id', '=', 'departments.industry_verticals_id')
        ->where('departments.status', 1)
        ->select([
            'departments.id',
            'departments.industry_verticals_id',
            'departments.status',
            // alias as `name` so existing Blade that prints $dept->name keeps working
            DB::raw("CONCAT(departments.name, ' - ', COALESCE(iv.name, '')) as name"),
        ])
        ->orderBy('name')
        ->get();
        return view('content.service-offerings.form',compact('title','type','department'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => [
                'required',
                'string',
                'max:255',
                // unique per department
                \Illuminate\Validation\Rule::unique('service_offerings', 'name')
                    ->where(fn ($q) => $q->where('department_id', $request->input('department_id'))),
            ],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'status'        => ['required', 'in:1,0'], // matches your select values
        ]);

        // Trim name to avoid accidental trailing spaces
        $validated['name'] = trim($validated['name']);

        ServiceOffering::create([
            'name'          => $validated['name'],
            'department_id' => (int) $validated['department_id'],
            'status'        => (int) $validated['status'],
            'created_by'    => auth()->id(),
            'updated_by'    => auth()->id(),
        ]);

        return redirect()
            ->route('service-offerings.index')
            ->with('success', 'Service Offering created successfully.');
    }


    public function edit($id)
    {
        $title = "Edit Service Offering";
        $type = "edit";
        $data = ServiceOffering::findOrFail(Crypt::decryptString($id));
        $department = Department::query()
        ->leftJoin('industry_verticals as iv', 'iv.id', '=', 'departments.industry_verticals_id')
        ->where('departments.status', 1)
        ->select([
            'departments.id',
            'departments.industry_verticals_id',
            'departments.status',
            // alias as `name` so existing Blade that prints $dept->name keeps working
            DB::raw("CONCAT(departments.name, ' - ', COALESCE(iv.name, '')) as name"),
        ])
        ->orderBy('name')
        ->get();
        return view('content.service-offerings.form', compact('data','title','type','department'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $service = ServiceOffering::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                 \Illuminate\Validation\Rule::unique('service_offerings', 'name')
                    ->where(fn ($q) => $q->where('department_id', $request->input('department_id')))
                    ->ignore($id),
            ],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'status'        => ['required', 'in:1,0'],
        ]);

        $service->update([
            'name'          => trim($validated['name']),
            'department_id' => (int) $validated['department_id'],
            'status'        => (int) $validated['status'],
            'updated_by'    => auth()->id(),
        ]);

        return redirect()
            ->route('service-offerings.index')
            ->with('success', 'Service Offering updated successfully.');
    }


    public function destroy($id)
    {
        ServiceOffering::findOrFail(Crypt::decryptString($id))->delete();
        $status     = true;
        $message    = "Service Offering deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
        //return redirect()->route('service-offerings.index')->with('success', 'Service Offering deleted successfully.');
    }

    public function byDepartment($department)
    {
        $departmentId = (int) $department;

        \Validator::make(['department_id' => $departmentId], [
            'department_id' => 'required|exists:departments,id',
        ])->validate();

        $items = ServiceOffering::query()
            ->where('status', 1)
            ->where('department_id', $departmentId) // assumes you added this FK
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['data' => $items]);
    }
}
