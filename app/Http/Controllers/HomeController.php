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
        $this->middleware('auth');
        // Injected ApiService instance from the container
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
        $year   = $request->query('year', date('Y'));
        $userId = $request->query('user_id', auth()->id());
        $response = $this->api->getCompanyDetails($year, $userId);
        $data = [];
        $error = null;
        $totalProjects = Project::count();
        $statusCounts = Project::select('project_status_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('project_status_id')
            ->pluck('total', 'project_status_id');
        $statuses = ProjectStatus::pluck('id', 'name');

        $totalCompanies = 0;
        $activeCompanies = 0;
        $inactiveCompanies = 0;

        if (isset($response['status']) && $response['status'] === true) {
            $data = $response['data'] ?? [];

            $totalCompanies    = Company::count();
            $activeCompanies   = Company::where('status', 1)->count();
            $inactiveCompanies = Company::where('status', 2)->count();
        } else {
            $error = $response['message'] ?? 'Failed to fetch data from API';
        }

        return view('content.dashboard.dashboards-analytics', compact(
            'data',
            'year',
            'userId',
            'totalProjects',
            'statusCounts',
            'statuses',
            'totalCompanies',
            'activeCompanies',
            'inactiveCompanies',
            'error'
        ));
    }
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
