<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\InputHarian;
use App\Models\KategoriSampel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartController extends Controller
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

        // Ambil data kategori sampel untuk donut chart
        $panenData = DB::table('input_harians')
            ->join('kategori_sampel', 'input_harians.kategori_sampel_id', '=', 'kategori_sampel.id')
            ->select('kategori_sampel.nama', DB::raw('count(*) as total'))
            ->groupBy('kategori_sampel.nama')
            ->get();

        $labels = $panenData->pluck('nama');
        $series = $panenData->pluck('total');

        return view('chart.index', [
            'data' => $results,
            'chartData' => $this->prepareChartData($results),
            'reportSeries' => $this->prepareReportSeries($results),
            'trafficChartData' => $this->prepareChartData($results),
            'timeSeriesChart' => $this->prepareTimeSeriesChart($results),
            'labels' => $labels,
            'series' => $series,
        ]);
    }

    public function getDeviceData(string $device)
    {
        $device = strtolower($device);

        if (!in_array($device, $this->deviceList)) {
            abort(404, 'Device tidak ditemukan.');
        }

        return view('device', [
            'device' => $device,
            'items' => $this->fetchAllData($device),
        ]);
    }

    private function fetchLatestData(string $device): array
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $raw = $response->json('m2m:cin.con');
            if (empty($raw)) return ['nilai' => null];

            $nilai = $this->parseRawValue($device, $raw);

            if (is_numeric($nilai)) {
                $nilai = $this->adjustValue($device, $nilai);
                return ['nilai' => $this->formatNumber($nilai, $device)];
            }
        }

        return ['nilai' => null];
    }

    private function fetchAllData(string $device): array
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

        return collect($cinList)->map(function ($item) use ($device) {
            $parsedValue = $this->parseRawValue($device, $item['con'] ?? null);

            return [
                'ri' => $item['ri'] ?? 'no-ri',
                'time' => $item['ct'] ?? 'no-time',
                'value' => $parsedValue,
            ];
        })->toArray();
    }

    private function fetchRecentValues(string $device): array
    {
        $all = $this->fetchAllData($device);

        return collect($all)
            ->sortByDesc('time')
            ->take(7)
            ->map(function ($item) use ($device) {
                $value = is_array($item['value']) ? ($item['value']['nilai'] ?? $item['value'][$device] ?? null) : $item['value'];
                return is_numeric($value) ? $this->adjustValue($device, $value) : null;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function prepareChartData(array $results): array
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

    private function prepareReportSeries(array $results): array
    {
        $deviceMap = [
            'ph' => 'pH',
            'pota' => 'Potassium',
            'phospor' => 'Phospor',
            'EC' => 'EC',
            'Nitrogen' => 'Nitrogen',
            'humidity' => 'Kelembaban',
            'temp' => 'Temperatur Tanah',
        ];

        $series = [];
        $labels = []; // opsional untuk label x-axis jika pakai custom tick formatter
        $index = 0;

        foreach ($deviceMap as $key => $label) {
            $value = $this->parseNumber($results[$key]['nilai'] ?? null);
            if (is_numeric($value)) {
                $series[] = [
                    'name' => $label,
                    'data' => [[$index, round($value, 2)]]
                ];
                $labels[$index] = $label; // mapping index ke label
                $index++;
            }
        }

        return [
            'labels' => $labels,
            'series' => $series
        ];
    }


    private function prepareTimeSeriesChart(array $results): array
    {
        $labels = [];
        $data = [];

        $deviceMap = [
            'ph' => 'pH',
            'pota' => 'Potassium',
            'phospor' => 'Phospor',
            'EC' => 'EC',
            'Nitrogen' => 'Nitrogen',
            'humidity' => 'Kelembaban',
            'temp' => 'Temperatur Tanah',
        ];

        foreach ($deviceMap as $key => $label) {
            $labels[] = $label;
            $nilai = $this->parseNumber($results[$key]['nilai'] ?? null);
            $data[] = is_numeric($nilai) ? round($nilai, 2) : null;
        }

        return [
            'labels' => $labels,
            'series' => [[
                'name' => 'Data Sensor',
                'data' => $data,
            ]],
        ];
    }

    public function getBarChartData()
    {
        $results = [];
        foreach ($this->deviceList as $device) {
            // Pastikan fetchLatestData mengembalikan array dengan kunci 'nilai' atau null jika tidak ada data
            $data = $this->fetchLatestData($device);
            $results[$device] = is_array($data) ? $data : ['nilai' => null];
        }

        $barChartData = $this->buildSensorBarChart($results);

        return response()->json($barChartData);
    }

    private function buildSensorBarChart(array $results): array
    {
        $deviceMap = [
            'ph' => 'pH',
            'EC' => 'EC',
            'temp' => 'Temperatur Tanah',
            'Nitrogen' => 'Nitrogen',
            'humidity' => 'Kelembaban',
            'phospor' => 'Phospor',
            'pota' => 'Potassium',
        ];

        $labels = [];
        $data = [];

        foreach ($deviceMap as $key => $label) {
            $labels[] = $label;

            // Pastikan index ada dan nilai valid angka
            $nilai = isset($results[$key]['nilai']) && is_numeric($results[$key]['nilai'])
                ? round($results[$key]['nilai'], 2)
                : 0;

            $data[] = $nilai;
        }

        return [
            'labels' => $labels,
            'series' => [[
                'name' => 'Data Sensor',
                'data' => $data,
            ]]
        ];
    }

    private function adjustValue(string $device, $value)
    {
        if (!is_numeric($value)) return $value;

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
            return (int)$value;
        }

        return $value;
    }

    private function formatNumber($value, string $device): string
    {
        if (!is_numeric($value)) return $value;

        $formatted = (fmod($value, 1) !== 0.0)
            ? number_format($value, 2, ',', '.')
            : number_format($value, 0, ',', '.');

        return match (strtolower($device)) {
            'ec' => "$formatted dS/m",
            'humidity' => "$formatted %",
            'temp' => "$formatted °C",
            default => $formatted,
        };
    }

    private function parseNumber($value): ?float
    {
        if (is_null($value)) return null;

        $clean = preg_replace('/[^\d,.-]/', '', str_replace(',', '.', $value));
        return is_numeric($clean) ? (float)$clean : null;
    }

    private function parseRawValue(?string $device, $raw)
    {
        if (is_string($raw)) {
            $cleaned = trim($raw, "'\"");
            $decoded = json_decode($cleaned, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $device = strtolower($device);
                return $decoded['nilai'] ?? $decoded[$device] ?? collect($decoded)->first();
            }

            return $cleaned;
        }

        return $raw;
    }

    public function dashboardChart()
    {
        $chartData = [];
        $chartLabels = [];

        foreach ($this->deviceList as $device) {
            $allData = $this->fetchAllData($device);

            $formattedData = collect($allData)
                ->filter(fn($item) => isset($item['time'], $item['value']))
                ->sortByDesc('time')
                ->take(10)
                ->reverse();

            $labels = [];
            $values = [];

            foreach ($formattedData as $entry) {
                $labels[] = Carbon::parse($entry['time'])->format('H:i');
                $value = is_array($entry['value']) ? ($entry['value']['nilai'] ?? $entry['value'][$device] ?? null) : $entry['value'];
                $values[] = is_numeric($value) ? $this->adjustValue($device, $value) : null;
            }

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
