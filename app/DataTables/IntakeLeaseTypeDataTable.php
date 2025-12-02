<?php

namespace App\DataTables;

use App\Models\IntakeLeaseType;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class IntakeLeaseTypeDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('actions', function ($row) {
                $enc = Crypt::encryptString($row->id);
                return view('content.intake-lease-types.actions', compact('enc'))->render();
            })
            ->rawColumns(['actions']);
    }

    public function query(IntakeLeaseType $model)
    {
        return $model->newQuery()->orderByDesc('id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('intake-lease-types-table')
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
            ['data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false, 'width' => '140px'],
        ];
    }
}
