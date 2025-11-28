<?php

namespace App\DataTables;

use App\Models\PricingMaster;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Crypt;

class PricingMasterDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('pricing_type',function($row){
<<<<<<< HEAD
                 return $row->pricing_type ?? '-';
=======
                return $row->pricing_type === 'static'
                ? 'Standard'
                : ($row->pricing_type === 'custom' ? 'Custom' : '-');

>>>>>>> 9d9ed85b (for cleaner setup)
            })
            ->addColumn('name',function($row){
                 return $row->name ?? '-';
            })
            ->addColumn('industry_vertical', function ($row) {
                return $row->industryVertical->name ?? '-';
            })
            ->addColumn('department', function ($row) {
                return $row->department->name ?? '-';
            })
            ->addColumn('service_offering', function ($row) {
                return $row->serviceOffering->name ?? '-';
            })
            ->addColumn('unit_of_measurement', function ($row) {
                return $row->unitOfMeasurement->name ?? '-';
            })
            ->addColumn('currency', function ($row) {
                return $row->currency->name ?? '-';
            })
            ->addColumn('rate', function ($row) {
                return $row->rate;
            })
            ->addColumn('description', function ($row) {
                return $row->description->name ?? '-';
            })
            ->addColumn('is_approved', function ($row) {
               return $row->approval_status == "approved"
                ? '<span class="badge bg-success">Approved</span>'
                : ($row->approval_status == "rejected"
                    ? '<span class="badge bg-danger">Rejected</span>'
                    : '<span class="badge bg-warning">Pending</span>');
                        })
            ->addColumn('actions', function ($row) {
                $encrypted = Crypt::encryptString($row->id);
                $actions = '';

                if (auth()->user()->can('view pricing master')) {
                    $actions .= '<a href="' . route('pricing-master.show', $encrypted) . '"><i class="fa-regular fa-eye" title="View"></i></a> ';
                }

                if (auth()->user()->can('edit pricing master')) {
                    $actions .= '<a href="' . route('pricing-master.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }

                // if (!$row->is_approved && auth()->user()->can('approve pricing master')) {
                //     $actions .= ' <a href="' . route('pricing-master.approve', $encrypted) . '"><i class="fa-solid fa-circle-check" title="Approve"></i></a>';
                // }

                if (auth()->user()->can('delete pricing master')) {
                    $actions .= '<a href="javascript:void(0)" id="delete-pricing-master" data-id="' . $encrypted . '" class="delete-pricing-master"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }

                

                return $actions;
            })
            ->rawColumns(['is_approved', 'actions']);
    }

    public function query(PricingMaster $model)
    {
        return $model->newQuery()->with([
            'industryVertical', 'department', 'serviceOffering',
            'unitOfMeasurement', 'currency', 'description'
        ])->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('pricing-master-table')
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
            ['data' => 'actions',               'name' => 'actions',                    'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
