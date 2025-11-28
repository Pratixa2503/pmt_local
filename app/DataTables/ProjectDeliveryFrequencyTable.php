<?php

namespace App\DataTables;

use App\Models\ProjectDeliveryFrequency;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class ProjectDeliveryFrequencyTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<ProjectPriority> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query)
    {
       return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($type) {
                $encrypted = Crypt::encryptString($type->id);
                $actions = '';
                if (auth()->user()->can('edit delivery frequencies')) {
                    $actions .= '<a href="' . route('project-delivery-frequencies.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete delivery frequencies')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-project-frequency" data-id="' . $encrypted . '" class="delete-project-type"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<ProjectPriority>
     */
    public function query(ProjectDeliveryFrequency $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('project-types-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    /**
     * Get the dataTable columns definition.
     */
    protected function getColumns()
    {
        return [
            ['data' => 'name',     'name' => 'name',     'title' => 'Project Type'],
            ['data' => 'status',   'name' => 'status',   'title' => 'Status'],
            ['data' => 'actions',  'name' => 'actions',  'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }

}
