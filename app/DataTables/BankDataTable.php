<?php
namespace App\DataTables;

use App\Models\Bank;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;
class BankDataTable extends DataTable
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
            ->editColumn('status', function ($bank) {
                return $bank->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($bank) {
                $encrypted = Crypt::encryptString($bank->id);
                $actions = '';
                if (auth()->user()->can('edit bank')) {
                    $actions .= '<a href="' . route('banks.edit', $encrypted ) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                }
                if (auth()->user()->can('delete bank')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-bank"><i class="fa-solid fa-trash" title="Delete"></i></a>';
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
    public function query(Bank $model)
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
            ->setTableId('banks-table')
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
          
            ['data' => 'account_no',    'name' => 'account_no',    'title' => 'Account No'],
            ['data' => 'ifsc_code',     'name' => 'ifsc_code',     'title' => 'IFSC Code'],
            ['data' => 'status',        'name' => 'status',        'title' => 'Status'],
            ['data' => 'actions',       'name' => 'actions',       'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}