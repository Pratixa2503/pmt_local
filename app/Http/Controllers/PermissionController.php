<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\DataTables\PermissionDataTable;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Crypt;
class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PermissionDataTable $dataTable)
    {
        if ( !auth()->user()->hasRole('super admin')) {
            abort(403, 'Unauthorized action.');
        }
        return $dataTable->render('content.permissions.index');
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
            $title      = __('Permission') . ' : ' .  __('Add');
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.permissions.create', compact('title'));
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
            'name' => 'required|unique:permissions,name',
        ], [
            'name.required' => 'Please Enter Permission Name.',
            'name.unique'   => 'This Permission Name already exists.',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
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
            $decryptedId = Crypt::decryptString($id);
            $permission = Permission::where('id',$decryptedId)->first();
            $title      = __('Permission') . ' : ' .  __('Edit');
        } catch (Exception $exception) {
            $status   = false;
            $message  = $exception->getMessage();
            return back()->withError($message)->withInput();
        }
        return view('content.permissions.edit', compact('permission', 'title'));
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
            'name' => 'required|unique:permissions,name,' . $decryptedId,
        ], [
            'name.required' => 'Please Enter Permission Name.',
            'name.unique'   => 'This Permission Name already exists.',
        ]);
        $permission = Permission::where('id',$decryptedId)->first();
        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $decryptedId = Crypt::decryptString($request->id);
        Permission::where('id', $decryptedId)->delete();

        $status     = true;
        $message    = "Permission deleted successfully.";
        return response()->json([
            'status'  => $status,
            'message' => $message,
        ]);
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
