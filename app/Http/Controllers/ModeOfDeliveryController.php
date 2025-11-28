<?php

namespace App\Http\Controllers;
use App\Models\ModeOfDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\ModeOfDeliveryTable;

class ModeOfDeliveryController extends Controller {

    public function index(ModeOfDeliveryTable $dataTable) { 
        if ( !auth()->user()->can('view mode of delivery')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.mode-of-delivery.index');
    }

    public function create() {
        if ( !auth()->user()->can('create mode of delivery')) {
            abort(403, 'Unauthorized action.');
        }
        return view('content.mode-of-delivery.create', ['title' => 'Add Mode of Delivery']);
    }

    public function store(Request $request) {
        if ( !auth()->user()->can('create mode of delivery')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|unique:mode_of_deliveries,name|max:100',
            'status' => 'required|boolean',
        ]);

        ModeOfDelivery::create(array_merge($request->all(),['created_by' => auth()->id(),'updated_by'=>auth()->id()]));
        return redirect()->route('mode-of-delivery.index')->with('success', 'Mode of Delivery created successfully.');
    }

    public function edit($id) {
        if ( !auth()->user()->can('edit mode of delivery')) {
            abort(403, 'Unauthorized action.');
        }
        $mode = ModeOfDelivery::findOrFail(Crypt::decryptString($id));
        return view('content.mode-of-delivery.edit', compact('mode'))->with('title', 'Edit Mode of Delivery');
    }

    public function update(Request $request, $id) {
        if ( !auth()->user()->can('edit mode of delivery')) {
            abort(403, 'Unauthorized action.');
        }
       $request->validate([
            'name' => 'required|string|max:100|unique:mode_of_deliveries,name,' . Crypt::decryptString($id),
            'status' => 'required|boolean',
        ]);

        $modeOfDelivery = ModeOfDelivery::findOrFail(Crypt::decryptString($id));
        $modeOfDelivery->update(array_merge($request->all(),['updated_by'=> auth()->id()]));
        return redirect()->route('mode-of-delivery.index')->with('success', 'Mode of Delivery updated successfully.');
    }

    public function destroy($id) {
      
        if ( !auth()->user()->can('delete mode of delivery')) {
            abort(403, 'Unauthorized action.');
        }
        $modeOfDelivery = ModeOfDelivery::findOrFail(Crypt::decryptString($id));
        
        $modeOfDelivery->delete();
        $status     = true;
        $message    = "Mode of Delivery deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
