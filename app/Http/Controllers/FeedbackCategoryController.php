<?php

namespace App\Http\Controllers;

use App\DataTables\FeedbackCategoryDataTable;
use App\Models\FeedbackCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FeedbackCategoryController extends Controller
{
    public function index(FeedbackCategoryDataTable $dataTable)
    {
        abort_unless(auth()->user()?->can('view feedback category'), 403);

        $title = 'Feedback Categories';
        return $dataTable->render('content.feedback_categories.index', compact('title'));
    }

    public function create()
    {
        abort_unless(auth()->user()?->can('create feedback category'), 403);

        $title = 'Add Feedback Category';
        $type  = 'create';
        $model = new FeedbackCategory();
        return view('content.feedback_categories.form', compact('title','type','model'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->can('create feedback category'), 403);

        $validated = $request->validate([
            'name'   => ['required','string','max:255','unique:feedback_categories,name'],
            'status' => ['nullable','boolean'],
        ]);

        $item = new FeedbackCategory();
        $item->name   = $validated['name'];
        $item->status = (bool)($validated['status'] ?? 1); // default active
        $item->save();

        return redirect()->route('feedback-categories.index')->with('success', 'Feedback category created successfully.');
    }

    public function edit(string $encryptedId)
    {
        abort_unless(auth()->user()?->can('edit feedback category'), 403);

        $id    = Crypt::decryptString($encryptedId);
        $model = FeedbackCategory::findOrFail($id);

        $title = 'Edit Feedback Category';
        $type  = 'edit';

        return view('content.feedback_categories.form', compact('title','type','model','encryptedId'));
    }

    public function update(Request $request, string $encryptedId)
    {
        abort_unless(auth()->user()?->can('edit feedback category'), 403);

        $id    = Crypt::decryptString($encryptedId);
        $model = FeedbackCategory::findOrFail($id);

        $validated = $request->validate([
            'name'   => ['required','string','max:255','unique:feedback_categories,name,'.$model->id],
            'status' => ['nullable','boolean'],
        ]);

        $model->name   = $validated['name'];
        $model->status = (bool)($validated['status'] ?? 1);
        $model->save();

        return redirect()->route('feedback-categories.index')->with('success', 'Feedback category updated successfully.');
    }

    public function destroy(Request $request)
    {
        abort_unless(auth()->user()?->can('delete feedback category'), 403);

        $request->validate(['id' => ['required','string']]);

        $id    = Crypt::decryptString($request->id);
        $model = FeedbackCategory::findOrFail($id);
        $model->delete();

        return response()->json(['status' => true, 'message' => 'Deleted successfully.']);
    }
}
