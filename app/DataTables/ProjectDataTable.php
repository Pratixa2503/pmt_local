<?php

namespace App\DataTables;

use App\Models\Project;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Services\DataTable;

class ProjectDataTable extends DataTable
{
    public function dataTable($query)
    {
<<<<<<< HEAD
        return datatables()
            ->eloquent($query)
            ->addColumn('expand', function ($p) {
=======
        $user = auth()->user();
        $shouldHideExpand = false;
        if ($user) {
            // Guard in case roles package isn't present
            if (method_exists($user, 'hasAnyRole')) {
                $shouldHideExpand = $user->hasAnyRole([
                    'abstractor','Abstractor',
                    'reviewer','Reviewer',
                    'sense check','sense_check','Sense Check / DDR',
                    'customer','Customer',
                ]);
            }
        }

        return datatables()
            ->eloquent($query)
            ->addColumn('expand', function ($p) use ($shouldHideExpand) {
                if ($shouldHideExpand) {
                    // Hide for Abstractor / Reviewer / Sense Check / Customer
                    return '';
                }
>>>>>>> 9d9ed85b (for cleaner setup)
                $encId = Crypt::encryptString($p->id);
                return '<a href="javascript:void(0)" class="details-control" data-id="' . $encId . '" title="View subprojects"><i class="fa fa-plus-circle"></i></a>';
            })
            ->editColumn('start_date', fn($p) => optional($p->start_date)->format('Y-m-d'))
            ->editColumn('end_date',   fn($p) => optional($p->end_date)->format('Y-m-d'))
            ->editColumn('status_name', fn($p) => $p->status_name ?? '-')
            ->addColumn('actions', function ($p) {
                $encrypted = Crypt::encryptString($p->id);
<<<<<<< HEAD
                $actions = '';

                if (auth()->check() && auth()->user()->can('view intake form')) {
                    $isCat1 = (int)($p->project_category ?? 0) === 1;
                    $encrypted = Crypt::encryptString($p->id);

                    // Route + Icon + Tooltip
                    if ($isCat1) {
                        // 👇 Check for super admin
                        if (auth()->user()->hasRole('super admin')) {
                            $url   = route('projects.admin.tracking', $encrypted);   // New admin-only route
                            $icon  = 'fa-solid fa-user-shield';
                            $title = 'Admin Tracking';
                        } else {
                            $url   = route('projects.tasks.track', $encrypted);     // Normal tracking page
                            $icon  = 'fa-solid fa-eye';
                            $title = 'View';
                        }
                    } else {
                        $url   = route('projects.fileView', ['parent' => $encrypted]); // Existing page for cat=2
                        $icon  = 'fa-solid fa-file-excel';
                        $title = 'View Files';
                    }

                    $actions .= '<a href="' . $url . '" class="me-2">'
                        . '<i class="' . $icon . '" title="' . e($title) . '"></i></a>';
                }
                if (auth()->check() && auth()->user()->can('create project')) {
                    $actions .= '<a href="' . route('projects.create', ['parent' => $encrypted]) . '" class="me-2">'
                        . '<i class="fa-solid fa-diagram-project" title="Add Subproject"></i></a>';
                }

                if (auth()->check() && auth()->user()->can('edit project')) {
                    $actions .= '<a href="' . route('projects.edit', $encrypted) . '" class="me-2">'
                        . '<i class="fa-solid fa-pen-to-square" title="Edit"></i></a>';
                }
                if (auth()->check() && auth()->user()->can('delete project')) {
                    $actions .= '<a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-project">'
                        . '<i class="fa-solid fa-trash" title="Delete"></i></a>';
                }
                return $actions ?: '-';
=======
                $user      = auth()->user();
                $actions   = [];

                // ----- View / Tracking buttons (visible when user can view intake form) -----
                if ($user && $user->can('view intake form')) {
                    $isCat1 = (int) ($p->project_category ?? 0) === 1;
                    $isCat2 = (int) ($p->project_category ?? 0) === 2;
                    $url = $title = $icon = null;
                    // Admin tracking for cat=1 + super admin
                    if ($user->hasRole('super admin') || $user->hasRole('project manager')) { 
                        $url   = route('projects.admin.tracking', $encrypted);
                        $title = 'Admin Tracking';
                        $icon  = 'fa-solid fa-user-shield';
                    } else {
                        if(!$user->hasRole('customer')){
                            // Normal tracker for BOTH cat=1 and cat=2
                            $url   = route('projects.tasks.track', $encrypted);
                            $title = 'Project Tracker';
                            $icon  = 'fa-solid fa-list-check';
                        }
                    }
                    if ($url) {
                    $actions[] = '<a href="' . $url . '" class="me-2" title="' . e($title) . '">
                                    <i class="' . $icon . '"></i>
                                </a>';
                    }
                    // Keep the existing File View button for cat=2
                    if ($isCat2) {
                        $url = route('projects.fileView', ['parent' => $encrypted]);
                        $actions[] = '<a href="' . $url . '" class="me-2" title="View Files">
                                        <i class="fa-solid fa-file-excel"></i>
                                    </a>';
                    }
                }

                // ----- Add Subproject -----
                if ($user && $user->can('create project')) {
                    $url = route('projects.create', ['parent' => $encrypted]);
                    $actions[] = '<a href="' . $url . '" class="me-2" title="Add Subproject">
                                    <i class="fa-solid fa-diagram-project"></i>
                                </a>';
                }

                // ----- Edit -----
                if ($user && $user->can('edit project')) {
                    $url = route('projects.edit', $encrypted);
                    $actions[] = '<a href="' . $url . '" class="me-2" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>';
                }

                // ----- Delete -----
                if ($user && $user->can('delete project')) {
                    $actions[] = '<a href="javascript:void(0)" data-id="' . $encrypted . '" class="delete-project" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </a>';
                }

                return $actions ? implode('', $actions) : '-';
>>>>>>> 9d9ed85b (for cleaner setup)
            })
            ->rawColumns(['expand', 'actions']);
    }

    public function query(Project $model)
    {
        $user = auth()->user();

        $query = $model->newQuery()
            ->leftJoin('companies as c', 'c.id', '=', 'projects.customer_id')
<<<<<<< HEAD
            ->leftJoin('project_types as pt', 'pt.id', '=', 'projects.project_type_id')
            ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
            // we’ll left join intakes so cat=1 projects without intakes aren’t dropped
            ->leftJoin('project_intakes as pi', 'pi.parent_id', '=', 'projects.id')
            ->whereNull('projects.parent_id')
=======
            //->leftJoin('project_types as pt', 'pt.id', '=', 'projects.project_type_id')
            ->leftJoin('project_statuses as ps', 'ps.id', '=', 'projects.project_status_id')
            // we’ll left join intakes so cat=1 projects without intakes aren’t dropped
            ->leftJoin('project_intakes as pi', 'pi.parent_id', '=', 'projects.id')
>>>>>>> 9d9ed85b (for cleaner setup)
            ->select([
                'projects.id',
                'projects.project_name',
                'projects.start_date',
                'projects.end_date',
                'c.name as customer_name',
<<<<<<< HEAD
                'pt.name as project_type_name',
=======
                //'pt.name as project_type_name',
>>>>>>> 9d9ed85b (for cleaner setup)
                'ps.name as status_name',
                'projects.project_category',
            ])
            ->orderByDesc('projects.id')
            ->distinct();

        if (!$user) {
            return $query;
        }
<<<<<<< HEAD

=======
        if($user->hasRole('super admin') || $user->hasRole('project manager')){
            $query->whereNull('projects.parent_id');
        }
>>>>>>> 9d9ed85b (for cleaner setup)
        if ($user->hasRole('super admin')) {
            return $query;
        }

        // Map roles that are determined via project_intakes (cat=2)
        $roleColumns = [
            'abstractor'  => 'pi.abstractor_id',
            'reviewer'    => 'pi.reviewer_id',
            'sense check' => 'pi.sense_check_ddr_id',
            'customer'    => 'pi.property_manager_id',
        ];

        $columnsToFilter = [];
        foreach ($roleColumns as $role => $col) {
            if ($user->hasRole($role)) {
                $columnsToFilter[] = $col;
            }
        }

        $query->where(function ($root) use ($user, $columnsToFilter) {

            // ---------- Category 2 (LA) — keep your existing logic ----------
            $root->orWhere(function ($cat2) use ($user, $columnsToFilter) {
                $cat2->where('projects.project_category', 2)
                    ->where(function ($q) use ($user, $columnsToFilter) {

                        // OR across intake role columns
                        foreach ($columnsToFilter as $col) {
                            $q->orWhere($col, $user->id);
                        }

                        // Project Manager access (via pivot project_user) — unchanged
                        if ($user->hasRole('project manager')) {
                            $q->orWhereExists(function ($sub) use ($user) {
                                $sub->selectRaw(1)
                                    ->from('project_user as pu')
                                    ->whereColumn('pu.project_id', 'projects.id')
                                    ->where('pu.user_id', $user->id);
                            });
                        }
                    });
            });

            // ---------- Category 1 (General) — PM stays same + member assignment ----------
            $root->orWhere(function ($cat1) use ($user) {
                $cat1->where('projects.project_category', 1)
                    ->where(function ($q) use ($user) {

                        // PMs still see their projects
                        if ($user->hasRole('project manager')) {
                            $q->orWhereExists(function ($sub) use ($user) {
                                $sub->selectRaw(1)
                                    ->from('project_user as pu')
                                    ->whereColumn('pu.project_id', 'projects.id')
                                    ->where('pu.user_id', $user->id);
                            });
                        }

                        // Members assigned (project_member_assignments.member_id = user)
                        $q->orWhereExists(function ($sub) use ($user) {
                            $sub->selectRaw(1)
                                ->from('project_member_assignments as pma')
                                ->whereColumn('pma.project_id', 'projects.id')
                                ->where('pma.member_id', $user->id);
                        });
                    });
            });
        });
        return $query;
    }

<<<<<<< HEAD
=======
   

>>>>>>> 9d9ed85b (for cleaner setup)


    public function html()
    {
        return $this->builder()
            ->setTableId('projects-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1, 'desc') // because 'expand' is column 0 now
            ->buttons(['excel', 'csv', 'print', 'reset', 'reload']);
    }

    protected function getColumns()
    {
        return [
            ['data' => 'expand',            'name' => 'expand',               'title' => '', 'orderable' => false, 'searchable' => false, 'width' => '30px'],
            ['data' => 'project_name',      'name' => 'projects.project_name', 'title' => 'Project Name'],
            ['data' => 'customer_name',     'name' => 'c.name',                'title' => 'Customer'],
<<<<<<< HEAD
            ['data' => 'project_type_name', 'name' => 'pt.name',               'title' => 'Project Type'],
=======
          //  ['data' => 'project_type_name', 'name' => 'pt.name',               'title' => 'Project Type'],
>>>>>>> 9d9ed85b (for cleaner setup)
            ['data' => 'status_name',       'name' => 'ps.name',               'title' => 'Status'],
            ['data' => 'start_date',        'name' => 'projects.start_date',   'title' => 'Start Date'],
            ['data' => 'end_date',          'name' => 'projects.end_date',     'title' => 'End Date'],
            ['data' => 'actions',           'name' => 'actions',               'title' => 'Actions', 'orderable' => false, 'searchable' => false],
        ];
    }

    protected function filename(): string
    {
        return 'Projects_' . date('YmdHis');
    }
}
