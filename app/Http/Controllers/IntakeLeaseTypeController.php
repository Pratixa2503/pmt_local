<?php

namespace App\Http\Controllers;

use App\Models\IntakeLeaseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\DataTables\IntakeLeaseTypeDataTable;

class IntakeLeaseTypeController extends Controller
{
    public function index(IntakeLeaseTypeDataTable $dataTable)
    {
        if (!auth()->user()->can('view intake lease type')) {
            abort(403);
        }
        return $dataTable->render('content.intake-lease-types.index', ['title' => 'Intake Lease Types']);
    }

    public function create()
    {
        if (!auth()->user()->can('create intake lease type')) {
            abort(403);
        }
        return view('content.intake-lease-types.form', ['title' => 'Add Lease Type', 'edit' => false]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create intake lease type')) {
            abort(403);
        }

        $request->validate([
            'name' => ['required','string','max:150', Rule::unique('intake_lease_types','name')],
        ]);

        IntakeLeaseType::create($request->only('name'));
        return redirect()->route('intake-lease-types.index')->with('success','Lease Type added successfully.');
    }

    public function edit($encId)
    {
        if (!auth()->user()->can('edit intake lease type')) {
            abort(403);
        }

        $id = Crypt::decryptString($encId);
        $item = IntakeLeaseType::findOrFail($id);

        return view('content.intake-lease-types.form', [
            'title' => 'Edit Lease Type',
            'edit'  => true,
            'item'  => $item,
        ]);
    }

    public function update(Request $request, $encId)
    {
        if (!auth()->user()->can('edit intake lease type')) {
            abort(403);
        }

        $id = Crypt::decryptString($encId);
        $item = IntakeLeaseType::findOrFail($id);

        $request->validate([
            'name' => ['required','string','max:150', Rule::unique('intake_lease_types','name')->ignore($item->id)],
        ]);

        $item->update($request->only('name'));
        return redirect()->route('intake-lease-types.index')->with('success','Lease Type updated successfully.');
    }

    public function destroy($encId)
    {
        if (!auth()->user()->can('delete intake lease type')) {
            abort(403);
        }
        $id = Crypt::decryptString($encId);
        $item = IntakeLeaseType::findOrFail($id);
        $item->delete();

        return response()->json(['status' => true, 'message' => 'Lease Type deleted successfully.']);
    }
}
