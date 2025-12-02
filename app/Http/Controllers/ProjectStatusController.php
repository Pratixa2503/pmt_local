<?php

namespace App\Http\Controllers;

use App\Models\ProjectStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\ProjectStatusTable; 

class ProjectStatusController extends Controller
{
    
    public function index(ProjectStatusTable $dataTable)
    {
        if ( !auth()->user()->can('view project status')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.project-statuses.index');
    }

    public function create()
    {
        $title = 'Create Project Status';
        return view('content.project-statuses.create',compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:project_statuses,name',
            'status' => 'required|boolean',
        ]);

        ProjectStatus::create(array_merge($request->all(),['created_by' => auth()->id(),'updated_by'=>auth()->id()]));

        return redirect()->route('project-statuses.index')->with('success', 'Project Status created successfully.');
    }

    public function edit($id)
    {
        $title = 'Edit Project Status';
        $id = Crypt::decryptString($id);
        $projectType = ProjectStatus::findOrFail($id);

        return view('content.project-statuses.edit', compact('projectType','title'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $projectStatus = ProjectStatus::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:project_statuses,name,' . $projectStatus->id,
            'status' => 'required|boolean',
        ]);

        $projectStatus->update(array_merge($request->only('name', 'status'),['updated_by'=>auth()->id()]));

        return redirect()->route('project-statuses.index')->with('success', 'Project Status updated successfully.');
    }

    public function destroy($id)
    {
        $id = Crypt::decryptString($id);
        $projectStatus = ProjectStatus::findOrFail($id);
        $projectStatus->delete();
        $status     = true;
        $message    = "Project Status deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
