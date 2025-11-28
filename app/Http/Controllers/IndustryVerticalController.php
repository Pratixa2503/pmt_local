<?php

// app/Http/Controllers/IndustryVerticalController.php

namespace App\Http\Controllers;

use App\Models\IndustryVertical;
use Illuminate\Http\Request;
use App\DataTables\IndustryDataTable;
use Illuminate\Support\Facades\Crypt;

class IndustryVerticalController extends Controller
{
    public function index(IndustryDataTable $dataTable) { 
        if ( !auth()->user()->can('create industry vertical')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.industry-vertical.index');
    }

    public function create()
    {
        $title = "Create Industry Vertical";
        $type = "create";
        return view('content.industry-vertical.form',compact('title','type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:industry_verticals,name',
            'status' => 'required|boolean'
        ]);

        IndustryVertical::create([
            'name'        => $request->name,
            'status'      => $request->status,
            'created_by'  => auth()->id(),
            'updated_by'  => auth()->id(),
        ]);

        return redirect()->route('industry-verticals.index')->with('success', 'Industry Vertical created successfully.');
    }

    public function edit($id)
    {
        $title = "Edit Industry Vertical";
        $type = "edit";
        $data = IndustryVertical::findOrFail(Crypt::decryptString($id));
        return view('content.industry-vertical.form', compact('data','title','type'));
    }

    public function update(Request $request, $id)
    {
        $id = Crypt::decryptString($id);
        $request->validate([
            'name' => 'required|unique:industry_verticals,name,'.$id,
            'status' => 'required|boolean'
        ]);

        IndustryVertical::findOrFail($id)->update([
            'name'       => $request->name,
            'status'     => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('industry-verticals.index')->with('success', 'Industry Vertical updated successfully.');
    }

    public function destroy($id)
    {
        IndustryVertical::findOrFail(Crypt::decryptString($id))->delete();
        $status     = true;
        $message    = "Industry Vertical deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
