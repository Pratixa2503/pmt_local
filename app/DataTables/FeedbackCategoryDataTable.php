<?php

namespace App\DataTables;

use App\Models\FeedbackCategory;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class FeedbackCategoryDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('status', function ($row) {
                return $row->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
                $html = '';

                if (auth()->user()?->can('edit feedback category')) {
                    $html .= '<a href="'.route('feedback-categories.edit', $encrypted).'" class="me-2">
                                <i class="fa-solid fa-pen-to-square" title="Edit"></i>
                              </a>';
                }
                if (auth()->user()?->can('delete feedback category')) {
                    $html .= '<a href="javascript:void(0)" data-id="'.$encrypted.'" class="delete-feedback-category">
                                <i class="fa-solid fa-trash" title="Delete"></i>
                              </a>';
                }
                return $html;
            })
            ->rawColumns(['actions']);
    }

    public function query(FeedbackCategory $model)
    {
        return $model->newQuery()->orderByDesc('id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('feedback-categories-table')
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
