<?php
namespace App\DataTables;

use App\Models\ProjectType;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class ProjectTypeDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('status', function ($type) {
                return $type->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($type) {
                $encrypted = Crypt::encryptString($type->id);
                $actions = '';
                if (auth()->user()->can('edit project type')) {
                    $actions .= '<a href="' . route('project-types.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete project type')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-projecttype" data-id="' . $encrypted . '" class="delete-project-type"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(ProjectType $model)
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }

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

    protected function getColumns()
    {
        return [
            ['data' => 'name',     'name' => 'name',     'title' => 'Project Type'],
            ['data' => 'status',   'name' => 'status',   'title' => 'Status'],
            ['data' => 'actions',  'name' => 'actions',  'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
