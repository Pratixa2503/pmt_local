<?php
namespace App\Http\Controllers;

use App\DataTables\QueryStatusDataTable;
use App\Models\QueryStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class QueryStatusController extends Controller
{
    public function index(QueryStatusDataTable $dataTable)
    {
        // Permission: view
        abort_unless(auth()->user()?->can('view query status'), 403);

        $title = 'Query Statuses';
        return $dataTable->render('content.query_statuses.index', compact('title'));
    }

    public function create()
    {
        // Permission: create
        abort_unless(auth()->user()?->can('create query status'), 403);

        $title = 'Add Query Status';
        $type  = 'create';
        $model = new QueryStatus();
        return view('content.query_statuses.form', compact('title','type','model'));
    }

    public function store(Request $request)
    {
        // Permission: create
        abort_unless(auth()->user()?->can('create query status'), 403);

        $validated = $request->validate([
            'name'   => ['required','string','max:255','unique:query_statuses,name'],
            'status' => ['nullable','boolean'],
        ]);

        $item = new QueryStatus();
        $item->name   = $validated['name'];
        $item->status = (bool)($validated['status'] ?? 1); // default Active
        $item->save();

        return redirect()->route('query-statuses.index')->with('success', 'Query status created successfully.');
    }

    public function edit(string $encryptedId)
    {
        // Permission: edit
        abort_unless(auth()->user()?->can('edit query status'), 403);

        $id    = Crypt::decryptString($encryptedId);
        $model = QueryStatus::findOrFail($id);

        $title = 'Edit Query Status';
        $type  = 'edit';

        return view('content.query_statuses.form', compact('title','type','model','encryptedId'));
    }

    public function update(Request $request, string $encryptedId)
    {
        // Permission: edit
        abort_unless(auth()->user()?->can('edit query status'), 403);

        $id    = Crypt::decryptString($encryptedId);
        $model = QueryStatus::findOrFail($id);

        $validated = $request->validate([
            'name'   => ['required','string','max:255','unique:query_statuses,name,'.$model->id],
            'status' => ['nullable','boolean'],
        ]);

        $model->name   = $validated['name'];
        $model->status = (bool)($validated['status'] ?? 1);
        $model->save();

        return redirect()->route('query-statuses.index')->with('success', 'Query status updated successfully.');
    }

    public function destroy(Request $request)
    {
        // Permission: delete
        abort_unless(auth()->user()?->can('delete query status'), 403);

        $request->validate(['id' => ['required','string']]);

        $id    = Crypt::decryptString($request->id);
        $model = QueryStatus::findOrFail($id);
        $model->delete();

        return response()->json(['status' => true, 'message' => 'Deleted successfully.']);
    }
}
