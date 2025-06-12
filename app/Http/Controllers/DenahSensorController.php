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

    protected array $deviceLocations = [
        'ph'       => ['lat' => -7.310847, 'lng' => 112.729129],
        'pota'     => ['lat' => -7.310907, 'lng' => 112.729221],
        'phospor'  => ['lat' => -7.310806, 'lng' => 112.729251],
        'EC'       => ['lat' => -7.310780, 'lng' => 112.729170],
        'Nitrogen' => ['lat' => -7.310760, 'lng' => 112.729100],
        'humidity' => ['lat' => -7.310790, 'lng' => 112.729190],
        'temp'     => ['lat' => -7.310800, 'lng' => 112.729160],
    ];

    public function index()
    {
        $results = [];

        foreach ($this->deviceList as $device) {
            $url = "https://platform.antares.id:8443/~/antares-cse/antares/{$this->appName}/{$device}/la";

            $response = Http::withHeaders([
                'X-M2M-Origin' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->get($url);

            $location = $this->deviceLocations[$device];

            if ($response->successful()) {
                $json = $response->json();
                $con = $json['m2m:cin']['con'] ?? '{}';
                $data = json_decode($con, true);

                $results[] = [
                    'name'   => $device,
                    'data'   => is_array($data) ? $data : null,
                    'status' => is_array($data) ? 'Respon' : 'Tidak Respon',
                    'lat'    => $location['lat'],
                    'lng'    => $location['lng'],
                ];
            } else {
                $results[] = [
                    'name'   => $device,
                    'data'   => null,
                    'status' => 'Tidak Respon',
                    'lat'    => $location['lat'],
                    'lng'    => $location['lng'],
                ];
            }
        }

        return view('denah.index', [
            'title'   => 'Denah Sensor',
            'sensors' => $results,
        ]);
    }
}
