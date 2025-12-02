<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected $client;
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('API_BASE_URL'), '/'); // remove trailing slash if any
        $this->apiKey  = env('API_KEY');
        $this->defaultYear = env('METRICS_DEFAULT_YEAR');

        $this->client = new Client([
            'base_uri' => $this->baseUrl . '/', // append slash here
            'timeout'  => 15.0,
        ]);
    }

    public function getCompanyDetails($year = null)
    {
        $year = $year ?: date('Y');
        $url = "api/metrics/overall-company-status"; // endpoint path (relative to base_uri)

        try {
             $options = [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => ['year' => $year],
                'timeout' => 120,         
                'connect_timeout' => 20, 
            ];

            if (!empty($this->apiKey)) {
                $options['headers']['X-API-KEY'] = $this->apiKey;
            }

            $response = $this->client->request('GET', $url, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            /* Log::info('getDeliveryCompany: API Success', [
                'url' => $this->baseUrl . '/' . $url . '?year=' . $year,
                'response' => $body,
            ]); */
            // Normalize return like other methods
            return [
                'status' => true,
                'data'   => $body,
            ];

        } catch (\Exception $e) {
           /*  Log::error('getDeliveryCompany: API Error', [
                'url' => $this->baseUrl . '/' . $url . '?year=' . $year,
                'error' => $e->getMessage(),
            ]); */

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

     public function getFileTypeDetails($year = null)
    {
        $year = $year ?: date('Y');
        $url = "api/metrics/delivered-xbrl-nonxbrl"; // endpoint path (relative to base_uri)

        try {
             $options = [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => ['year' => $year],
                'timeout' => 120,         
                'connect_timeout' => 20, 
            ];

            if (!empty($this->apiKey)) {
                $options['headers']['X-API-KEY'] = $this->apiKey;
            }

            $response = $this->client->request('GET', $url, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('getFileTypeDetails: API Success', [
                'url' => $this->baseUrl . '/' . $url . '?year=' . $year,
                'response' => $body,
            ]);
            // Normalize return like other methods
            return [
                'status' => true,
                'data'   => $body,
            ];

        } catch (\Exception $e) {
            Log::error('getFileTypeDetails: API Error', [
                'url' => $this->baseUrl . '/' . $url . '?year=' . $year,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
    public function getDeliverySla($year = null)
    {
        $year = $year ?: date('Y');
        $url = "api/metrics/delivered-sla"; // endpoint path (relative to base_uri)

        try {
             $options = [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'query' => ['year' => $year],

                'timeout' => 120,         
                'connect_timeout' => 20,  
            ];

            if (!empty($this->apiKey)) {
                $options['headers']['X-API-KEY'] = $this->apiKey;
            }

            $response = $this->client->request('GET', $url, $options);
            $body = json_decode($response->getBody()->getContents(), true);

            Log::info('getDeliverySla: API Success', [
                'url' => $this->baseUrl . '/' . $url . '?year=' . $year,
                'response' => $body,
            ]);
            // Normalize return like other methods
            return [
                'status' => true,
                'data'   => $body,
            ];

        } catch (\Exception $e) {
            Log::error('getDeliverySla: API Error', [
                'url' => $this->baseUrl . '/' . $url . '?year=' . $year,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getMonthlyCount($year = null, $months = null)
{
    $year = $year ?: date('Y');
    $url = "api/metrics/monthly-delivered";

    try {
        // Normalize months input (accept array, CSV string, or null)
        $monthQuery = null;
        if (!empty($months)) {
            if (is_array($months)) {
                // convert numeric months like [8,7,6] to zero-padded 2-digit strings ['08','07','06']
                $monthQuery = implode(',', array_map(function($m){
                    $m = (string) $m;
                    // if month already formatted like 'Aug' then keep as-is (but API expects digits usually)
                    if (preg_match('/^[A-Za-z]{3}$/', $m)) {
                        return $m;
                    }
                    $int = intval($m);
                    return str_pad($int, 2, '0', STR_PAD_LEFT);
                }, $months));
            } else {
                // assume string like '08,07,06' or '08'
                $monthQuery = (string) $months;
            }
        }

        $query = ['year' => $year];
        if (!empty($monthQuery)) {
            $query['month'] = $monthQuery;
        }

        $options = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $query,
            'timeout' => 120,         
            'connect_timeout' => 20, 
        ];

        if (!empty($this->apiKey)) {
            $options['headers']['X-API-KEY'] = $this->apiKey;
        }

        $response = $this->client->request('GET', $url, $options);
        $body = json_decode($response->getBody()->getContents(), true);

        Log::info('getMonthlyCount: API Success', [
            'url' => $this->baseUrl . '/' . $url . '?' . http_build_query($query),
            'response' => $body,
        ]);

        // Defensive: ensure expected shape
        $data = data_get($body, 'data', []);
        $breakdown = data_get($data, 'breakdown', []);

        // Normalize: ensure breakdown is an array of ['month'=>..., 'count'=>...]
        // Some APIs may return single object — handle both
        if (!is_array($breakdown)) {
            $breakdown = [];
        }

        // Build labels & values arrays (preserve API order — which for full-year is Jan..Dec)
        $labels = [];
        $values = [];
        foreach ($breakdown as $item) {
            // If item indexed numerically and holds month/count as object, make safe checks
            if (is_array($item)) {
                $label = data_get($item, 'month', null);
                $count = data_get($item, 'count', 0);
            } else {
                // not expected, skip
                continue;
            }
            $labels[] = $label;
            $values[] = (int) $count;
        }

        // If client requested specific months as numeric array, but API returns month names,
        // you might want to reorder results according to requested months. Below is optional:
        if (!empty($monthQuery) && is_array($months)) {
            // Build map by month name for quick lookup (API returns 'Jun','Jul' etc.)
            $map = [];
            foreach ($breakdown as $item) {
                $map[data_get($item,'month')] = (int) data_get($item,'count',0);
            }
            // Convert requested numeric months to names (Jan..Dec) to ensure order matches request
            // Only if months array contains numeric months
            $allMonthNames = [
                1 => 'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',
                7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'
            ];
            $labels = [];
            $values = [];
            foreach ($months as $m) {
                $mi = intval($m);
                if ($mi >=1 && $mi <= 12) {
                    $mn = $allMonthNames[$mi];
                    $labels[] = $mn;
                    $values[] = isset($map[$mn]) ? $map[$mn] : 0;
                } else {
                    // if not numeric, try to use as-is (e.g. 'Aug')
                    $mn = (string) $m;
                    $labels[] = $mn;
                    $values[] = isset($map[$mn]) ? $map[$mn] : 0;
                }
            }
        }

        // Final normalized payload
        $normalized = [
            'year' => data_get($data, 'year', $year),
            'breakdown' => $breakdown,
            'labels' => $labels,
            'values' => $values,
        ];

        return [
            'status' => true,
            'data'   => $normalized,
            'message' => data_get($body, 'message', 'OK'),
        ];

    } catch (\Exception $e) {
        Log::error('getMonthlyCount: API Error', [
            'url' => $this->baseUrl . '/' . $url . (isset($query) ? '?'.http_build_query($query) : ''),
            'error' => $e->getMessage(),
        ]);

        return [
            'status' => false,
            'message' => $e->getMessage(),
        ];
    }
}
}
