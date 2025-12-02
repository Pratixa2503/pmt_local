<?php
// app/Http/Controllers/ProjectController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Helpers\ProjectHelper;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $api;

    public function __construct(ApiService $api)
    {
        $this->api = $api;
    }
    public function index(Request $request)
    {

        $year = (int) date('Y');
        $data = [
            'total_companies'   => 0,
            'valid_companies'   => 0,
            'invalid_companies' => 0,
            'total_delivered'   => 0,
            'xbrl'              => 0,
            'non_xbrl'          => 0,
            'last_30_days'      => 0,
            'last_60_days'      => 0,
            'last_90_days'      => 0,
            'monthly_labels'    => [],
            'monthly_values'    => [],
            'year'              => $year,
        ];
        $projects = ProjectHelper::activeProjectsIdName();
        // pick default: from query ?project=ID, else first project id
        $selectedProjectId = $request->integer('project') ?: ($projects->first()->id ?? null);
        $countByStatus = ProjectHelper::countByStatus();
        $leaseinfo = [];
        if ($selectedProjectId) {
            $leaseinfo = ProjectHelper::lease_abstract_info($selectedProjectId);
        }

        // -----------------------
        // Company details
        // -----------------------
        try {
            $response = $this->api->getCompanyDetails($year);
            $ok = false;
            if (is_array($response) || is_object($response)) {
                $respArr = (array) $response;
                if (array_key_exists('success', $respArr)) {
                    $ok = (bool) $respArr['success'];
                } elseif (array_key_exists('status', $respArr)) {
                    $ok = (bool) $respArr['status'];
                }
            } else {
                $respArr = [];
            }

            if ($ok) {
                $apiData = data_get($response, 'data.data', data_get($response, 'data', []));
                $summary = data_get($apiData, 'overall_status_counts', []);

                $data['total_companies']   = (int) data_get($summary, 'total', $data['total_companies']);
                $data['valid_companies']   = (int) data_get($summary, 'valid', $data['valid_companies']);
                $data['invalid_companies'] = (int) data_get($summary, 'invalid', $data['invalid_companies']);
            } else {
                Log::warning('Company API returned false', ['response' => $response, 'year' => $year]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching company details', ['err' => $e->getMessage(), 'year' => $year]);
        }

        // -----------------------
        // File type / delivery format
        // -----------------------
        try {
            $fileTypeResp = $this->api->getFileTypeDetails($year);
            $slaOk = false;
            if (is_array($fileTypeResp) || is_object($fileTypeResp)) {
                $slaArr = (array) $fileTypeResp;
                if (array_key_exists('success', $slaArr)) {
                    $slaOk = (bool) $slaArr['success'];
                } elseif (array_key_exists('status', $slaArr)) {
                    $slaOk = (bool) $slaArr['status'];
                }
            }

            if ($slaOk) {
                $fileData = data_get($fileTypeResp, 'data', []);
                $metrics = data_get($fileData, 'overall_delivery_format_counts', []);
                $data['total_delivered'] = (int) data_get($metrics, 'total_delivered', $data['total_delivered']);
                $data['xbrl']            = (int) data_get($metrics, 'xbrl', $data['xbrl']);
                $data['non_xbrl']        = (int) data_get($metrics, 'non_xbrl', $data['non_xbrl']);
                $data['year'] = $year;
            } else {
                Log::warning('FileType API returned false', ['response' => $fileTypeResp, 'year' => $year]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching file type details', ['err' => $e->getMessage(), 'year' => $year]);
        }

        // -----------------------
        // Delivery SLA
        // -----------------------
        try {
            $slaResp = $this->api->getDeliverySla($year);

            if (!is_array($slaResp) && !is_object($slaResp)) {
                Log::warning('SLA API invalid response', ['resp' => $slaResp, 'year' => $year]);
            } else {
                $arr = (array) $slaResp;
                $ok = array_key_exists('success', $arr) ? (bool)$arr['success'] : (bool)($arr['status'] ?? false);

                if ($ok) {
                    $slaData = data_get($slaResp, 'data.data', data_get($slaResp, 'data', []));
                    $metrics = data_get($slaData, 'delivered_sla_metrics', [
                        'last_30_days' => 0,
                        'last_60_days' => 0,
                        'last_90_days' => 0,
                    ]);
                    $data['last_30_days'] = (int) data_get($metrics, 'last_30_days', 0);
                    $data['last_60_days'] = (int) data_get($metrics, 'last_60_days', 0);
                    $data['last_90_days'] = (int) data_get($metrics, 'last_90_days', 0);

                    $data['sla_last_updated'] = data_get($slaData, 'last_updated', data_get($slaResp, 'data.last_updated', null));
                } else {
                    Log::warning('SLA API returned false', ['response' => $slaResp, 'year' => $year]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error fetching SLA', ['err' => $e->getMessage(), 'year' => $year]);
        }
        // -----------------------
        // Monthly chart data
        // -----------------------
        try {
            $monthlyResp = $this->api->getMonthlyCount($year);
            if (is_array($monthlyResp) || is_object($monthlyResp)) {
                // some of your other code expected status key truthy
                $statusOk = !empty(data_get($monthlyResp, 'status')) || data_get($monthlyResp, 'success') === true;
                if ($statusOk) {
                    $monthlyData = data_get($monthlyResp, 'data', []);
                    $data['monthly_labels'] = data_get($monthlyData, 'labels', $data['monthly_labels']);
                    $data['monthly_values'] = data_get($monthlyData, 'values', $data['monthly_values']);
                    $data['year'] = $year;
                } else {
                    Log::warning('getMonthlyCount returned false', ['resp' => $monthlyResp, 'year' => $year]);
                }
            } else {
                Log::warning('getMonthlyCount invalid response', ['resp' => $monthlyResp, 'year' => $year]);
            }
        } catch (\Exception $e) {
            Log::error('Error fetching monthly data on index', ['err' => $e->getMessage(), 'year' => $year]);
        }

        return view('content.dashboard.dashboards-analytics', compact('data', 'year', 'projects', 'selectedProjectId', 'countByStatus', 'leaseinfo'));
    }

    // // AJAX endpoint to fetch data for a selected project
    // public function showData(Request $request, int $projectId)
    // {
    //     $data = ProjectHelper::projectData($projectId);
    //     return response()->json($data);
    // }

    public function companyByYear(Request $request)
    {
        $year = (int) $request->query('year', date('Y'));
        $resp = $this->api->getCompanyDetails($year);

        if (!is_array($resp) && !is_object($resp)) {
            return response()->json(['success' => false, 'message' => 'Invalid response from API'], 500);
        }

        $ok = (bool) (data_get($resp, 'success') ?? data_get($resp, 'status') ?? false);

        if (!$ok) {
            $msg = data_get($resp, 'message', 'Failed to fetch data');
            return response()->json(['success' => false, 'message' => $msg], 500);
        }
        $apiData = data_get($resp, 'data.data', []);
        $summary = data_get($apiData, 'overall_status_counts', [
            'total' => 0,
            'valid' => 0,
            'invalid' => 0
        ]);
        return response()->json([
            'success' => true,
            'data' => [
                'Total Company' => (int) data_get($summary, 'total', 0),
                'Valid Company' => (int) data_get($summary, 'valid', 0),
                'Invalid Company' => (int) data_get($summary, 'invalid', 0),
                'year' => $year,
            ],
        ]);
    }


    public function fileTypeByYear(Request $request)
    {
        $year = (int) $request->query('year', date('Y'));
        $resp = $this->api->getFileTypeDetails($year);

        if (!is_array($resp) && !is_object($resp)) {
            return response()->json(['success' => false, 'message' => 'Invalid response from API'], 500);
        }

        $ok = (bool) (data_get($resp, 'success') ?? data_get($resp, 'status') ?? false);

        if (!$ok) {
            $msg = data_get($resp, 'message', 'Failed to fetch data');
            return response()->json(['success' => false, 'message' => $msg], 500);
        }
        $apiData = data_get($resp, 'data.data', []);
        $summary = data_get($apiData, 'overall_delivery_format_counts', [
            'total_delivered' => 0,
            'xbrl' => 0,
            'non_xbrl' => 0
        ]);
        return response()->json([
            'success' => true,
            'data' => [
                'Total Delivered' => (int) data_get($summary, 'total_delivered', 0),
                'xbrl' => (int) data_get($summary, 'xbrl', 0),
                'non xbrl' => (int) data_get($summary, 'non_xbrl', 0),
                'year' => $year,
            ],
        ]);
    }


    public function slaByYear(Request $request)
    {
        $year = (int) $request->query('year', date('Y'));

        try {
            $slaResp = $this->api->getDeliverySla($year);

            if (!is_array($slaResp) && !is_object($slaResp)) {
                Log::warning('SLA API invalid response (not array/object)', ['resp' => $slaResp, 'year' => $year]);
                return response()->json(['success' => false, 'message' => 'Invalid response from SLA API'], 500);
            }

            $arr = (array) $slaResp;
            $ok = array_key_exists('success', $arr) ? (bool) $arr['success'] : (bool)($arr['status'] ?? false);

            if (! $ok) {
                $msg = data_get($slaResp, 'message', 'Failed to fetch SLA');
                Log::warning('SLA API reported failure', ['year' => $year, 'resp' => $slaResp]);
                return response()->json(['success' => false, 'message' => $msg], 500);
            }

            // --- Robust extraction: try several common paths where metrics might live ---
            $metrics = data_get($slaResp, 'data.data.delivered_sla_metrics');
            if (is_null($metrics)) {
                $metrics = data_get($slaResp, 'data.delivered_sla_metrics');
            }
            if (is_null($metrics)) {
                // fallback to deeper inspect: sometimes API wraps another status/data layer
                $possible = data_get($slaResp, 'data');
                if (is_array($possible) && array_key_exists('data', $possible)) {
                    $metrics = data_get($possible, 'data.delivered_sla_metrics');
                }
            }

            // final fallback to zeros
            $metrics = (array) ($metrics ?? [
                'last_30_days' => 0,
                'last_60_days' => 0,
                'last_90_days' => 0,
            ]);

            // Ensure integer values
            $last30 = (int) data_get($metrics, 'last_30_days', 0);
            $last60 = (int) data_get($metrics, 'last_60_days', 0);
            $last90 = (int) data_get($metrics, 'last_90_days', 0);

            // last_updated might live at data.data.last_updated or data.last_updated
            $lastUpdated = data_get($slaResp, 'data.data.last_updated', data_get($slaResp, 'data.last_updated', null));

            // Build flat payload expected by frontend
            $payload = [
                'success' => true,
                'data' => [
                    'Last 30 Day' => $last30,
                    'Last 60 Day' => $last60,
                    'Last 90 Day' => $last90,
                ],
            ];

            return response()->json($payload, 200);
        } catch (\Throwable $e) {
            Log::error('Exception in slaByYear', ['err' => $e->getMessage(), 'year' => $year]);
            return response()->json(['success' => false, 'message' => 'Error fetching SLA metrics'], 500);
        }
    }


    public function monthlyDelivered(Request $request)
    {
        // Accept both GET query string and array payload (Choices might send array)
        $year = (int) $request->query('year', date('Y'));
        $monthInput = $request->query('month', null); // could be "08,07" or ["8","7"]

        // normalize months to numeric array or null
        $monthsArr = null;
        if (is_array($monthInput)) {
            $monthsArr = array_values(array_filter(array_map(function ($m) {
                return strlen(trim($m)) ? (int) $m : null;
            }, $monthInput)));
        } elseif (is_string($monthInput) && strlen(trim($monthInput)) > 0) {
            $parts = array_filter(array_map('trim', explode(',', $monthInput)));
            $monthsArr = array_map('intval', $parts);
        }

        try {
            $resp = $this->api->getMonthlyCount($year, $monthsArr);

            if (is_array($resp) && !empty($resp['status'])) {
                return response()->json([
                    'success' => true,
                    'data'    => $resp['data'],
                    'message' => data_get($resp, 'message', 'OK'),
                ]);
            }

            // API returned false-ish
            return response()->json([
                'success' => false,
                'message' => data_get($resp, 'message', 'API returned false'),
                'data'    => [],
            ], 400);
        } catch (\Exception $e) {
            \Log::error('monthlyDelivered endpoint error', ['err' => $e->getMessage(), 'year' => $year, 'months' => $monthsArr]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }
}
