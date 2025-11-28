<?php
namespace App\DataTables;

use App\Models\ServiceOffering;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class ServiceOfferingDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
<<<<<<< HEAD
            ->addColumn('actions', function ($type) {
                $encrypted = Crypt::encryptString($type->id);
=======
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
>>>>>>> 9d9ed85b (for cleaner setup)
                $actions = '';
                if (auth()->user()->can('edit service offering')) {
                    $actions .= '<a href="' . route('service-offerings.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete service offering')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-service-offering" data-id="' . $encrypted . '" class="delete-project-type"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(ServiceOffering $model)
    {
<<<<<<< HEAD
        return $model->newQuery()->orderBy('id', 'DESC');
=======
        return $model->newQuery()
            ->leftJoin('departments as d', 'd.id', '=', 'service_offerings.department_id')
            ->leftJoin('industry_verticals as iv', 'iv.id', '=', 'd.industry_verticals_id')
            ->select([
                'service_offerings.*',
                'd.name as department_name',
                'iv.name as industry_vertical',
            ])
            ->orderBy('service_offerings.id', 'DESC');
>>>>>>> 9d9ed85b (for cleaner setup)
    }

    public function html()
    {
        return $this->builder()
<<<<<<< HEAD
            ->setTableId('project-status-table')
=======
            ->setTableId('input-output-format-table') // match Blade
>>>>>>> 9d9ed85b (for cleaner setup)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
<<<<<<< HEAD
            ['data' => 'name',     'name' => 'name',     'title' => 'Project Type'],
            ['data' => 'status',   'name' => 'status',   'title' => 'Status'],
            ['data' => 'actions',  'name' => 'actions',  'title' => 'Actions', 'orderable' => false, 'searchable' => false],
=======
            ['data' => 'name',              'name' => 'service_offerings.name', 'title' => 'Service Offering'],
            ['data' => 'department_name',   'name' => 'd.name',                 'title' => 'Department'],
            ['data' => 'industry_vertical', 'name' => 'iv.name',                'title' => 'Industry Vertical'],
            ['data' => 'status',            'name' => 'service_offerings.status','title' => 'Status'],
            ['data' => 'actions',           'name' => 'actions',                'title' => 'Actions', 'orderable' => false, 'searchable' => false],
>>>>>>> 9d9ed85b (for cleaner setup)
        ];
    }
}
