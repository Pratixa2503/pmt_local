<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectDeliveryFrequency;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\ProjectDeliveryFrequencyTable;

class ProjectDeliveryFrequencyController extends Controller
{
    public function index(ProjectDeliveryFrequencyTable $dataTable)
    {
        if (!auth()->user()->can('view delivery frequencies')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.project-delivery-frequencies.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create delivery frequencies')) {
            abort(403, 'Unauthorized action.');
        }
        $title = 'Create Project Delivery Frequency';
        $type = 'create';
        return view('content.project-delivery-frequencies.create',compact('title','type'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create delivery frequencies')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name'   => 'required|string|max:255|unique:project_delivery_frequencies,name',
            'status' => 'required|boolean',
        ], [
            'name.required' => 'Please enter frequency name.',
            'name.unique'   => 'This frequency name already exists.',
            'status.required' => 'Please select a status.',
        ]);

        ProjectDeliveryFrequency::create(array_merge($request->all(),['created_by' => auth()->id(),'updated_by'=>auth()->id()]));

        return redirect()->route('project-delivery-frequencies.index')->with('success', 'Project Delivery Frequency created successfully.');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit delivery frequencies')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
        $frequency = ProjectDeliveryFrequency::findOrFail($id);
        $title = 'Edit Project Delivery Frequency';
        $type = 'edit';
        $data =$frequency;
        $id = Crypt::encrypt($data->id);
        return view('content.project-delivery-frequencies.create', compact('data','title','id','type'));
    }

    public function update(Request $request, $id)
    {
       
        if (!auth()->user()->can('edit delivery frequencies')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
         
        $frequency = ProjectDeliveryFrequency::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255|unique:project_delivery_frequencies,name,' . $frequency->id,
            'status' => 'required|boolean',
        ], [
            'name.required' => 'Please enter frequency name.',
            'name.unique'   => 'This frequency name already exists.',
            'status.required' => 'Please select a status.',
        ]);

        $frequency->update(array_merge($request->all(),['updated_by'=>auth()->id()]));
        return redirect()->route('project-delivery-frequencies.index')->with('success', 'Project Delivery Frequency updated successfully.');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete delivery frequencies')) {
            abort(403, 'Unauthorized action.');
        }

        $id = Crypt::decryptString($id);
        $frequency = ProjectDeliveryFrequency::findOrFail($id);
        $frequency->delete();
        $status     = true;
        $message    = "Project Delivery Frequency deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
