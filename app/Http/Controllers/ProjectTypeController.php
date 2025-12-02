<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\ProjectTypeDataTable;

class ProjectTypeController extends Controller
{
    public function index(ProjectTypeDataTable $dataTable)
    {
        return $dataTable->render('content.project_types.index');
    }

    public function create()
    {
        $title = "Create Project Type";
        return view('content.project_types.create',compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);

        ProjectType::create([
            'name'   => $request->name,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'updated_by'=>auth()->id()
        ]);

        return redirect()->route('project-types.index')->with('success', 'Project type created successfully.');
    }

    public function edit($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $projectType = ProjectType::findOrFail($id);
            $title = "Edit Project Type";
            return view('content.project_types.edit', compact('projectType', 'title'));
        } catch (\Exception $e) {
            return redirect()->route('project-types.index')->with('error', 'Invalid Project Type ID.');
        }
    }

    public function update(Request $request, $encryptedId)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|boolean'
        ]);

        try {
            $id = Crypt::decryptString($encryptedId);
            $projectType = ProjectType::findOrFail($id);

            $projectType->update([
                'name'   => $request->name,
                'status' => $request->status,
                'updated_by'=>auth()->id()
            ]);

            return redirect()->route('project-types.index')->with('success', 'Project type updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('project-types.index')->with('error', 'Invalid Project Type ID.');
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);

            $projectType = ProjectType::findOrFail($id);
            $projectType->delete();
            $status     = true;
            $message    = "Project type deleted successfully.";
            return response()->json([
                'status'  => $status,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('project-types.index')->with('error', 'Invalid Project Type ID.');
        }
    }
}
