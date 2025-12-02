<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            // A tiny control column to expand/collapse team members
            ->addColumn('expand', function ($user) {
                return '<a href="javascript:void(0)" class="details-control" data-id="' . e(Crypt::encryptString($user->id)) . '" title="Show team">'
                     . '<i class="fa-solid fa-users"></i>'
                     . '</a>';
            })
            ->addColumn('name', fn($user) => $user->first_name . ' ' . $user->last_name)
            ->addColumn('role_name', fn($user) => ucwords($user->role_names_string))
            ->editColumn('status', fn($user) => $user->status ? 'Active' : 'Inactive')
            ->addColumn('team_count', function ($user) {
                // quick count of direct reports
                return $user->team_members_count ?? 0;
            })
            ->addColumn('actions', function ($user) {
                // Hide edit and delete for current user's own account
                if ($user->id === Auth::id()) {
                    return '';
                }
                
                $encrypted = Crypt::encryptString($user->id);
                $actions = '';
                if (auth()->user()->can('edit user')) {
                    $actions .= '<a href="' . route('users.edit', $encrypted) . '"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a> ';
                }
                if (auth()->user()->can('delete user')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '" id="delete-user"><i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions;
            })
            ->rawColumns(['expand', 'actions', 'role_name']);
    }

    /**
     * Source query: ONLY Project Managers, exclude self & restricted roles.
     *
     * Assumes users have a role named "project manager"
     * and team members reference their PM via users.project_manager_id
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $user = Auth::user();
        $excludedRoles = ['customer'];
        if (!$user->hasRole('super admin')) {
            $excludedRoles[] = 'super admin';
        }

        // only PMs
        $query = User::with('roles')
            ->withCount(['teamMembers' => function ($q) { // define relation below in User model
                // no extra filtering here; counts direct reports
            }])
            ->whereHas('assign_roles', function ($q) use ($excludedRoles) {
                $q->whereNotIn('name', $excludedRoles);
            })
            ->whereHas('assign_roles', function ($q) {
                $q->where('name', 'project manager');
            });

        // If current user is a project manager (and not super admin), show only their own account
        if ($user->hasRole('project manager') && !$user->hasRole('super admin')) {
            $query->where('id', Auth::id());
        } else {
            // Otherwise, exclude self and show all other PMs
            $query->where('id', '!=', Auth::id());
        }

        $query->orderBy('id', 'DESC');

        return $query;
    }

    /**
     * HTML builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    /**
     * Columns
     */
    protected function getColumns()
    {
        return [
            // small expand/collapse control column
            ['data' => 'expand',      'name' => 'expand',      'title' => '',           'orderable' => false, 'searchable' => false, 'width' => '30px'],
            ['data' => 'id',          'name' => 'id',          'title' => 'ID'],
            ['data' => 'first_name',  'name' => 'first_name',  'title' => 'First Name'],
            ['data' => 'last_name',   'name' => 'last_name',   'title' => 'Last Name'],
            ['data' => 'email',       'name' => 'email',       'title' => 'Email'],
            ['data' => 'contact_no',  'name' => 'contact_no',  'title' => 'Contact No'],
            ['data' => 'role_name',   'name' => 'roles.name',  'title' => 'Role'],
            ['data' => 'team_count',  'name' => 'team_count',  'title' => 'Team Members'],
            ['data' => 'status',      'name' => 'status',      'title' => 'Status'],
            ['data' => 'actions',     'name' => 'actions',     'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }
}
