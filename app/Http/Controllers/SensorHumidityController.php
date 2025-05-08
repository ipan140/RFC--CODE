<?php

namespace App\Http\Controllers;

use App\Models\SensorHM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SensorHumidityController extends Controller
{
    protected $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected $appName = 'interest';

    protected $deviceList = [
        'humidity',
    ];

    public function index(Request $request)
    {
        $deviceParam = $request->query('device');

        if ($deviceParam) {
            return $this->getDeviceData($deviceParam);
        }

        // Ambil data humidity
        $results['humidity'] = $this->fetchLatestData('humidity');

        return view('sensor_humidity.index', ['data' => $results]);
    }

    public function getDeviceData($device)
    {
        if (!in_array($device, $this->deviceList)) {
            abort(404, 'Device tidak ditemukan.');
        }

        $data = $this->fetchAllData($device);

        return view('sensor_humidity.index', [
            'device' => $device,
            'items' => $data
        ]);
    }

    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        // Lakukan request HTTP
        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            // Ambil data CIN
            $cin = $response['m2m:cin'] ?? null;

            // Jika CIN tidak ada, kembalikan nilai default
            if (!$cin) {
                return ['value' => 'no-value', 'time' => 'no-time', 'ri' => 'no-ri'];
            }

            // Ambil dan proses nilai dari CIN
            $value = $cin['con'] ?? null;
            if (is_string($value)) {
                $value = trim($value, "\"'");

                // Decode jika JSON
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            // Jika perangkat adalah humidity, bagi dengan 100
            if ($device === 'humidity' && is_numeric($value)) {
                $value = $value / 100;
            }

            // Proses waktu, dengan pengecekan yang lebih baik
            try {
                $formattedTime = Carbon::createFromFormat('Ymd\THis', substr($cin['ct'], 0, 15))->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $formattedTime = 'invalid-time';
            }

            // Kembalikan data yang sudah diformat
            return [
                'value' => $this->formatSensorValue($device, $value) ?? 'no-value',
                'time' => $formattedTime,
                'ri' => $cin['ri'] ?? 'no-ri',
            ];
        }

        // Jika request gagal, kembalikan nilai default
        return ['value' => 'Gagal ambil data', 'time' => '-', 'ri' => '-'];
    }

    private function formatSensorValue($device, $value)
    {
        if (!is_numeric($value)) {
            return '-';
        }

        // Format angka dengan dua desimal dan koma sebagai pemisah ribuan
        $formattedValue = number_format($value, 2, '.', ',');

        // Tambahkan simbol persen untuk humidity
        if (strtolower($device) === 'humidity') {
            return $formattedValue . " %"; // Tambahkan simbol persen untuk humidity
        }

        return $formattedValue;
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

        $cinList = $response['m2m:cnt']['m2m:cin'] ?? [];

        if (empty($cinList)) {
            return [['info' => 'Belum ada data untuk device ini']];
        }

        $result = [];
        foreach ($cinList as $item) {
            $value = $item['con'] ?? null;
            if (is_string($value)) {
                $value = trim($value, "'");

                // Decode jika JSON
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'time' => 'required|date',
            'resource_index' => 'required|string|max:255',
            'data' => 'required|numeric|between:0,100',
        ]);

        try {
            $validatedData['time'] = Carbon::parse($validatedData['time'])->toDateTimeString();
            SensorHM::create($validatedData);
            return redirect()->route('sensor_humidity.index')->with('success', 'Data berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan data.');
        }
    }

    public function update(Request $request, SensorHM $sensor)
    {
        $validatedData = $request->validate([
            'time' => 'required|date',
            'resource_index' => 'required|string|max:255',
            'data' => 'required|numeric|between:0,100',
        ]);

        try {
            $validatedData['time'] = Carbon::parse($validatedData['time'])->toDateTimeString();
            $sensor->update($validatedData);
            return redirect()->route('sensor_humidity.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function destroy(SensorHM $sensor)
    {
        try {
            $sensor->delete();
            return redirect()->route('sensor_humidity.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function export()
    {
        $device = 'humidity';
        $cin = $this->fetchLatestData($device);

        if (empty($cin)) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        $value = $cin['value'] ?? '';
        $timeFormatted = $cin['time'] ?? '';
        $resourceId = $cin['ri'] ?? '';

        $filePath = storage_path('app/public/data-humidity.csv');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addRow(['Resource ID', 'Waktu', 'Nilai']);
        $writer->addRow([$resourceId, $timeFormatted, $value]);

        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function fetchAndStore()
    {
        $device = 'humidity'; // Ganti sesuai device
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

            // Ambil dan decode nilai
            $value = $cin['con'] ?? null;
            if (is_string($value)) {
                $value = trim($value, "\"'");
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            // Cek nilai sensor humidity
            $parsedValue = is_numeric($value) ? $value / 100 : 0;

            // Format waktu
            $rawTime = $cin['ct'] ?? ($cin['creationTime'] ?? null);
            try {
                $formattedTime = $rawTime
                    ? Carbon::createFromFormat('Ymd\THis', substr($rawTime, 0, 15))->format('Y-m-d H:i:s')
                    : now()->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $formattedTime = now()->format('Y-m-d H:i:s');
            }

            // Simpan ke DB
            SensorHM::updateOrCreate(
                [
                    'parameter' => 'humidity',
                    'waktu'     => $formattedTime,
                ],
                [
                    'ri'    => $cin['ri'] ?? 'no-ri',
                    'value' => $parsedValue
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
