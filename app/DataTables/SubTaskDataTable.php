<?php

namespace App\DataTables;

use App\Models\SubTask;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class SubTaskDataTable extends DataTable
{
    /**
     * Build DataTable.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('main_task', function (SubTask $subTask) {
                return optional($subTask->mainTask)->name ?: '-';
            })
            ->editColumn('task_type', function (SubTask $subTask) {
                // 1 = Production, 2 = Non-Production
                return $subTask->task_type === 1 ? 'Production' : 'Non-Production';
            })
            ->editColumn('benchmarked_time', function (SubTask $subTask) {
                return $subTask->benchmarked_time ?: 'NA';
            })
            ->editColumn('status', function (SubTask $subTask) {
                // badge-like output
                return $subTask->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>';
            })
            ->addColumn('actions', function (SubTask $subTask) {
                $encrypted = Crypt::encryptString($subTask->id);
                $actions = '';

                if (auth()->user()->can('edit task')) {
                    $actions .= '<a href="' . route('subtasks.edit', $encrypted) . '"
                        class="mx-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                }
                if (auth()->user()->can('delete task')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '"
                        class="delete-subtask mx-1" title="Delete"><i class="fa-solid fa-trash"></i></a>';
                }

                return $actions ?: '-';
            })
            ->rawColumns(['status', 'actions']); // allow HTML in these columns
    }

    /**
     * Query source.
     *
     * @param \App\Models\SubTask $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SubTask $model)
    {
        return $model->newQuery()
            ->with('mainTask:id,name')   // eager load to avoid N+1
            ->orderBy('id', 'DESC');
    }

    /**
     * HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('subtasks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    /**
     * Columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ['data' => 'id',                'name' => 'id',                       'title' => '#'],
            ['data' => 'main_task',         'name' => 'mainTask.name',            'title' => 'Main Task'],
            ['data' => 'name',              'name' => 'name',                     'title' => 'Sub-Task Name'],
            ['data' => 'task_type',         'name' => 'task_type',                'title' => 'Task Type'],
            ['data' => 'benchmarked_time',  'name' => 'benchmarked_time',         'title' => 'Benchmarked Time'],
            ['data' => 'status',            'name' => 'status',                   'title' => 'Status', 'orderable' => false, 'searchable' => false],
            ['data' => 'actions',           'name' => 'actions',                  'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
