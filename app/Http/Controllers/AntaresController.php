<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AntaresController extends Controller
{
    protected $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected $appName = 'interest';

    protected $deviceList = [
        'ph',
        'pota',
        'phospor',
        'EC',
        'Nitrogen',
        'humidity',
        'temp',
    ];

    public function index(Request $request)
    {
        $deviceParam = $request->query('device');

        if ($deviceParam) {
            return $this->getDeviceData($deviceParam);
        }

        $results = [];
        $historyData = [];

        foreach ($this->deviceList as $device) {
            $results[$device] = $this->fetchLatestData($device);
            $historyData[$device] = $this->fetchRecentValues($device);
        }

        $trafficChart = $this->prepareChartData($results);
        $reportSeries = $this->prepareReportSeries($historyData);

        return view('table.index', [
            'data' => $results,
            'chartData' => $trafficChart,
            'reportSeries' => $reportSeries,
        ]);
    }

    public function getDeviceData($device)
    {
        $device = strtolower($device);

        if (!in_array($device, $this->deviceList)) {
            abort(404, 'Device tidak ditemukan.');
        }

        $data = $this->fetchAllData($device);

        return view('device', [
            'device' => $device,
            'items' => $data,
        ]);
    }

    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $raw = $response->json('m2m:cin.con') ?? null;

            if (empty($raw)) {
                return ['nilai' => null];
            }

            if (is_string($raw)) {
                $raw = trim($raw, "'");
                $decoded = json_decode($raw, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $nilai = $decoded['nilai'] ?? null;
                } else {
                    $nilai = $raw;
                }

                if ($nilai !== null) {
                    $nilai = $this->adjustValue($device, $nilai);
                    return ['nilai' => $this->formatNumber($nilai, $device)];
                }
            } elseif (is_numeric($raw)) {
                $nilai = $this->adjustValue($device, $raw);
                return ['nilai' => $this->formatNumber($nilai, $device)];
            }
        }

        return ['nilai' => null];
    }

    private function fetchAllData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}?rcn=4";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if (!$response->successful()) {
            return [['error' => 'Gagal mengambil data dari Antares']];
        }

        $cinList = $response->json('m2m:cnt.m2m:cin') ?? [];

        if (empty($cinList)) {
            return [['info' => 'Belum ada data untuk device ini']];
        }

        $result = [];

        foreach ($cinList as $item) {
            $rawValue = $item['con'] ?? null;

            $parsedValue = $rawValue;

            // Bersihkan kutipan jika ada
            if (is_string($rawValue)) {
                $cleaned = trim($rawValue, "'\"");

                // Coba decode JSON
                $decoded = json_decode($cleaned, true);

                // Jika decode berhasil dan array, pakai hasilnya
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $parsedValue = $decoded;
                } else {
                    // Jika bukan JSON, tetap pakai string asli
                    $parsedValue = $cleaned;
                }
            }

            $result[] = [
                'ri' => $item['ri'] ?? 'no-ri',
                'time' => $item['ct'] ?? 'no-time',
                'value' => $parsedValue ?? 'no-value',
            ];
        }

        return $result;
    }


    private function fetchRecentValues($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}?rcn=4";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if (!$response->successful()) {
            return [];
        }

        $cinList = $response->json('m2m:cnt.m2m:cin') ?? [];

        $recentValues = [];

        foreach (array_slice($cinList, -7) as $item) {
            $value = $item['con'] ?? null;

            if (is_string($value)) {
                $value = trim($value, "'");
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $nilai = $decoded['nilai'] ?? null;
                } else {
                    $nilai = $value;
                }
            } else {
                $nilai = $value;
            }

            if (is_numeric($nilai)) {
                $recentValues[] = $this->adjustValue($device, $nilai);
            }
        }

        return $recentValues;
    }

    private function prepareChartData($results)
    {
        return [
            'pH' => $this->parseNumber($results['ph']['nilai'] ?? null),
            'EC' => $this->parseNumber($results['EC']['nilai'] ?? null),
            'Soil Moisture' => $this->parseNumber($results['humidity']['nilai'] ?? null),
            'Soil Temperature' => $this->parseNumber($results['temp']['nilai'] ?? null),
            'Nitrogen' => $this->parseNumber($results['Nitrogen']['nilai'] ?? null),
            'Phospor' => $this->parseNumber($results['phospor']['nilai'] ?? null),
            'Potassium' => $this->parseNumber($results['pota']['nilai'] ?? null),
        ];
    }

    private function prepareReportSeries($historyData)
    {
        return [
            [
                'name' => 'pH',
                'data' => $historyData['ph'] ?? []
            ],
            [
                'name' => 'EC',
                'data' => $historyData['EC'] ?? []
            ],
            [
                'name' => 'Soil Moisture',
                'data' => $historyData['humidity'] ?? []
            ]
        ];
    }

    private function adjustValue($device, $value)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        $device = strtolower($device);

        $adjustments = [
            'ph' => 100,
            'ec' => 100,
            'humidity' => 10,
            'moisture' => 10,
            'temp' => 10,
        ];

        $integerDevices = ['pota', 'phospor', 'nitrogen'];

        if (isset($adjustments[$device])) {
            return $value / $adjustments[$device];
        } elseif (in_array($device, $integerDevices)) {
            return (int) $value;
        }

        return $value;
    }

    private function formatNumber($value, $device)
    {
        if (!is_numeric($value)) {
            return $value;
        }

        if (fmod($value, 1) !== 0.0) {
            $formatted = number_format($value, 2, ',', '.');
        } else {
            $formatted = number_format($value, 0, ',', '.');
        }

        switch (strtolower($device)) {
            case 'ec':
                return $formatted . ' dS/m';
            case 'humidity':
                return $formatted . ' %';
            case 'temp':
                return $formatted . ' °C';
            default:
                return $formatted;
        }
    }

    private function parseNumber($value)
    {
        if (is_null($value)) return null;

        $clean = str_replace(['.', ',', 'dS/m', '%', '°C'], ['', '.', '', '', ''], $value);
        return is_numeric($clean) ? (float) $clean : null;
    }

    public function dashboardByRole($role)
    {
        $devices = ['ph', 'pota', 'phospor', 'EC', 'Nitrogen', 'humidity', 'temp'];
        $data = [];

        foreach ($devices as $device) {
            $entries = $this->fetchAllData($device);

            if (!empty($entries) && isset($entries[0]['value']) && is_array($entries[0]['value'])) {
                $latest = $entries[0]['value'];

                foreach ($latest as $key => $value) {
                    if (in_array(strtolower($key), $this->deviceList)) {
                        $adjusted = $this->adjustValue(strtolower($key), $value);
                        $data[$device][$key] = $this->formatNumber($adjusted, $key);
                    }
                }
            } else {
                $data[$device] = ['error' => 'Data tidak tersedia'];
            }
        }

        if ($role === 'admin') {
            return view('dashboardadmin.index', compact('data'));
        } elseif ($role === 'owner') {
            return view('dashboardowner.index', compact('data'));
        } elseif ($role === 'user') {
            return view('dashboarduser.index', compact('data'));
        } else {
            abort(403, 'Role tidak valid.');
        }
    }

    public function dashboardChart()
    {
        $chartData = [];
        $chartLabels = [];

        foreach ($this->deviceList as $device) {
            $allData = $this->fetchAllData($device);

            $formattedData = collect($allData)
                ->filter(fn($item) => isset($item['time'], $item['value']) && is_array($item['value']))
                ->sortByDesc('time')
                ->take(10)
                ->reverse()
                ->values()
                ->all();

            $labels = [];
            $values = [];

            foreach ($formattedData as $entry) {
                $labels[] = \Carbon\Carbon::parse($entry['time'])->format('H:i');
                $rawValue = $entry['value']['nilai'] ?? $entry['value'][$device] ?? 0;
                $values[] = $this->adjustValue($device, $rawValue);
            }

            // Pakai label pertama sebagai acuan (asumsi semua device punya waktu serupa)
            if (empty($chartLabels)) {
                $chartLabels = $labels;
            }

            $chartData[$device] = $values;
        }

        return view('dashboard.chart', [
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
        ]);
    }
}
