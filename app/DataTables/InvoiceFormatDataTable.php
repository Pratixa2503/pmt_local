<?php

namespace App\DataTables;

use App\Models\InvoiceFormat;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class InvoiceFormatDataTable extends DataTable
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
            ->editColumn('status', function ($format) {
                return $format->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($format) {
                $encrypted = Crypt::encryptString($format->id);
                $actions = '';
                if (auth()->user()->can('edit invoice format')) {
                    $actions .= '<a class="me-2" href="' . route('invoice-formats.edit', $encrypted ) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                }
                if (auth()->user()->can('delete invoice format')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-invoice-format"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\InvoiceFormat $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(InvoiceFormat $model)
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }

    /**
     * HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('invoice-formats-table')
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
            ['data' => 'name',          'name' => 'name',   'title' => 'Invoice Format'],
            ['data' => 'status',        'name' => 'status', 'title' => 'Status'],
            ['data' => 'actions',       'name' => 'actions','title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    } 
}
