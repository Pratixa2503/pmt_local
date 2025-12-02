<?php
namespace App\DataTables;

use App\Models\ModeOfDelivery;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;
class ModeOfDeliveryTable extends DataTable
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
            ->addColumn('actions', function ($bank) {
                $encrypted = Crypt::encryptString($bank->id);
                $actions = '';
                if (auth()->user()->can('edit mode of delivery')) {
                    $actions .= '<a href="' . route('mode-of-delivery.edit', $encrypted ) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                }
                if (auth()->user()->can('delete mode of delivery')) {
                    $actions .= '<a href="javascript:void(0)"  id="delete-mode-of-delivery" data-id="' . $encrypted . '" class="delete-bank"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\Bank $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ModeOfDelivery $model)
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
            ->setTableId('modeDelivery-table')
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
            ['data' => 'bank_name',     'name' => 'bank_name',     'title' => 'Bank Name'],
            ['data' => 'branch_name',   'name' => 'branch_name',   'title' => 'Branch Name'],
            ['data' => 'account_no',    'name' => 'account_no',    'title' => 'Account No'],
            ['data' => 'ifsc_code',     'name' => 'ifsc_code',     'title' => 'IFSC Code'],
            ['data' => 'status',        'name' => 'status',        'title' => 'Status'],
            ['data' => 'actions',       'name' => 'actions',       'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}