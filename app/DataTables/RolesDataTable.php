<?php
namespace App\DataTables;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class RolesDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function ajax(): \Illuminate\Http\JsonResponse
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('name', function($role) {
                return ucwords($role->name);
            })
            ->addColumn('permissions', function($role) {
                return implode(', ', $role->permissions->pluck('name')->toArray());
            })
            ->addColumn('actions', function($role) {
                $encrypted = Crypt::encryptString($role->id);
                if ( $role->name != "super admin") {
                    return '
                    <a href="'.route('roles.edit', $encrypted).'"><i class="fa-solid fa-pen-to-square"  title="Edit"></i></a>';
                    // '<a href="javascript:void(0)" data-id="'.$role->id.'" id="delete-roles"><i class="fa-solid fa-trash"  title="Delete"></i></a>
                    // ';
                } else {
                    return '
                    <a href="'.route('roles.edit', $encrypted).'"><i class="fa-solid fa-pen-to-square"  title="Edit"></i></a>
                    ';
                }
            })
            ->rawColumns(['actions']) // Enable HTML for the actions column
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Role::with('permissions')->select('roles.*')->orderBy('roles.id', 'DESC');

        if (!Auth::user()->hasRole('super admin')) {
            $query->where('roles.name', '!=', 'super admin');
        }
        return $query;
    }

    /**
     * Optional method if you want to use the DataTable class.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('roles-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip') // Optional: for buttons like print, export
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
           ;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'permissions', 'name' => 'permissions', 'title' => 'Permissions'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
