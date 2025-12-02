<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Company;
use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }

    /**
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
{
    $error = null;
    $totalProjects = Project::count();
    $statusCounts  = Project::select('project_status_id')
                        ->selectRaw('COUNT(*) as total')
                        ->groupBy('project_status_id')
                        ->pluck('total', 'project_status_id');
    $statuses         = ProjectStatus::pluck('id', 'name');
    $totalCompanies   = Company::count();
    $activeCompanies  = Company::where('status', 1)->count();
    $inactiveCompanies= Company::where('status', 2)->count();

    return view('content.dashboard.dashboards-analytics', compact(
        'totalProjects',
        'statusCounts',
        'statuses',
        'totalCompanies',
        'activeCompanies',
        'inactiveCompanies',
        'error'
    ));
}

}
