<?php
namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\DepartmentDataTable;
<<<<<<< HEAD

=======
use App\Models\IndustryVertical;
>>>>>>> 9d9ed85b (for cleaner setup)
class DepartmentController extends Controller
{
    public function index(DepartmentDataTable $dataTable)
    {
        if ( !auth()->user()->can('view department')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.departments.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create department')) {
            abort(403, 'Unauthorized action.');
        }
        $title = 'Create Department';
<<<<<<< HEAD
        return view('content.departments.create', compact('title'));
=======
        $industry_verticals = IndustryVertical::where('status',1)->get();
        return view('content.departments.create', compact('title','industry_verticals'));
>>>>>>> 9d9ed85b (for cleaner setup)
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
        if ( !auth()->user()->can('create department')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|boolean',
        ]);

        Department::create(array_merge($request->all(),['created_by' => auth()->id(),'updated_by' => auth()->id()]));

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

=======
        if (! auth()->user()->can('create department')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'industry_verticals_id' => ['required', 'integer', 'exists:industry_verticals,id'],
            'status'                => ['required', 'in:1,0'], // matches your select values
        ]);

        // Trim name to avoid accidental spaces
        $validated['name'] = trim($validated['name']);
      
        Department::create([
            'name'                  => $validated['name'],
            'industry_verticals_id' => (int) $validated['industry_verticals_id'],
            'status'                => (int) $validated['status'],
            'created_by'            => auth()->id(),
            'updated_by'            => auth()->id(),
        ]);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }


>>>>>>> 9d9ed85b (for cleaner setup)
    public function edit($id)
    {
        if ( !auth()->user()->can('edit department')) {
            abort(403, 'Unauthorized action.');
        }
        $title = 'Edit Department';
        $id = Crypt::decryptString($id);
        $department = Department::findOrFail($id);
<<<<<<< HEAD

        return view('content.departments.edit', compact('department', 'title'));
=======
        $industry_verticals = IndustryVertical::where('status',1)->get();
        return view('content.departments.edit', compact('department','industry_verticals','title'));
>>>>>>> 9d9ed85b (for cleaner setup)
    }

    public function update(Request $request, $id)
    {
        if ( !auth()->user()->can('edit department')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $request->validate([
            'name' => 'required|string|max:100',
            'status' => 'required|boolean',
        ]);

        $department = Department::findOrFail($id);
        $department->update(array_merge($request->all(),['updated_by' => auth()->id()]));

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        if ( !auth()->user()->can('delete department')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $department = Department::findOrFail($id);
        $department->delete();
        $status     = true;
        $message    = "Department deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
<<<<<<< HEAD
=======

    public function byIndustry($industry)
    {
         $industryId = (int) $industry;

        // validate exists
        \Validator::make(['industry_vertical_id' => $industryId], [
            'industry_vertical_id' => 'required|exists:industry_verticals,id',
        ])->validate();

        $items = Department::query()
            ->where('status', 1)
            ->where('industry_verticals_id', $industryId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json(['data' => $items]);
    }
>>>>>>> 9d9ed85b (for cleaner setup)
}
