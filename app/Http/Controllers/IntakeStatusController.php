<?php

namespace App\Http\Controllers;

use App\Models\IntakeStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\IntakeStatusDataTable;

class IntakeStatusController extends Controller
{
    public function index(IntakeStatusDataTable $dataTable)
    {
        if (!auth()->user()->can('view intake status')) {
            abort(403, 'Unauthorized action.');
        }
        // View: resources/views/content/intake-statuses/index.blade.php
        return $dataTable->render('content.intake-statuses.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create intake status')) {
            abort(403, 'Unauthorized action.');
        }
        // View: resources/views/content/intake-statuses/form.blade.php
        return view('content.intake-statuses.form', [
            'title' => 'Add Intake Status',
            'edit'  => false,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create intake status')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('intake_statuses', 'name'),
            ],
        ]);

        IntakeStatus::create($request->only('name'));

        return redirect()
            ->route('intake-statuses.index')
            ->with('success', 'Intake status added successfully.');
    }

    public function edit($encryptedId)
    {
        if (!auth()->user()->can('edit intake status')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $status = IntakeStatus::findOrFail($id);

        return view('content.intake-statuses.form', [
            'title'  => 'Edit Intake Status',
            'edit'   => true,
            'status' => $status,
        ]);
    }

    public function update(Request $request, $encryptedId)
    {
        if (!auth()->user()->can('edit intake status')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $status = IntakeStatus::findOrFail($id);

        $request->validate([
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('intake_statuses', 'name')->ignore($status->id),
            ],
        ]);

        $status->update($request->only('name'));

        return redirect()
            ->route('intake-statuses.index')
            ->with('success', 'Intake status updated successfully.');
    }

    public function destroy($encryptedId)
    {
        if (!auth()->user()->can('delete intake status')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $status = IntakeStatus::findOrFail($id);
        $status->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Intake status deleted successfully.',
        ]);
    }
}
