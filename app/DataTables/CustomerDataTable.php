<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class CustomerDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('name', function ($user) {
                return $user->first_name . ' ' . $user->last_name;
            })
            ->addColumn('role_name', function ($row) {
                return ($row->role_name == '') ? '': ucwords($row->role_name);
            })
            ->editColumn('status', function ($user) {
                return $user->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($user) {
                $encrypted = Crypt::encryptString($user->id);
                $actions = '';
                
                // View Contract action
                if (auth()->user()->can('view document') && $user->company_id) {
                    $actions .= '
                        <a href="' . route('document.index', ['customer_id' => $user->company_id]) . '" title="View Contract"><i class="fa-solid fa-file-contract"></i></a>
                    ';
                }
                
                if (auth()->user()->can('edit customer')) {
                    $actions .= '
                        <a href="' . route('customers.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>
                    ';
                }
                if (auth()->user()->can('delete customer')) {
                    $actions .= '
                        <a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-customer"><i class="fa-solid fa-trash" title="Delete"></i></a>
                    ';
                }
                return $actions;
            })
            ->rawColumns(['actions', 'role_name']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $query = $model->newQuery()
        ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
        ->select('users.*', 'roles.name as role_name')
        ->where('users.id', '!=', Auth::id())
        ->where('roles.name', '=', 'customer')
        ->orderBy('users.id', 'DESC');

        return $query;
    }

    /**
     * HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            // ['data' => 'id',         'name' => 'id',         'title' => 'ID'],
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'First Name'],
            ['data' => 'last_name',  'name' => 'last_name',  'title' => 'Last Name'],
            ['data' => 'email',      'name' => 'email',      'title' => 'Email'],
            ['data' => 'contact_no', 'name' => 'contact_no', 'title' => 'Contact No'],
            ['data' => 'role_name',  'name' => 'roles.name', 'title' => 'Role'],
            ['data' => 'status',     'name' => 'status',     'title' => 'Status'],
            ['data' => 'actions',    'name' => 'actions',    'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
