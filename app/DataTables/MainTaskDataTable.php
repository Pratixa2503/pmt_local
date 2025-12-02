<?php

namespace App\DataTables;

use App\Models\MainTask;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class MainTaskDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('status', fn ($row) =>
                $row->status
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>'
            )
            // NEW: render task_type as badges
            ->editColumn('task_type', function ($row) {
                if ((int) $row->task_type === 1) {
                    return '<span class="badge bg-primary">Productive</span>';
                }
                return '<span class="badge bg-info">General</span>';
            })
            // Optional: make search for "productive"/"general" work
            ->filterColumn('task_type', function ($query, $keyword) {
                $kw = strtolower(trim($keyword));
                if (in_array($kw, ['1', 'productive'])) {
                    $query->where('task_type', 1);
                } elseif (in_array($kw, ['2', 'general'])) {
                    $query->where('task_type', 2);
                } else {
                    // allow partial search on label text too
                    $query->where(function ($q) use ($kw) {
                        if (str_contains('productive', $kw)) {
                            $q->orWhere('task_type', 1);
                        }
                        if (str_contains('general', $kw)) {
                            $q->orWhere('task_type', 2);
                        }
                    });
                }
            })
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
                $buttons = '';
                if (auth()->user()->can('edit task')) {
                    $buttons .= '<a href="'.route('maintasks.edit', $encrypted).'" class="mx-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                }
                if (auth()->user()->can('delete task')) {
                    $buttons .= '<a href="javascript:void(0)" data-id="'.$encrypted.'" class="delete-maintask mx-1" title="Delete"><i class="fa-solid fa-trash"></i></a>';
                }
                return $buttons ?: '-';
            })
            ->rawColumns(['status', 'task_type', 'actions']); // include task_type as raw
    }

    public function query(MainTask $model)
    {
        // If you prefer, you can select only needed columns:
        // return $model->newQuery()->select('id','name','status','task_type')->orderBy('id','DESC');
        return $model->newQuery()->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('maintasks-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'id',         'name' => 'id',         'title' => '#'],
            ['data' => 'name',       'name' => 'name',       'title' => 'Main Task Name'],
            // NEW: Task Type column (sortable, searchable)
            ['data' => 'task_type',  'name' => 'task_type',  'title' => 'Task Type', 'orderable' => true, 'searchable' => true],
            ['data' => 'status',     'name' => 'status',     'title' => 'Status', 'orderable' => false, 'searchable' => false],
            ['data' => 'actions',    'name' => 'actions',    'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
