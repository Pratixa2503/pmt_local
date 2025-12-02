<?php

namespace App\DataTables;

use App\Models\ProjectCategory;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class ProjectCategoryDataTable extends DataTable
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
            ->editColumn('status', function ($category) {
                return $category->status ? 'Active' : 'Inactive';
            })
            ->addColumn('actions', function ($category) {
                $encrypted = Crypt::encryptString($category->id);
                $actions = '';
                if (auth()->user()->can('edit project category')) {
                    $actions .= '<a href="' . route('project-categories.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                }
                if (auth()->user()->can('delete project category')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-project-category"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\ProjectCategory $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProjectCategory $model)
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
            ->setTableId('project-categories-table')
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
            ['data' => 'name',    'name' => 'name',    'title' => 'Category Name'],
            ['data' => 'status',  'name' => 'status',  'title' => 'Status'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
