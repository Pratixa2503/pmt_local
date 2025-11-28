<?php

namespace App\Http\Controllers;

use App\Models\IntakeWorkType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\DataTables\IntakeWorkTypeDataTable;

class IntakeWorkTypeController extends Controller
{
    public function index(IntakeWorkTypeDataTable $dataTable)
    {
        if (!auth()->user()->can('view intake work type')) {
            abort(403);
        }
        return $dataTable->render('content.intake-work-types.index', ['title' => 'Intake Work Types']);
    }

    public function create()
    {
        if (!auth()->user()->can('create intake work type')) {
            abort(403);
        }
        return view('content.intake-work-types.form', ['title' => 'Add Work Type', 'edit' => false]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create intake work type')) {
            abort(403);
        }

        $request->validate([
            'name' => ['required','string','max:150', Rule::unique('intake_work_types','name')],
        ]);

        IntakeWorkType::create($request->only('name'));
        return redirect()->route('intake-work-types.index')->with('success','Work Type added successfully.');
    }

    public function edit($encId)
    {
        if (!auth()->user()->can('edit intake work type')) {
            abort(403);
        }

        $id = Crypt::decryptString($encId);
        $item = IntakeWorkType::findOrFail($id);

        return view('content.intake-work-types.form', [
            'title' => 'Edit Work Type',
            'edit'  => true,
            'item'  => $item,
        ]);
    }

    public function update(Request $request, $encId)
    {
        if (!auth()->user()->can('edit intake work type')) {
            abort(403);
        }

        $id = Crypt::decryptString($encId);
        $item = IntakeWorkType::findOrFail($id);

        $request->validate([
            'name' => ['required','string','max:150', Rule::unique('intake_work_types','name')->ignore($item->id)],
        ]);

        $item->update($request->only('name'));
        return redirect()->route('intake-work-types.index')->with('success','Work Type updated successfully.');
    }

    public function destroy($encId)
    {
        if (!auth()->user()->can('delete intake work type')) {
            abort(403);
        }
        $id = Crypt::decryptString($encId);
        $item = IntakeWorkType::findOrFail($id);
        $item->delete();

        return response()->json(['status' => true, 'message' => 'Work Type deleted successfully.']);
    }
}
