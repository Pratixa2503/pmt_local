<?php

namespace App\DataTables;

use App\Models\Document;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class DocumentDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
           ->addColumn('contract_start_date', function ($row) {
            return $row->contract_start_date 
                ? \Carbon\Carbon::parse($row->contract_start_date)->format('Y-m-d') 
                : '-';
            })
            ->addColumn('contract_end_date', function ($row) {
                return $row->contract_end_date 
                    ? \Carbon\Carbon::parse($row->contract_end_date)->format('Y-m-d') 
                    : '-';
            })
            ->addColumn('contact_no', function ($row) {
            ->editColumn('contact_no', function ($row) {
                return $row->contact_no ?? '-';
            })
            ->addColumn('customer', function ($row) {
                return $row->customer->name ?? '-';
            })
            ->addColumn('department', function ($row) {
                return $row->department->name ?? '-';
            })
            ->addColumn('industry', function ($row) {
                return $row->industryVertical->name ?? '-';
            })
            ->addColumn('description', function ($row) {
                return $row->description ?? '-';
            })
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
                $actions = '';

                if (auth()->user()->can('view document')) {
                    $actions .= '<a href="' . route('document.show', $encrypted) . '"><i class="fa-regular fa-eye" title="View"></i></a> ';
                }

                if (auth()->user()->can('edit document')) {
                    $actions .= '<a href="' . route('document.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }

                // if (!$row->is_approved && auth()->user()->can('approve pricing master')) {
                //     $actions .= ' <a href="' . route('pricing-master.approve', $encrypted) . '"><i class="fa-solid fa-circle-check" title="Approve"></i></a>';
                // }

                if (auth()->user()->can('delete document')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-document-master" data-id="' . $encrypted . '" class="delete-document"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }

                


                return $actions;
            })
            ->rawColumns(['is_approved', 'actions']);
    }

    public function query(Document $model)
    {
        return $model->newQuery()->with([
            'industryVertical', 'department', 'customer',
        ])->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('pricing-master-table')
            ->setTableId('documents-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'pricing_type',     'name' => 'pricingType.name',      'title' => 'Pricing Type'],
            ['data' => 'industry_vertical',     'name' => 'industryVertical.name',      'title' => 'Industry Vertical'],
            ['data' => 'department',            'name' => 'department.name',            'title' => 'Department'],
            ['data' => 'service_offering',      'name' => 'serviceOffering.name',       'title' => 'Service Offering'],
            ['data' => 'unit_of_measurement',   'name' => 'unitOfMeasurement.name',     'title' => 'Unit of Measurement'],
            ['data' => 'currency',              'name' => 'currency.name',              'title' => 'Currency'],
            ['data' => 'rate',                  'name' => 'rate',                       'title' => 'Rate'],
            ['data' => 'description',           'name' => 'description.name',           'title' => 'Description'],
            ['data' => 'is_approved',           'name' => 'is_approved',                'title' => 'Approval Status'],
            ['data' => 'contact_no',            'name' => 'documents.contact_no',            'title' => 'Contact No','orderable' => false, 'searchable' => true],
            ['data' => 'description',      'name' => 'description',       'title' => 'Description'],
            ['data' => 'status',           'name' => 'status',           'title' => 'Status'],
            ['data' => 'actions',               'name' => 'actions',                    'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
