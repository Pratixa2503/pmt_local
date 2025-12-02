<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\CustomerCredentialsMail;
use App\DataTables\CustomerDataTable;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CustomerDataTable $dataTable)
    {
        if ( !auth()->user()->can('view customer')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ( !auth()->user()->can('create customer')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $title      = __('Customer') . ' : ' .  __('Add');
            $roles      = Role::where('name', '=', 'customer')->get();
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.customer.create', compact('roles', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ( !auth()->user()->can('create customer')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'first_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'last_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'contact_no' => ['required', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'company_name' => ['nullable', 'max:100', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'status' => ['required', 'in:0,1'],
        ], [
            'first_name.required' => 'Please enter First Name.',
            'first_name.max' => 'First Name cannot exceed 100 characters.',
            'first_name.regex' => 'First Name can only contain letters, numbers, and spaces.',

            'last_name.required' => 'Please enter Last Name.',
            'last_name.max' => 'Last Name cannot exceed 100 characters.',
            'last_name.regex' => 'Last Name can only contain letters, numbers, and spaces.',

            'email.required' => 'Please enter Email.',
            'email.email' => 'Please enter a valid email.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'This email is already in use.',

            'contact_no.required' => 'Please enter Contact Number.',
            'contact_no.max' => 'Contact Number cannot exceed 20 digits.',
            'contact_no.regex' => 'Contact Number can only contain digits, +, - and spaces.',

            'company_name.max' => 'Company Name cannot exceed 100 characters.',
            'company_name.regex' => 'Company Name can only contain letters, numbers, and spaces.',

            'status.required' => 'Please select a Status.',
            'status.in' => 'Invalid Status selected.',
        ]);

        try {
            $password = 'Brisk@123';
            $customer = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'company_name' => $request->company_name,
                'status' => $request->status,
                'password' => Hash::make($password),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id()
            ]);
            //dd($customer);
            $customer->assignRole($request->role);
            //Mail::to($customer->email)->send(new CustomerCredentialsMail($customer, $password));
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if ( !auth()->user()->can('edit customer')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $decryptedId = Crypt::decryptString($id);
            $title      = __('Customer') . ' : ' .  __('Edit');
            $customer = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->select('users.*', 'roles.id as role_id', 'roles.name as role_name')
                ->where('users.id', $decryptedId)->first();

            if ( empty( $customer ) ) {
                return redirect()->route('customer.index')->with('success', 'Customer not found.');
            }
            $roles      = Role::where('name', '=', 'customer')->get();
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.customer.edit', compact('roles', 'customer', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $decryptedId = Crypt::decryptString($id);
        if ( !auth()->user()->can('edit customer')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'first_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'last_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($decryptedId)],
            'contact_no' => ['required', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'company_name' => ['nullable', 'max:100', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'status' => ['required', 'in:0,1'],
        ], [
            'first_name.required' => 'Please enter First Name.',
            'first_name.max' => 'First Name cannot exceed 100 characters.',
            'first_name.regex' => 'First Name can only contain letters, numbers, and spaces.',

            'last_name.required' => 'Please enter Last Name.',
            'last_name.max' => 'Last Name cannot exceed 100 characters.',
            'last_name.regex' => 'Last Name can only contain letters, numbers, and spaces.',

            'email.required' => 'Please enter Email.',
            'email.email' => 'Please enter a valid email.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'email.unique' => 'This email is already in use.',

            'contact_no.required' => 'Please enter Contact Number.',
            'contact_no.max' => 'Contact Number cannot exceed 20 digits.',
            'contact_no.regex' => 'Contact Number can only contain digits, +, - and spaces.',

            'company_name.max' => 'Company Name cannot exceed 100 characters.',
            'company_name.regex' => 'Company Name can only contain letters, numbers, and spaces.',

            'status.required' => 'Please select a Status.',
            'status.in' => 'Invalid Status selected.',
        ]);
        try {

            $tmpUser = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'roles.id as role_id', 'roles.name as role_name')
            ->where('users.id', $decryptedId)->first();

            $customer = User::findOrFail($decryptedId);

            $customer->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'company_name' => $request->company_name,
                'status' => $request->status,
                'updated_by' => auth()->id()
            ]);
            if($tmpUser->role_name != $request->role ) {
                $customer->roles()->detach();
                $customer->assignRole($request->role);
            }
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($encryptedId)
    {
        try {
            $id = Crypt::decryptString($encryptedId);
            $customer = User::findOrFail($id);
            $customer->delete();
            $status     = true;
            $message    = "Customer deleted successfully.";
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }

    public function pocs($customer)
    {
        // Example: Users with company_id = customer->id
        $pocs = User::where('company_id', $customer)
            ->select('id', 'first_name', 'email')
            ->orderBy('first_name')
            ->get()
            ->map(fn($u) => ['id' => $u->id, 'name' => $u->first_name, 'email' => $u->email]);

        return response()->json($pocs);
    }
}
