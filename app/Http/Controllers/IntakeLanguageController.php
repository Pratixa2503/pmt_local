<?php

namespace App\Http\Controllers;

use App\Models\IntakeLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\DataTables\IntakeLanguageDataTable;

class IntakeLanguageController extends Controller
{
    public function index(IntakeLanguageDataTable $dataTable)
    {
        if (!auth()->user()->can('view intake language')) {
            abort(403);
        }
        return $dataTable->render('content.intake-languages.index', ['title' => 'Intake Languages']);
    }

    public function create()
    {
        if (!auth()->user()->can('create intake language')) {
            abort(403);
        }
        return view('content.intake-languages.form', ['title' => 'Add Language', 'edit' => false]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create intake language')) {
            abort(403);
        }

        $request->validate([
            'name' => ['required','string','max:150', Rule::unique('intake_languages','name')],
        ]);

        IntakeLanguage::create($request->only('name'));
        return redirect()->route('intake-languages.index')->with('success','Language added successfully.');
    }

    public function edit($encId)
    {
        if (!auth()->user()->can('edit intake language')) {
            abort(403);
        }

        $id = Crypt::decryptString($encId);
        $item = IntakeLanguage::findOrFail($id);

        return view('content.intake-languages.form', [
            'title' => 'Edit Language',
            'edit'  => true,
            'item'  => $item,
        ]);
    }

    public function update(Request $request, $encId)
    {
        if (!auth()->user()->can('edit intake language')) {
            abort(403);
        }

        $id = Crypt::decryptString($encId);
        $item = IntakeLanguage::findOrFail($id);

        $request->validate([
            'name' => ['required','string','max:150', Rule::unique('intake_languages','name')->ignore($item->id)],
        ]);

        $item->update($request->only('name'));
        return redirect()->route('intake-languages.index')->with('success','Language updated successfully.');
    }

    public function destroy($encId)
    {
        if (!auth()->user()->can('delete intake language')) {
            abort(403);
        }
        $id = Crypt::decryptString($encId);
        $item = IntakeLanguage::findOrFail($id);
        $item->delete();

        return response()->json(['status' => true, 'message' => 'Language deleted successfully.']);
    }
}
