<?php

namespace App\DataTables;

use App\Models\IntakeStatus;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class IntakeStatusDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($row) {
                $enc = Crypt::encryptString($row->id);
                $btns = '';

                if (auth()->user()->can('edit intake status')) {
                    $btns .= '<a href="'.route('intake-statuses.edit', $enc).'" class="me-2" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                }
                if (auth()->user()->can('delete intake status')) {
                    $btns .= '<a href="javascript:void(0)" data-id="'.$enc.'" class="text-danger delete-intake-status" title="Delete"><i class="fa-solid fa-trash"></i></a>';
                }

                return $btns ?: '-';
            })
            ->rawColumns(['actions']);
    }

    public function query(IntakeStatus $model)
    {
        return $model->newQuery()->orderByDesc('id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('intake-statuses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'id',      'name' => 'id',      'title' => '#', 'width' => '80px'],
            ['data' => 'name',    'name' => 'name',    'title' => 'Name'],
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false, 'width' => '120px'],
        ];
    }
}
