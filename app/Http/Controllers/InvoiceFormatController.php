<?php

namespace App\Http\Controllers;

use App\Models\InvoiceFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\DataTables\InvoiceFormatDataTable;

class InvoiceFormatController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view invoice formats')->only(['index']);
        // $this->middleware('permission:create invoice format')->only(['create','store']);
        // $this->middleware('permission:edit invoice format')->only(['edit','update']);
        // $this->middleware('permission:delete invoice format')->only(['destroy']);
    }

   public function index(InvoiceFormatDataTable $dataTable)
    {
        abort_unless(auth()->user()->can('view invoice format'), 403);

        $title = 'Invoice Formats';
        return $dataTable->render('content.invoice_formats.index', compact('title'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create invoice format'), 403);

        $title = 'Add Invoice Format';
        $type  = 'create';
        $model = new InvoiceFormat();

        return view('content.invoice_formats.form', compact('title','type','model'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create invoice format'), 403);

        $validated = $request->validate([
            'name'   => ['required','string','max:255','unique:invoice_formats,name'],
            'status' => ['nullable','boolean'],
        ]);

        $format = new InvoiceFormat();
        $format->name   = $validated['name'];
        $format->status = (bool)($validated['status'] ?? 0);
        $format->save();

        return redirect()->route('invoice-formats.index')
            ->with('success', 'Invoice format created successfully.');
    }

    public function edit(string $encryptedId)
    {
        abort_unless(auth()->user()->can('edit invoice format'), 403);

        $id     = Crypt::decryptString($encryptedId);
        $model  = InvoiceFormat::findOrFail($id);
        $title  = 'Edit Invoice Format';
        $type   = 'edit';

        return view('content.invoice_formats.form', compact('title','type','model','encryptedId'));
    }

    public function update(Request $request, string $encryptedId)
    {
        abort_unless(auth()->user()->can('edit invoice format'), 403);

        $id    = Crypt::decryptString($encryptedId);
        $model = InvoiceFormat::findOrFail($id);

        $validated = $request->validate([
            'name'   => ['required','string','max:255','unique:invoice_formats,name,'.$model->id],
            'status' => ['nullable','boolean'],
        ]);

        $model->name   = $validated['name'];
        $model->status = (bool)($validated['status'] ?? 0);
        $model->save();

        return redirect()->route('invoice-formats.index')
            ->with('success', 'Invoice format updated successfully.');
    }

    public function destroy(Request $request)
    {
        abort_unless(auth()->user()->can('delete invoice format'), 403);

        $request->validate(['id' => ['required','string']]);

        $id    = Crypt::decryptString($request->id);
        $model = InvoiceFormat::findOrFail($id);
        $model->delete();

        return response()->json(['status' => true, 'message' => 'Deleted successfully.']);
    }
}
