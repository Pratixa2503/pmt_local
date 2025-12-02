<?php

namespace App\Http\Controllers;

use App\Models\InputOutputFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\InputOutputFormatTable;

class InputOutputFormatController extends Controller
{
     public function index(InputOutputFormatTable $dataTable) { 
        if ( !auth()->user()->can('create input output format')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.inputoutputformate.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create input output format')) {
            abort(403, 'Unauthorized action.');
        }
        return view('content.inputoutputformate.form', [
            'title' => 'Create Input/Output Format',
            'type' => 'create'
        ]);
    }

    public function store(Request $request)
    {
       if ( !auth()->user()->can('create input output format')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:input_output_formats,name',
            'status' => 'required|boolean',
        ]);

        InputOutputFormat::create(array_merge($request->only('name', 'status'),['created_by'  => auth()->id(),
            'updated_by'  => auth()->id()]));

        return redirect()->route('input-output-formats.index')->with('success', 'Format created successfully.');
    }

    public function edit($id)
    {
        if ( !auth()->user()->can('edit input output format')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
        $data = InputOutputFormat::findOrFail($id);

        return view('content.inputoutputformate.form', [
            'title' => 'Edit Input/Output Format',
            'type' => 'edit',
            'data' => $data,
            'id' => Crypt::encryptString($data->id),
        ]);
    }

    public function update(Request $request, $id)
    {
       if ( !auth()->user()->can('edit input output format')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
        $format = InputOutputFormat::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100|unique:input_output_formats,name,' . $format->id,
            'status' => 'required|boolean',
        ]);

        $format->update(array_merge($request->only('name', 'status'),['updated_by'  => auth()->id()]));

        return redirect()->route('input-output-formats.index')->with('success', 'Format updated successfully.');
    }

    public function destroy($id)
    {
       if ( !auth()->user()->can('delete input output format')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
        InputOutputFormat::findOrFail($id)->delete();
        $status     = true;
        $message    = "Format deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
