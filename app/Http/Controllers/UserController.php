<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTables\UsersDataTable;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Helpers;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        if (!auth()->user()->can('view user')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create user')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $title      = __('User') . ' : ' .  __('Add');
            $user = auth()->user();
            $isProjectManager = $user->hasRole('project manager') && !$user->hasRole('super admin');
            
            // If current user is a project manager, exclude "project manager" role
            $rolesQuery = Role::whereNotIn('name', ['super admin', 'customer']);
            if ($isProjectManager) {
                $rolesQuery->where('name', '!=', 'project manager');
            }
            $roles = $rolesQuery->get();
            
            $pm_users = Helpers::getUsersByRole('project manager', ['id', 'first_name', 'last_name']);
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.users.create', compact('roles', 'title', 'pm_users', 'isProjectManager'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create user')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'first_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'last_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'contact_no' => ['required', 'min:10','max:10', 'regex:/^[0-9+\-\s]+$/'],
            'company_name' => ['nullable', 'max:100', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'status' => ['required', 'in:0,1'],
            'project_manager.' => ['integer', 'exists:users,id'],
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
            'contact_no.max' => 'Contact Number cannot exceed 10 digits.',
            'contact_no.regex' => 'Contact Number can only contain digits, +, - and spaces.',

            'company_name.max' => 'Company Name cannot exceed 100 characters.',
            'company_name.regex' => 'Company Name can only contain letters, numbers, and spaces.',

            'status.required' => 'Please select a Status.',
            'status.in' => 'Invalid Status selected.',
        ]);

        try {
            $password = 'Brisk@123';
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'company_name' => $request->company_name,
                'project_manager' => $request->project_manager,
                'status' => $request->status,
                'password' => Hash::make($password),
                'created_at' => auth()->id(),
                'updated_at' => auth()->id()
            ]);

            $user->assignRole($request->role);
            //  Mail::to($user->email)->send(new UserCredentialsMail($user, $password));

        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();

            return back()->withError($message)->withInput();
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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
        if (!auth()->user()->can('edit user')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $decryptedId = Crypt::decryptString($id);
            $title      = __('User') . ' : ' .  __('Edit');
            $user = User::with('roles')
                ->where('users.id', $decryptedId)->first();

            $pm_users = Helpers::getUsersByRole('project manager', ['id', 'first_name', 'last_name']);
            if (empty($user)) {
                return redirect()->route('users.index')->with('success', 'User not found.');
            }
            $roles = Role::whereNotIn('name', ['super admin', 'customer'])->get();
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.users.edit', compact('roles', 'user', 'title', 'pm_users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('edit user')) {
            abort(403, 'Unauthorized action.');
        }
        $decryptedId = Crypt::decryptString($id);
        $request->validate([
            'first_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'last_name' => ['required', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($decryptedId)],
            'contact_no' => ['required','min:10','max:10', 'regex:/^[0-9+\-\s]+$/'],
            'company_name' => ['nullable', 'max:100', 'regex:/^[a-zA-Z0-9\s]*$/'],
            'status' => ['required', 'in:0,1'],
            'project_manager.' => ['integer', 'exists:users,id'],
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
            'contact_no.max' => 'Contact Number cannot exceed 10 digits.',
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

            $user = User::findOrFail($decryptedId);
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'contact_no' => $request->contact_no,
                'company_name' => $request->company_name,
                'status' => $request->status,
                'project_manager' => $request->project_manager,
                'updated_at' => auth()->id()
            ]);
            if ($tmpUser->role_name != $request->role) {
                $user->roles()->detach();
                $user->assignRole($request->role);
            }
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            //dd($message);
            return back()->withError($message)->withInput();
        }
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Delete Method
     */
    public function destroy($encryptedId)
    {
        if (!auth()->user()->can('delete user')) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        try {
            // URL decode the encrypted ID in case it was encoded
            $encryptedId = urldecode($encryptedId);
            
            if (empty($encryptedId)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid user ID provided.',
                ], 422);
            }

            $id = Crypt::decryptString($encryptedId);
            $user = User::findOrFail($id);
            
            // Prevent deleting own account
            if ($user->id === auth()->id()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'You cannot delete your own account.',
                ], 422);
            }
            
            $user->delete();

            return response()->json([
                'status'  => true,
                'message' => "User deleted successfully.",
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid user ID. The payload is invalid.',
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.',
            ], 404);
        } catch (Exception $exception) {
            return response()->json([
                'status'  => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Extend the data of team
     */
    public function teamMembers(Request $request, $encryptedId)
    {
        try {
            $pmId = Crypt::decryptString($encryptedId);
        } catch (\Throwable $e) {
            return response()->json(['html' => '<div class="p-2 text-danger">Invalid ID</div>'], 422);
        }

        $team = User::with('roles')
            ->where('project_manager', $pmId)
            ->orderBy('id', 'desc')
            ->get();

        if ($team->isEmpty()) {
            return response()->json(['html' => '<div class="p-2">No team members found.</div>']);
        }

        $html  = '<div class="table-responsive"><table class="table table-sm table-striped mb-0">';
        $html .= '<thead><tr>'
            . '<th>Name</th>'
            . '<th>Email</th>'
            // REMOVED Contact column
            . '<th>Role(s)</th>'
            . '<th>Status</th>'
            . '<th style="width:120px;">Action</th>'
            . '</tr></thead><tbody>';

        foreach ($team as $u) {
            $enc = \Illuminate\Support\Facades\Crypt::encryptString($u->id);
            $name  = e(trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')));
            $roles = e(ucwords($u->role_names_string ?? ''));
            $actions = '';

            if (auth()->user()->can('edit user')) {
                $actions .= '<a href="' . route('users.edit', $enc) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
            }
            if (auth()->user()->can('delete user')) {
                $actions .= '<a href="javascript:void(0)" data-id="' . $enc . '" id="delete-user"><i class="fa-solid fa-trash" title="Delete"></i></a>';
            }

            $html .= '<tr>'
                . '<td>' . $name . '</td>'
                . '<td>' . e($u->email) . '</td>'
                . '<td>' . $roles . '</td>'
                . '<td>' . ($u->status ? 'Active' : 'Inactive') . '</td>'
                . '<td class="text-center">' . $actions . '</td>'
                . '</tr>';
        }
        $html .= '</tbody></table></div>';

        return response()->json(['html' => $html]);
    }

    public function unassigned(Request $request)
    {
        // Adjust column name if yours differs (e.g., 'project_manager_id')
        //$pmColumn = 'project_manager';

       $query = User::query()
        ->with('roles:id,name')
        ->whereNull('project_manager') // change column if different
        ->whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['project manager', 'customer','super admin']);
        })->get();
        //dd($query);

        return DataTables::of($query)
            ->addColumn('name', function ($user) {
                return e($user->first_name . ' ' . $user->last_name);
            })
            // ->addColumn('expand', function ($user) {
            //     // Use same markup you use in the Assign table for details-control
            //     return '<a href="javascript:void(0)" class="details-control" data-id="' . e(Crypt::encryptString($user->id)) . '" title="Expand"><i class="fa fa-plus-circle"></i></a>';
            // })
            ->addColumn('role_name', function ($user) {
                // Combine multiple roles if any
                return e($user->roles->pluck('name')->implode(', '));
            })
            ->addColumn('status', function ($user) {
                // Adapt to your status field (e.g., boolean/enum)
                return $user->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function ($user) {
                // Example actions — adapt to your policies/permissions
                $assignBtn = '<a href="'.route('users.edit', Crypt::encryptString($user->id)).'""><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                $deleteBtn = '<a href="javascript:void(0)" id="delete-user" data-id="'.Crypt::encryptString($user->id).'"><i class="fa fa-trash"></i></a>';
                return $assignBtn.$deleteBtn;
            })
            ->rawColumns(['status', 'actions', 'expand'])
            ->make(true);
    }
}
