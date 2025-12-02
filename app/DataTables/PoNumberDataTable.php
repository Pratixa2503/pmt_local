<?php

namespace App\DataTables;

use App\Models\PoNumber;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class PoNumberDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('customer', fn($r) => e($r->customer?->name ?? '-'))
            ->addColumn('project', fn($r) => e($r->project?->project_name ?? '-'))
            ->addColumn('sub_project', fn($r) => e($r->subProject?->project_name ?? '-'))
            ->editColumn('start_date', fn($r) => optional($r->start_date)->format('Y-m-d'))
            ->editColumn('end_date',   fn($r) => optional($r->end_date)->format('Y-m-d'))
            ->editColumn('status', fn($r) => $r->status
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>')
            ->addColumn('actions', function ($r) {
                $enc = Crypt::encryptString($r->id);
                $btns = '';
                if (auth()->user()->can('edit po')) {
                    $btns .= '<a href="'.route('po-numbers.edit', $enc).'"><i class="fa-solid fa-pen-to-square"></i></a>';
                }
                if (auth()->user()->can('delete po')) {
                    $btns .= '<a href="javascript:void(0)" class="btn-po-delete" data-id="'.$enc.'"><i class="fa-solid fa-trash"></i></a>';
                }
                return $btns ?: '-';
            })
            ->rawColumns(['status', 'actions']);
    }

    public function query(PoNumber $model)
    {
        return $model->newQuery()
            ->with(['customer:id,name','project:id,project_name','subProject:id,project_name'])
            ->orderByDesc('id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('po-numbers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->dom('Bfrtip')
            ->buttons(['excel','csv','print','reset','reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'id',           'name' => 'id',           'title' => '#'],
            ['data' => 'customer',     'name' => 'customer.name', 'title' => 'Customer'],
            ['data' => 'project',      'name' => 'project.project_name', 'title' => 'Project'],
            ['data' => 'sub_project',  'name' => 'subProject.project_name', 'title' => 'Sub Project'],
            ['data' => 'po_number',    'name' => 'po_number',    'title' => 'PO Number'],
            ['data' => 'start_date',   'name' => 'start_date',   'title' => 'Start'],
            ['data' => 'end_date',     'name' => 'end_date',     'title' => 'End'],
            ['data' => 'status',       'name' => 'status',       'title' => 'Status', 'orderable' => false, 'searchable' => false],
            ['data' => 'actions',      'name' => 'actions',      'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
