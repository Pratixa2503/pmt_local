<?php
namespace App\DataTables;

use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;
class PermissionDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param \Illuminate\Database\Eloquent\Builder|QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($permission) {
                $encrypted = Crypt::encryptString($permission->id);
                return '
                    <a href="' . route('permissions.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>
                    <a href="javascript:void(0)" data-id="'.$encrypted.'" id="delete-permission"><i class="fa-solid fa-trash"  title="Delete"></i></a>
                ';
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\Permission $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Permission $model)
    {
        return $model->orderBy('permissions.id', 'DESC')->newQuery();
    }

    /**
     * Optional method if you want to specify your HTML structure for the DataTable.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('permissions-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    /**
     * Get columns configuration.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
