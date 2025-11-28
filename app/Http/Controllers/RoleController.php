<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\DataTables\RolesDataTable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Crypt; 

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( RolesDataTable $dataTable )
    {
        if ( !auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if ( !auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $title      = __('Role') . ' : ' .  __('Add');
            $permissions = Permission::all();
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.roles.create', compact('permissions', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ( !auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|min:1',
        ], [
            'name.required' => 'Please enter a Role Name.',
            'name.unique'   => 'This Role Name already exists.',
            'permissions.required' => 'Please Select Permissions.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.min' => 'Please select at least one permission.',
        ]);

        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $permissionIds = $request->permissions;
            $permissions = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

            // Sync permissions using names
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
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
    public function edit($id)
    {
        
        if ( !auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $title      = __('Role') . ' : ' .  __('Edit');
            $decryptedId = Crypt::decryptString($id);
            $role = Role::where('id',$decryptedId)->first();
            $permissions = Permission::all();
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.roles.edit', compact('permissions', 'role', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ( !auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized action.');
        }
        $decryptedId = Crypt::decryptString($id);
        $request->validate([
            'name' => 'required|unique:roles,name,' . $decryptedId,
            'permissions' => 'required|array|min:1',
        ], [
            'name.required' => 'Please Enter Role Name.',
            'name.unique'   => 'This Role Name already exists.',
            'permissions.required' => 'Please Select Permissions.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.min' => 'Please select at least one permission.',
        ]);
        $role = Role::where('id',$decryptedId)->first();
        $role->update(['name' => $request->name]);
        if ($request->has('permissions')) {
            $permissionIds = $request->permissions;
            $permissions = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();

            // Sync permissions using names
            $role->syncPermissions($permissions);
        }
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        $status     = true;
        $message    = "Role deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
    }
}
