<?php
namespace App\DataTables;

use App\Models\ProjectStatus;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class ProjectStatusTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($type) {
                $encrypted = Crypt::encryptString($type->id);
                $actions = '';
                if (auth()->user()->can('edit project status')) {
                    $actions .= '<a href="' . route('project-statuses.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete project status')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-project-status" data-id="' . $encrypted . '" class="delete-project-type"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    public function query(ProjectStatus $model)
    {
        return $model->newQuery()->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('project-status-table')
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
