<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SensorChartController extends Controller
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
        'temp'
    ];

    public function index(Request $request)
    {
        $deviceParam = $request->query('device');

        if ($deviceParam) {
            return $this->getDeviceData($deviceParam);
        }

        $results = [];

        foreach ($this->deviceList as $device) {
            $results[$device] = $this->fetchLatestData($device);
        }

        return view('table', ['data' => $results // Pastikan variabel ini tersedia di blade
        ]);
        
    }
    

    public function getDeviceData($device)
    {
        if (!in_array($device, $this->deviceList)) {
            abort(404, 'Device tidak ditemukan.');
        }

        $data = $this->fetchAllData($device);

        return view('table', [
            'device' => $device,
            'items' => $data
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
            $raw = $response['m2m:cin']['con'] ?? null;

            if (is_string($raw)) {
                $raw = trim($raw, "'");
                $decoded = json_decode($raw, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : ['nilai' => $raw];
            }

            return ['nilai' => $raw];
        }

        return ['error' => 'Gagal ambil data'];
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

        $json = $response->json();
        $cinList = $json['m2m:cnt']['m2m:cin'] ?? [];

        if (empty($cinList)) {
            return [['info' => 'Belum ada data untuk device ini']];
        }

        $result = [];

        foreach ($cinList as $item) {
            $value = $item['con'] ?? null;

            if (is_string($value)) {
                $value = trim($value, "'");
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            $result[] = [
                'ri' => $item['ri'] ?? 'no-ri',
                'time' => $item['ct'] ?? 'no-time',
                'value' => $value ?? 'no-value'
            ];
        }

        return $result;
    }
}
