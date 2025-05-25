<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Proyek; // pastikan ini sesuai dengan nama model proyek kamu

class DashboardOwnerController extends Controller
{
    protected $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected $appName = 'interest';

    protected $deviceList = [
        'ph', 'pota', 'phospor', 'EC', 'Nitrogen', 'humidity', 'temp'
    ];

    public function index()
    {
        $results = [];
        $historyData = [];

        foreach ($this->deviceList as $device) {
            $results[$device] = $this->fetchLatestData($device);
            $historyData[$device] = $this->fetchRecentValues($device);
        }

        $chartData = $this->prepareChartData($results);
        $reportSeries = $this->prepareReportSeries($historyData);

        // Ambil jumlah user, mitra, dan proyek
        $jumlahUser = User::count();
        $jumlahUserMitra = Mitra::count();
        $jumlahProyek = Proyek::count(); // atau set = 0 jika tidak ada

        return view('dashboardowner.index', compact(
            'results',
            'chartData',
            'reportSeries',
            'jumlahUserMitra',
            'jumlahProyek',
            'jumlahUser'
        ));
    }

    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get($url);

        if (!$response->successful()) return ['nilai' => null];

        $raw = $response->json('m2m:cin.con') ?? null;

        if (!$raw) return ['nilai' => null];

        $clean = trim($raw, "'\"");
        $decoded = json_decode($clean, true);

        $nilai = is_array($decoded) ? ($decoded['nilai'] ?? null) : $clean;

        if (!is_numeric($nilai)) return ['nilai' => null];

        $nilai = $this->adjustValue($device, $nilai);

        return ['nilai' => $this->formatNumber($nilai, $device)];
    }

    private function fetchRecentValues($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}?rcn=4";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->get($url);

        if (!$response->successful()) return [];

        $cinList = $response->json('m2m:cnt.m2m:cin') ?? [];

        return collect($cinList)->reverse()->take(7)->map(function ($item) use ($device) {
            $value = $item['con'] ?? null;
            $clean = trim($value, "'\"");
            $decoded = json_decode($clean, true);

            $nilai = is_array($decoded) ? ($decoded['nilai'] ?? null) : $clean;

            return is_numeric($nilai) ? $this->adjustValue($device, $nilai) : null;
        })->filter()->values()->toArray();
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

    private function prepareReportSeries($data)
    {
        return [
            ['name' => 'pH', 'data' => $data['ph'] ?? []],
            ['name' => 'EC', 'data' => $data['EC'] ?? []],
            ['name' => 'Soil Moisture', 'data' => $data['humidity'] ?? []],
        ];
    }

    private function adjustValue($device, $value)
    {
        $adjustments = [
            'ph' => 100,
            'ec' => 100,
            'humidity' => 10,
            'moisture' => 10,
            'temp' => 10,
        ];

        $intDevices = ['pota', 'phospor', 'nitrogen'];

        $device = strtolower($device);

        if (isset($adjustments[$device])) {
            return $value / $adjustments[$device];
        } elseif (in_array($device, $intDevices)) {
            return (int) $value;
        }

        return $value;
    }

    private function formatNumber($value, $device)
    {
        if (!is_numeric($value)) return $value;

        $formatted = fmod($value, 1) !== 0.0
            ? number_format($value, 2, ',', '.')
            : number_format($value, 0, ',', '.');

        return match (strtolower($device)) {
            'ec' => $formatted . ' dS/m',
            'humidity' => $formatted . ' %',
            'temp' => $formatted . ' °C',
            default => $formatted,
        };
    }

    private function parseNumber($value)
    {
        if (is_null($value)) return null;
        $clean = str_replace(['dS/m', '%', '°C', '.', ','], ['', '', '', '', '.'], $value);
        return is_numeric($clean) ? (float)$clean : null;
    }
}
