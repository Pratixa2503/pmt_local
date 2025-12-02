<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectHelper
{
    /**
     * Get active projects (deleted_at IS NULL) with id, name, and status id.
     */
    public static function activeProjectsIdName()
    {
    $user = Auth::user();

    $q = DB::table('projects as p')
        ->select('p.id', 'p.project_name', 'p.project_status_id','p.project_category')
        ->whereNull('p.deleted_at');

    $hasRoles = $user && method_exists($user, 'hasAnyRole');

    if ($hasRoles && $user->hasAnyRole(['super admin', 'admin'])) {
        // Admins: see all not-deleted projects
        // (no extra filters)
    } elseif ($hasRoles && $user->hasAnyRole(['project manager'])) {
        // Project Managers: via project_user pivot
        $q->join('project_user as pu', 'pu.project_id', '=', 'p.id')
          ->where('pu.user_id', $user->id);
    } else {
        // ----- Other users: constrain by project_intakes -----
        // Build the set of intake columns to match based on user role(s)
        $matchCols = [];

        if ($hasRoles && $user->hasRole('abstractor')) {
            $matchCols[] = 'abstractor_id';
        }
        if ($hasRoles && $user->hasRole('reviewer')) {
            $matchCols[] = 'reviewer_id';
        }
        if ($hasRoles && ($user->hasRole('sense check') || $user->hasRole('sense_check') || $user->hasRole('Sense Check / DDR'))) {
            $matchCols[] = 'sense_check_ddr_id';
        }
        // You asked to map "customer" to property_manager_id in intakes:
        if ($hasRoles && $user->hasRole('customer')) {
            $matchCols[] = 'property_manager_id';
        }

        // If none of the above roles matched, return none as a safe default
        if (empty($matchCols)) {
            // (Optionally, you could keep a fallback here)
            $q->whereRaw('1=0');
        } else {
            // WHERE EXISTS an intake row for this project with ANY of the columns = current user
            $q->whereExists(function ($sub) use ($matchCols, $user) {
                $sub->from('project_intakes as pi')
                    ->whereColumn('pi.parent_id', 'p.id')
                    ->where(function ($w) use ($matchCols, $user) {
                        foreach ($matchCols as $col) {
                            $w->orWhere("pi.$col", $user->id);
                        }
                    });
            });
        }
    }

    return $q->distinct('p.id')
             ->orderBy('p.project_name')
             ->get();
    }

    /**
     * Get count of active projects grouped by project_status_id.
     * Returns: [project_status_id => total]
     */
    public static function countByStatus()
    {
         $total = DB::table('projects')->whereNull('deleted_at')->count();

        // counts by status NAME (include statuses with 0 via LEFT JOIN)
        $byStatus = DB::table('project_statuses as s')
            ->leftJoin('projects as p', function ($join) {
                $join->on('p.project_status_id', '=', 's.id')
                    ->whereNull('p.deleted_at');
            })
            ->groupBy('s.id', 's.name') // change s.name -> s.status_name if that's your column
            ->orderBy('s.name')
            ->selectRaw('s.name as status_name, COUNT(p.id) as total')
            ->pluck('total', 'status_name'); // ["In Progress" => 7, "On Hold" => 0, ...]

        // put Total Projects at the beginning
        return $byStatus->prepend($total, 'Total Projects');
    }

    public static function lease_abstract_info(int $projectId, ?string $monthYmd = null): array
    {
        $tz = config('app.timezone');

        // Determine month window
        $start = $monthYmd
            ? Carbon::createFromFormat('Y-m', $monthYmd, $tz)->startOfMonth()->startOfDay()
            : Carbon::now($tz)->startOfMonth()->startOfDay();

        $end = (clone $start)->endOfMonth()->endOfDay();

        $row = DB::table('project_intakes')
            ->where('parent_id', $projectId)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status_master_id = 1 THEN 1 ELSE 0 END) as delivered')
            ->first();

        $total = (int) ($row->total ?? 0);
        $delivered = (int) ($row->delivered ?? 0);
        $percent = $total > 0 ? round(($delivered / $total) * 100, 2) : 0.0;

        return [
            'total'     => $total,
            'delivered' => $delivered,
            'percent'   => $percent,
            'month'     => $start->format('Y-m'),
        ];
    }

}
