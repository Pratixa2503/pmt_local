<?php
namespace App\DataTables;

use App\Models\IndustryVertical;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class IndustryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
           
            ->addColumn('actions', function ($department) {
                $encrypted = Crypt::encryptString($department->id);
                $actions = '';
                if (auth()->user()->can('edit industry vertical')) {
                    $actions .= '<a href="' . route('industry-verticals.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete industry vertical')) {
                    $actions .= '<a href="javascript:void(0)" id="industry-vertical" data-id="' . $encrypted . '" class="delete-department"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(IndustryVertical $model)
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('departments-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'name',    'name' => 'name',    'title' => 'Department Name'],
            ['data' => 'status',  'name' => 'status',  'title' => 'Status'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }

}