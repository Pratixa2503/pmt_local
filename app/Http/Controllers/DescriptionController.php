<?php

namespace App\Http\Controllers;

use App\Models\Description;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\DescriptionDataTable;

class DescriptionController extends Controller
{
    public function index(DescriptionDataTable $dataTable)
    {
        if ( !auth()->user()->can('view description')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.descriptions.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create description')) {
            abort(403, 'Unauthorized action.');
        }
        $type  = "create";
        $title = "Create Description";
        return view('content.descriptions.form',compact('type','title'));
    }

    public function store(Request $request)
    {
        if ( !auth()->user()->can('create description')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|unique:descriptions,name',
            'status' => 'required|boolean',
        ]);

        Description::create([
            'name' => $request->name,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('descriptions.index')->with('success', 'Description created successfully.');
    }

    public function edit($id)
    {
        if ( !auth()->user()->can('edit description')) {
            abort(403, 'Unauthorized action.');
        }
        $type  = "edit";
        $title = "Edit Description";
        $id = Crypt::decryptString($id);
        $data = Description::findOrFail($id);
        return view('content.descriptions.form', compact('data','type','title'));
    }

    public function update(Request $request, $id)
    {
        if ( !auth()->user()->can('edit description')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $description = Description::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:descriptions,name,' . $description->id,
            'status' => 'required|boolean',
        ]);

        $description->update([
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('descriptions.index')->with('success', 'Description updated successfully.');
    }

    public function destroy($id)
    {
        if ( !auth()->user()->can('delete description')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $description = Description::findOrFail($id);
        $description->delete();
        $status     = true;
        $message    = "Description deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}

