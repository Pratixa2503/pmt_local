<?php

namespace App\Http\Controllers;

use App\Models\IntakeQueryType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\IntakeQueryTypeDataTable;

class IntakeQueryTypeController extends Controller
{
    public function index(IntakeQueryTypeDataTable $dataTable)
    {
        if (!auth()->user()->can('view intake query type')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.intake-query-types.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create intake query type')) {
            abort(403, 'Unauthorized action.');
        }
        return view('content.intake-query-types.form', [
            'title' => 'Add Intake Query Type',
            'edit'  => false,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create intake query type')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('intake_query_types', 'name'),
            ],
        ]);

        IntakeQueryType::create($request->only('name'));

        return redirect()->route('intake-query-types.index')
            ->with('success', 'Intake Query Type added successfully.');
    }

    public function edit($encryptedId)
    {
        if (!auth()->user()->can('edit intake query type')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $item = IntakeQueryType::findOrFail($id);

        return view('content.intake-query-types.form', [
            'title' => 'Edit Intake Query Type',
            'edit'  => true,
            'item'  => $item,
        ]);
    }

    public function update(Request $request, $encryptedId)
    {
        if (!auth()->user()->can('edit intake query type')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $item = IntakeQueryType::findOrFail($id);

        $request->validate([
            'name' => [
                'required', 'string', 'max:150',
                Rule::unique('intake_query_types', 'name')->ignore($item->id),
            ],
        ]);

        $item->update($request->only('name'));

        return redirect()->route('intake-query-types.index')
            ->with('success', 'Intake Query Type updated successfully.');
    }

    public function destroy($encryptedId)
    {
        if (!auth()->user()->can('delete intake query type')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $item = IntakeQueryType::findOrFail($id);
        $item->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Intake Query Type deleted successfully.',
        ]);
    }
}
