<?php
namespace App\DataTables;

use App\Models\QueryStatus;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class QueryStatusDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('status', fn ($row) => $row->status ? 'Active' : 'Inactive')
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
                $actions = '';
                if (auth()->user()->can('edit query status')) {
                    $actions .= '<a href="' . route('query-statuses.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                }
                if (auth()->user()->can('delete query status')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-query-status ms-2"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(QueryStatus $model)
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('query-statuses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'name',    'name' => 'name',    'title' => 'Name'],
            ['data' => 'status',  'name' => 'status',  'title' => 'Status'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
