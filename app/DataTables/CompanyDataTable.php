<?php

namespace App\DataTables;

use App\Models\Company;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
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
            ->addColumn('id_encrypted', fn($row) => Crypt::encryptString($row->id))
            ->addColumn('team', fn($company) => (int) ($company->team_count ?? 0))
            ->addColumn('actions', function ($company) {
                $encrypted = Crypt::encryptString($company->id);
                $html = [];

                // Add Project
                if (auth()->user()->can('create project')) {
                    $html[] = sprintf(
                        '<a href="%s" class="me-2" title="Add Project"><i class="fa-solid fa-plus"></i></a>',
                        route('projects.create', ['customer' => $encrypted])
                    );
                }

                // Add Contract
                if (auth()->user()->can('create customer')) {
                    $html[] = sprintf(
                        '<a href="%s" class="me-2" title="Add Contract"><i class="fa-solid fa-file-circle-plus"></i></a>',
                        route('document.create', ['customer' => $encrypted])
                    );
                }

                // View Document
                if (auth()->user()->can('view document')) {
                    $html[] = sprintf(
                        '<a href="%s" class="me-2" title="View Document"><i class="fa-solid fa-file-contract"></i></a>',
                        route('document.index', ['customer_id' => $company->id])
                    );
                }

                // Edit Customer
                if (auth()->user()->can('edit customer')) {
                    $html[] = sprintf(
                        '<a href="%s" class="me-2" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>',
                        route('companies.edit', $encrypted)
                    );
                }

                // Delete Customer
                if (auth()->user()->can('delete customer')) {
                    $html[] = sprintf(
                        '<a href="javascript:void(0)" data-id="%s" class="delete-company" title="Delete"><i class="fa-solid fa-trash"></i></a>',
                        e($encrypted)
                    );
                }

                return $html ? implode('', $html) : '-';
            })
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @param \App\Models\Company $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Company $model)
    {
        return $model->newQuery()
            ->select('companies.*')
            ->withCount([
                'users as team_count' => function ($q) {
                    $q->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                        ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->where('roles.name', '=', 'customer');
                }
            ])
            ->orderBy('companies.id', 'DESC');
    }

    /**
     * HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('companies-table')
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
            ['data' => 'name',       'name' => 'name',       'title' => 'Name'],
            ['data' => 'address',    'name' => 'address',    'title' => 'Address'],
            ['data' => 'location',   'name' => 'location',   'title' => 'Location'],
            ['data' => 'contact_no', 'name' => 'contact_no', 'title' => 'Contact No'],
            ['data' => 'website',    'name' => 'website',    'title' => 'Website'],
            ['data' => 'team',       'name' => 'team_count', 'title' => 'Team'],
            [
                'data'       => 'actions',
                'name'       => 'actions',
                'title'      => 'Actions',
                'orderable'  => false,
                'searchable' => false,
            ],
        ];
    }
}
