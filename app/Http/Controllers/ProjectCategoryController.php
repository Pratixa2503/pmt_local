<?php

namespace App\Http\Controllers;

use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use App\DataTables\ProjectCategoryDataTable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class ProjectCategoryController extends Controller
{
    public function index(ProjectCategoryDataTable $dataTable)
    {
        if (!auth()->user()->can('view project category')) {
            abort(403, 'Unauthorized action.');
        }

        return $dataTable->render('content.project_categories.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create project category')) {
            abort(403, 'Unauthorized action.');
        }

        return view('content.project_categories.create', [
            'title' => 'Add Project Category',
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create project category')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('project_categories', 'name')->whereNull('deleted_at'),
            ],
            'status' => ['required', 'in:0,1'],
        ]);

        ProjectCategory::create(array_merge(
            $request->only(['name', 'status']),
            [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]
        ));

        return redirect()->route('project-categories.index')->with('success', 'Project category added successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit project category')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $category = ProjectCategory::findOrFail($decryptedId);
        
        return view('content.project_categories.create', [
            'title'    => 'Edit Project Category',
            'type'     => 'edit',
            'model' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit project category')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $category = ProjectCategory::findOrFail($decryptedId);

        $request->validate([
            'name'   => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('project_categories', 'name')
                    ->ignore($category->id)
                    ->whereNull('deleted_at'),
            ],
            'status' => ['required', 'in:0,1'],
        ]);

        $category->update(array_merge(
            $request->only(['name', 'status']),
            ['updated_by' => auth()->id()]
        ));

        return redirect()->route('project-categories.index')->with('success', 'Project category updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete project category')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $category = ProjectCategory::findOrFail($decryptedId);
        $category->delete();

        return response()->json(['status' => 1, 'message' => 'Project category deleted successfully.']);
    }
}
