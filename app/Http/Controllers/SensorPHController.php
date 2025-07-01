<?php

namespace App\Http\Controllers;

use App\Models\SensorPH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SensorPHController extends Controller
{
    protected $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected $appName = 'interest';

    protected $deviceList = [
        'ph', 'pota', 'phospor', 'EC', 'Nitrogen', 'humidity', 'temp'
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

        return view('sensor_Ph.index', [
            'data' => $results
        ]);
    }

    public function getDeviceData($device)
    {
        if (!in_array($device, $this->deviceList)) {
            abort(404, 'Device tidak ditemukan.');
        }

        $data = $this->fetchAllData($device);

        return view('sensor_Ph.index', [
            'device' => $device,
            'items' => $data
        ]);
    }

    private function parseValue($rawValue)
    {
        if (is_string($rawValue)) {
            $rawValue = trim($rawValue, "\"'");
            $decoded = json_decode($rawValue, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $rawValue = $decoded;
            }
        }

        if (is_array($rawValue)) {
            return $rawValue['ph'] ?? 0;
        }

        if (is_numeric($rawValue)) {
            return floatval($rawValue) / 100; // ✅ Auto bagi 100
        }

        return 0;
    }

    private function formatTime($rawTime)
    {
        try {
            return $rawTime
                ? Carbon::createFromFormat('Ymd\THis', substr($rawTime, 0, 15))->format('Y-m-d H:i:s')
                : now()->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return now()->format('Y-m-d H:i:s');
        }
    }

    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if (!$response->successful()) {
            return [
                'value' => 'Gagal ambil data',
                'time' => '-',
                'ri' => '-',
            ];
        }

        $cin = $response['m2m:cin'] ?? null;
        //dd($cin);
        if (!$cin) {
            return [
                'value' => 'no-value',
                'time' => 'no-time',
                'ri' => 'no-ri',
            ];
        }

        return [
            'value' => $this->parseValue($cin['con'] ?? null),
            'time' => $this->formatTime($cin['ct'] ?? ($cin['creationTime'] ?? null)),
            'ri' => $cin['ri'] ?? 'no-ri',
        ];
    }
    private function formatSensorValue($device, $value)
    {
        if (!is_numeric($value)) {
            return '-';
        }
    
        // Format angka dengan koma sebagai pemisah ribuan dan 2 angka desimal
        $formattedValue = number_format($value, 2, '.', ',');
    
        // Tambahkan simbol untuk masing-masing perangkat
        switch (strtolower($device)) {
            case 'ph':
                return $formattedValue;
            case 'pota':
            case 'phospor':
            case 'nitrogen':
                return "{$formattedValue} ppm";
            case 'ec':
                return $formattedValue . " dS/m";
            case 'humidity':
                return $formattedValue . " %"; // Tambahkan simbol persen untuk humidity
            case 'temp':
                return $formattedValue . " °C";
            default:
                return $formattedValue;
        }
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

        $cinList = $response->json()['m2m:cnt']['m2m:cin'] ?? [];

        if (empty($cinList)) {
            return [['info' => 'Belum ada data untuk device ini']];
        }

        $result = [];

        foreach ($cinList as $item) {
            $result[] = [
                'ri' => $item['ri'] ?? 'no-ri',
                'time' => $this->formatTime($item['ct'] ?? null),
                'value' => $this->parseValue($item['con'] ?? null),
            ];
        }

        return $result;
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'time' => 'required|date',
            'resource_index' => 'required|string|max:255',
            'data' => 'required|numeric|between:0,14',
        ]);

        try {
            $validatedData['time'] = Carbon::parse($validatedData['time'])->toDateTimeString();
            SensorPH::create($validatedData);

            return redirect()->route('sensor_ph.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data.');
        }
    }

    public function update(Request $request, SensorPH $sensor)
    {
        $validatedData = $request->validate([
            'time' => 'required|date',
            'resource_index' => 'required|string|max:255',
            'data' => 'required|numeric|between:0,14',
        ]);

        try {
            $validatedData['time'] = Carbon::parse($validatedData['time'])->toDateTimeString();
            $sensor->update($validatedData);

            return redirect()->route('sensor_ph.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function destroy(SensorPH $sensor)
    {
        try {
            $sensor->delete();

            return redirect()->route('sensor_ph.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function export()
    {
        $device = 'ph';
        $cin = $this->fetchLatestData($device);

        if (empty($cin)) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        $filePath = storage_path('app/public/data-ph.csv');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addRow(['Resource ID', 'Waktu', 'Nilai']);
        $writer->addRow([
            $cin['ri'] ?? '',
            $cin['time'] ?? '',
            $cin['value'] ?? '',
        ]);

        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function fetchAndStore()
    {
        $device = 'ph';
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        try {
            $response = Http::withHeaders([
                'X-M2M-Origin' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($url);

            if (!$response->successful()) {
                return response()->json(['success' => false, 'message' => 'Gagal mengambil data dari Antares'], 500);
            }

            $cin = $response['m2m:cin'] ?? null;
            if (!$cin) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan dari Antares'], 404);
            }

            $parsedValue = $this->parseValue($cin['con'] ?? null);
            $formattedTime = $this->formatTime($cin['ct'] ?? ($cin['creationTime'] ?? null));

            SensorPH::updateOrCreate(
                [
                    'parameter' => 'ph',
                    'waktu' => $formattedTime,
                ],
                [
                    'ri' => $cin['ri'] ?? 'no-ri',
                    'value' => $parsedValue,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'data' => $parsedValue
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
