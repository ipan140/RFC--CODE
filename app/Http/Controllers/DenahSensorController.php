<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DenahSensorController extends Controller
{
    protected string $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected string $appName = 'interest';

    protected array $deviceList = [
        'ph',
        'pota',
        'phospor',
        'EC',
        'Nitrogen',
        'humidity',
        'temp',
    ];

    protected array $deviceIdMap = [
        'ph' => 'cnt-a5dIIlr6fAprJBmA',
        'pota' => 'cnt-2pltCukXhYO7ltH4',
        'phospor' => 'cnt-I0EzV5YK4F0ZFMjK',
        'EC' => 'cnt-bjIY1VrkWE4Zldm',
        'Nitrogen' => 'cnt-JIx5K0R06fHdkuPx',
        'humidity' => 'cnt-2QlkBtJC6Sw8SMBy',
        'temp' => 'cnt-UNnbR4liwVIQMQGRH',
    ];

    protected array $deviceLocations = [
        'ph'       => ['lat' => -7.310985, 'lng' => 112.729157],
        'pota'     => ['lat' => -7.310987, 'lng' => 112.729168],
        'phospor'  => ['lat' => -7.310989, 'lng' => 112.729145],
        'EC'       => ['lat' => -7.310991, 'lng' => 112.729155],
        'Nitrogen' => ['lat' => -7.310993, 'lng' => 112.729165],
        'humidity' => ['lat' => -7.310995, 'lng' => 112.729175],
        'temp'     => ['lat' => -7.310997, 'lng' => 112.729185],
    ];


    public function index()
    {
        $results = [];

        foreach ($this->deviceList as $device) {
            $deviceId = $this->deviceIdMap[$device] ?? null;
            if (!$deviceId) continue;

            $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

            $response = Http::withHeaders([
                'X-M2M-Origin' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->get($url);

            $location = $this->deviceLocations[$device];
            $data = null;
            $status = 'Tidak Respon';

            if ($response->successful()) {
                $con = $response->json('m2m:cin.con');
                $parsedValue = $this->parseRawValue($device, $con);

                if (is_numeric($parsedValue)) {
                    $adjusted = $this->adjustValue($device, $parsedValue);
                    $formatted = $this->formatNumber($adjusted, $device);

                    $data = [
                        'raw' => $parsedValue,
                        'adjusted' => $adjusted,
                        'formatted' => $formatted,
                    ];
                    $status = 'Respon';
                }
            }

            $results[] = [
                'name' => $device,
                'data' => $data,
                'status' => $status,
                'lat' => $location['lat'],
                'lng' => $location['lng'],
            ];
        }

        return view('denah.index', [
            'title' => 'Denah Sensor',
            'sensors' => $results,
        ]);
    }

    private function parseRawValue(?string $device, $raw)
    {
        if (is_null($raw)) return null;
        $cleaned = trim($raw, "'\"");
        $decoded = json_decode($cleaned, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $device = strtolower($device);
            return $decoded['nilai'] ?? $decoded['value'] ?? $decoded[$device] ?? collect($decoded)->first();
        }
        return is_numeric($cleaned) ? (float)$cleaned : null;
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
            return (int)$value;
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
            default => $formatted . ' ppm',
        };
    }
}
