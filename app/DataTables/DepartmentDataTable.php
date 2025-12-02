<?php
namespace App\DataTables;

use App\Models\Department;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class DepartmentDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($department) {
                $encrypted = Crypt::encryptString($department->id);
                $actions = '';
                if (auth()->user()->can('edit department')) {
                    $actions .= '<a href="' . route('departments.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete department')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-department" data-id="' . $encrypted . '" class="delete-department"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(Department $model)
    {
        // LEFT JOIN to expose industry vertical name
        return $model->newQuery()
            ->leftJoin('industry_verticals as iv', 'iv.id', '=', 'departments.industry_verticals_id')
            ->select([
                'departments.*',
                'iv.name as industry_vertical', // <-- alias used by DataTable
            ])
            ->orderBy('departments.id', 'DESC');
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
            ['data' => 'name',               'name' => 'departments.name',      'title' => 'Department Name'],
            ['data' => 'industry_vertical',  'name' => 'iv.name',               'title' => 'Industry Vertical'], // NEW
            ['data' => 'status',             'name' => 'departments.status',    'title' => 'Status'],
            ['data' => 'actions',            'name' => 'actions',               'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
