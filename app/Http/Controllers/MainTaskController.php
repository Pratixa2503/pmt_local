<?php

namespace App\Http\Controllers;

use App\Models\MainTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\DataTables\MainTaskDataTable;

class MainTaskController extends Controller
{
    public function index(MainTaskDataTable $dataTable)
    {
        if (!auth()->user()->can('view task')) {
            abort(403, 'Unauthorized action.');
        }
        // View: resources/views/content/tasks/main/index.blade.php
        return $dataTable->render('content.tasks.main.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create task')) {
            abort(403, 'Unauthorized action.');
        }
        // View: resources/views/content/tasks/main/form.blade.php
        return view('content.tasks.main.form', [
            'title' => 'Add Main Task',
            'edit'  => false,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create task')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'       => [
                'required', 'string', 'max:255',
                Rule::unique('main_tasks', 'name') // ->whereNull('deleted_at') if using soft deletes
            ],
            'status'     => ['required', 'in:0,1'],
            // NEW: validate task_type (1=Productive, 2=General)
            'task_type'  => ['required', 'in:1,2'],
        ]);

        MainTask::create($request->only('name', 'status', 'task_type'));

        return redirect()->route('maintasks.index')
            ->with('success', 'Main task added successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit task')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $mainTask = MainTask::findOrFail($decryptedId);

        return view('content.tasks.main.form', [
            'title'    => 'Edit Main Task',
            'mainTask' => $mainTask,
            'edit'     => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit task')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $mainTask = MainTask::findOrFail($decryptedId);

        $request->validate([
            'name'       => [
                'sometimes', 'required', 'string', 'max:255',
                Rule::unique('main_tasks', 'name')->ignore($mainTask->id)
            ],
            'status'     => ['required', 'in:0,1'],
            // NEW: validate task_type on update as well
            'task_type'  => ['required', 'in:1,2'],
        ]);

        $mainTask->update($request->only('name', 'status', 'task_type'));

        return redirect()->route('maintasks.index')
            ->with('success', 'Main task updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete task')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $mainTask = MainTask::findOrFail($decryptedId);

        $mainTask->delete();

        return response()->json(['status' => 1, 'message' => 'Main task deleted successfully.']);
    }
}
