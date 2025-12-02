<?php

// app/Http/Controllers/UnitOfMeasurementController.php

namespace App\Http\Controllers;

use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\UnitOfMeasurementDataTable;
use Illuminate\Support\Facades\Crypt;

class UnitOfMeasurementController extends Controller
{
    public function index(UnitOfMeasurementDataTable $dataTable)
    {
        if ( !auth()->user()->can('view unit of measurement')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.unit-of-measurement.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create unit of measurement')) {
            abort(403, 'Unauthorized action.');
        }
        $type = "create";
        $title = "Create Unit Of Measurement";
        return view('content.unit-of-measurement.form',compact('title','type'));
    }

    public function store(Request $request)
    {
        if ( !auth()->user()->can('create unit of measurement')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name'   => 'required|string|max:255|unique:unit_of_measurements,name',
            'status' => 'required|in:0,1',
        ]);

        UnitOfMeasurement::create([
            'name'       => $request->name,
            'status'     => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('unit-of-measurements.index')
            ->with('success', 'Unit of Measurement created successfully.');
    }

    public function edit($encryptedId)
    {
        if ( !auth()->user()->can('edit unit of measurement')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($encryptedId);
        $data = UnitOfMeasurement::findOrFail($id);
        $title = "Edit Unit of measurement";
        $type = "edit";
        return view('content.unit-of-measurement.form', compact('data','title','type'));
    }

    public function update(Request $request, $encryptedId)
    { 
        if ( !auth()->user()->can('edit unit of measurement')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($encryptedId);
        $unit = UnitOfMeasurement::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255|unique:unit_of_measurements,name,' . $unit->id,
            'status' => 'required|in:0,1',
        ]);

        $unit->update([
            'name'       => $request->name,
            'status'     => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('unit-of-measurements.index')
            ->with('success', 'Unit of Measurement updated successfully.');
    }

    public function destroy($encryptedId)
    {
        if ( !auth()->user()->can('delete unit of measurement')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($encryptedId);
        $unit = UnitOfMeasurement::findOrFail($id);
        $unit->delete();
        $status     = true;
        $message    = "Unit of Measurement deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}

