<?php

namespace App\Http\Controllers;

use App\Models\ProjectPriority;
use Illuminate\Http\Request;
use App\DataTables\ProjectPriorityDataTable;
use Illuminate\Support\Facades\Crypt;

class ProjectPriorityController extends Controller
{
    public function index(ProjectPriorityDataTable $dataTable)
    {
         if ( !auth()->user()->can('view project priorities')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.project-priorities.index');

        return view('project-priorities.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create project priorities')) {
            abort(403, 'Unauthorized action.');
        }
        $title = 'Create Project Priorities';
        return view('content.project-priorities.create',compact('title'));
    }

    public function store(Request $request)
    {
        if ( !auth()->user()->can('create project priorities')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|unique:project_priorities,name',
            'status' => 'required|boolean',
        ]);

        ProjectPriority::create(array_merge($request->all(),['created_by' => auth()->id(),'updated_by'=>auth()->id()]));

        return redirect()->route('project-priorities.index')->with('success', 'Priority created');
    }

    public function edit($id)
    {
        if ( !auth()->user()->can('edit project priorities')) {
            abort(403, 'Unauthorized action.');
        }
        $title = 'Edit Project Priority';
        $type = "edit";
        $id = Crypt::decryptString($id);
        $projectType = ProjectPriority::findOrFail($id);
        return view('content.project-priorities.edit', compact('projectType','title','type'));
    }

    public function update(Request $request, $id)
    {
        if ( !auth()->user()->can('edit project priorities')) {
            abort(403, 'Unauthorized action.');
        }
       
        $id = Crypt::decryptString($id);
        $priority = ProjectPriority::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:project_priorities,name,' . $priority->id,
            'status' => 'required|boolean',
        ]);

        $priority->update(array_merge($request->all(),['updated_by'=>auth()->id()]));

        return redirect()->route('project-priorities.index')->with('success', 'Priority updated');
    }

    public function destroy($id)
    {
        if ( !auth()->user()->can('delete project priorities')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $department = ProjectPriority::findOrFail($id);
        $department->delete();
        $status     = true;
        $message    = "Project Priority deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
