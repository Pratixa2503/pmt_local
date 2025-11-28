<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\CurrencyDataTable;

class CurrencyController extends Controller
{
    public function index(CurrencyDataTable $dataTable)
    {
        if ( !auth()->user()->can('view currency')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.currencies.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create currency')) {
            abort(403, 'Unauthorized action.');
        }
        $title = "Create Currency";
        $type = "create";
        return view('content.currencies.form',compact('title','type'));
    }

    public function store(Request $request)
    {
        if ( !auth()->user()->can('create currency')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|unique:currencies,name',
            'status' => 'required|boolean',
        ]);

        Currency::create([
            'name' => $request->name,
            'status' => $request->status,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    public function edit($id)
    {
        if ( !auth()->user()->can('edit currency')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $data = Currency::findOrFail($id);
        $title = "Edit Currency";
        $type = "edit";
        return view('content.currencies.form', compact('data','title','type'));
    }

    public function update(Request $request, $id)
    {
        if ( !auth()->user()->can('edit currency')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $currency = Currency::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:currencies,name,' . $currency->id,
            'status' => 'required|boolean',
        ]);

        $currency->update([
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully.');
    }

    public function destroy($id)
    {
        if ( !auth()->user()->can('delete currency')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($id);
        $currency = Currency::findOrFail($id);
        $currency->delete();
        $status     = true;
        $message    = "Currency deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);

        //return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
