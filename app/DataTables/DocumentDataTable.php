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
        $req = $this->request();
        
        return $model->newQuery()
            ->with([
                'industryVertical', 'department', 'customer',
            ])
            ->when($req->get('customer_id'), function ($q, $v) {
                return $q->where('customer_id', $v);
            })
            ->orderBy('id', 'DESC');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('document-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'contact_no',            'name' => 'documents.contact_no',            'title' => 'Contact No','orderable' => false, 'searchable' => true],
            ['data' => 'description',      'name' => 'description',       'title' => 'Description'],
            ['data' => 'status',           'name' => 'status',           'title' => 'Status'],
            ['data' => 'actions',               'name' => 'actions',                    'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
