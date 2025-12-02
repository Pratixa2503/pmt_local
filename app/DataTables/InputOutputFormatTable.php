<?php

namespace App\DataTables;

use App\Models\InputOutputFormat;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class InputOutputFormatTable extends DataTable
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
            ->addColumn('status', function ($row) {
                return $row->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
                $actions = '';
                if (auth()->user()->can('edit input output format')) {
                    $actions .= '<a href="' . route('input-output-formats.edit', $encrypted ) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete input output format')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-input-output-format" data-id="' . $encrypted . '" class="delete-format"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['status', 'actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\InputOutputFormat $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InputOutputFormat $model)
    {
        return $model->newQuery()->select(['id', 'name', 'status'])->orderBy('id', 'DESC');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('input-output-format-table')
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
            ['data' => 'name',    'name' => 'name',    'title' => 'Format Name'],
            ['data' => 'status',  'name' => 'status',  'title' => 'Status'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
