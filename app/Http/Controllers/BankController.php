<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\DataTables\BankDataTable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index(BankDataTable $dataTable)
    {
        if (!auth()->user()->can('view bank')) {
            abort(403, 'Unauthorized action.');
        }

        return $dataTable->render('content.banks.index');
    }

    public function create()
    {
        if (!auth()->user()->can('create bank')) {
            abort(403, 'Unauthorized action.');
        }

        $currencies = DB::table('currencies')
            ->orderBy('name')
            ->get(['id','name']);

        return view('content.banks.create', [
            'title'       => 'Add Bank',
            'currencies'  => $currencies,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create bank')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'entity'           => ['required','string','max:255'],
            'currency_id'      => ['required','integer','exists:currencies,id'],
            'account_name'     => ['required','string','max:255'],
            'account_number'   => [
                'required',
                'string',
                Rule::unique('banks', 'account_number')->whereNull('deleted_at'),
            ],
            'bank_name'        => ['required','string','max:255'],
            'branch_location'  => ['nullable','string','max:255'],
            'ifsc_code'        => ['nullable','string','max:50','regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            'swift_code'       => ['nullable','string','max:50'],
            'micr'             => ['nullable','string','max:50'],
            'bsr_code'         => ['nullable','string','max:50'],
            'branch_address'   => ['nullable','string'],
            'status'           => ['required','in:0,1'],
            'aba_number'     => ['nullable','digits:9'],
            'routing_number' => ['nullable','digits:9'],
        ]);

        Bank::create(array_merge(
            $request->only([
                'entity','currency_id','account_name','account_number','bank_name',
                'branch_location','ifsc_code','swift_code','micr','bsr_code','branch_address','status','aba_number','routing_number'
            ]),
            [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]
        ));

        return redirect()->route('banks.index')->with('success', 'Bank details added successfully');
    }

    public function edit($id)
    {
        if (!auth()->user()->can('edit bank')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $bank = Bank::findOrFail($decryptedId);

        $currencies = DB::table('currencies')
            ->orderBy('name')
            ->get(['id','name']);

        return view('content.banks.create', [
            'title'      => 'Edit Bank',
            'bank'       => $bank,
            'currencies' => $currencies,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('edit bank')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $bank = Bank::findOrFail($decryptedId);

        $request->validate([
            'entity'           => ['sometimes','required','string','max:255'],
            'currency_id'      => ['sometimes','required','integer','exists:currencies,id'],
            'account_name'     => ['sometimes','required','string','max:255'],
            'account_number'   => [
                'sometimes',
                'required',
                'string',
                Rule::unique('banks', 'account_number')
                    ->ignore($bank->id)
                    ->whereNull('deleted_at'),
            ],
            'bank_name'        => ['sometimes','required','string','max:255'],
            'branch_location'  => ['nullable','string','max:255'],
            'ifsc_code'        => ['nullable','string','max:50','regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            'swift_code'       => ['nullable','string','max:50'],
            'micr'             => ['nullable','string','max:50'],
            'bsr_code'         => ['nullable','string','max:50'],
            'branch_address'   => ['nullable','string'],
            'status'           => ['required','in:0,1'],
            'aba_number'     => ['nullable','digits:9'],
            'routing_number' => ['nullable','digits:9'],
        ]);

        $bank->update(array_merge(
            $request->only([
                'entity','currency_id','account_name','account_number','bank_name',
                'branch_location','ifsc_code','swift_code','micr','bsr_code','branch_address','status','aba_number','routing_number'
            ]),
            ['updated_by' => auth()->id()]
        ));

        return redirect()->route('banks.index')->with('success', 'Bank details updated successfully');
    }

    public function destroy($id)
    {
        if (!auth()->user()->can('delete bank')) {
            abort(403, 'Unauthorized action.');
        }

        $decryptedId = Crypt::decryptString($id);
        $bank = Bank::findOrFail($decryptedId);
        $bank->delete();

        return response()->json(['status' => 1, 'message' => 'Bank record deleted successfully.']);
    }
}
