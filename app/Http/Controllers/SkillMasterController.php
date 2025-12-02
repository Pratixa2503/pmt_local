<?php
namespace App\Http\Controllers;

use App\Models\SkillMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\SkillMasterDataTable;

class SkillMasterController extends Controller
{
    public function index(SkillMasterDataTable $dataTable)
    {
        if ( !auth()->user()->can('view skill master')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.skill-masters.index');
    }

    public function create()
    {
        if ( !auth()->user()->can('create skill master')) {
            abort(403, 'Unauthorized action.');
        }
        $title = "Create Skill";
        $type = "create";
        return view('content.skill-masters.form', compact('title', 'type'));
    }

    public function store(Request $request)
    {
        if ( !auth()->user()->can('create skill master')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'skill_expertise_level' => 'required|string|max:255',
            'ctc' => 'required|numeric|min:0',
            'status' => 'required'
        ]);

        SkillMaster::create([
            'name' => $request->name,
            'skill_expertise_level' => $request->skill_expertise_level,
            'ctc' => $request->ctc,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('skill-masters.index')->with('success', 'Skill created successfully.');
    }

    public function edit($encryptedId)
    {
        if ( !auth()->user()->can('edit skill master')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($encryptedId);
        $data = SkillMaster::findOrFail($id);
        $title = "Edit Skill";
        $type = "edit";

        return view('content.skill-masters.form', compact('data', 'title', 'type'));
    }

    public function update(Request $request, $encryptedId)
    {
        if ( !auth()->user()->can('edit skill master')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($encryptedId);
        $request->validate([
            'name' => 'required|string|max:255',
            'skill_expertise_level' => 'required|string|max:255',
            'ctc' => 'required|numeric|min:0',
            'status' => 'required',
        ]);

        $skill = SkillMaster::findOrFail($id);
        $skill->update([
            'name' => $request->name,
            'skill_expertise_level' => $request->skill_expertise_level,
            'ctc' => $request->ctc,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('skill-masters.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy($encryptedId)
    {
        if ( !auth()->user()->can('delete skill master')) {
            abort(403, 'Unauthorized action.');
        }
        $id = Crypt::decryptString($encryptedId);
        SkillMaster::findOrFail($id)->delete();
        $status     = true;
        $message    = "Skill deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
