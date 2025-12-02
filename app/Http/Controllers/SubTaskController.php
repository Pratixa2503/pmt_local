<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\MainTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\DataTables\SubTaskDataTable;

class SubTaskController extends Controller
{
    public function index(SubTaskDataTable $dataTable)
    {
        if (!auth()->user()->can('view task')) {
            abort(403, 'Unauthorized action.');
        }
        // View: resources/views/content/tasks/sub/index.blade.php
        return $dataTable->render('content.tasks.sub.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create task')) {
            abort(403, 'Unauthorized action.');
        }

        $mainTasks = MainTask::orderBy('name')->pluck('name', 'id'); // for select dropdown

        // View: resources/views/content/tasks/sub/create.blade.php
        return view('content.tasks.sub.form', [
            'title'     => 'Add Sub Task',
            'mainTasks' => $mainTasks,
            'edit'  => false
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create task')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'main_task_id'     => ['required', 'exists:main_tasks,id'],
            'name'             => ['required', 'string', 'max:255',
                // Optional: unique per main task
                // Rule::unique('sub_tasks', 'name')->where('main_task_id', $request->main_task_id)
            ],
            'task_type'        => ['required', 'in:1,2'], // 1=Production, 2=Non-Production
            'count_type'       => ['required', 'in:1,2'], // 🌟 NEW: 1=Mandatory, 2=Optional
            'benchmarked_time' => ['nullable', 'regex:/^\d{2}:\d{2}:\d{2}$/'], // HH:MM:SS
            'status'           => ['required', 'in:0,1'],
        ]);

        SubTask::create($request->only(
            'main_task_id', 'name', 'task_type','count_type', 'benchmarked_time', 'status'
        ));

        return redirect()->route('subtasks.index')
            ->with('success', 'Sub task added successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit task')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $subTask = SubTask::findOrFail($decryptedId);
        $mainTasks = MainTask::orderBy('name')->pluck('name', 'id');

        // View: resources/views/content/tasks/sub/create.blade.php
        return view('content.tasks.sub.form', [
            'title'     => 'Edit Sub Task',
            'subTask'   => $subTask,
            'mainTasks' => $mainTasks,
            'edit'  => true
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit task')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $subTask = SubTask::findOrFail($decryptedId);

        $request->validate([
            'main_task_id'     => ['sometimes', 'required', 'exists:main_tasks,id'],
            'name'             => ['sometimes', 'required', 'string', 'max:255',
                // Optional unique per main task:
                // Rule::unique('sub_tasks', 'name')
                //     ->ignore($subTask->id)
                //     ->where('main_task_id', $request->main_task_id ?? $subTask->main_task_id)
            ],
            'task_type'        => ['required', 'in:1,2'],
            'benchmarked_time' => ['nullable', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'status'           => ['required', 'in:0,1'],
        ]);

        $subTask->update($request->only(
            'main_task_id', 'name', 'task_type', 'benchmarked_time', 'status'
        ));

        return redirect()->route('subtasks.index')
            ->with('success', 'Sub task updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete task')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $subTask = SubTask::findOrFail($decryptedId);
        $subTask->delete();

        return response()->json(['status' => 1, 'message' => 'Sub task deleted successfully.']);
    }
}
