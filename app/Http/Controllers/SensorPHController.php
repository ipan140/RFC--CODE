<?php

namespace App\Http\Controllers;

use App\Models\SensorPH;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // <- Tambahkan ini
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter; // Tambahkan di bagian paling atas jika belum


class SensorPHController extends Controller
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

        return view('sensor_Ph.index', [
            'data' => $results // Pastikan variabel ini tersedia di blade
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

    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $cin = $response['m2m:cin'] ?? null;

            if (!$cin) {
                return [
                    'value' => 'no-value',
                    'time' => 'no-time',
                    'ri' => 'no-ri'
                ];
            }

            $value = $cin['con'] ?? null;

            if (is_string($value)) {
                $value = trim($value, "\"'");
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            $rawTime = $cin['ct'] ?? ($cin['creationTime'] ?? null);
            try {
                $formattedTime = $rawTime
                    ? Carbon::createFromFormat('Ymd\THis', substr($rawTime, 0, 15))->format('Y-m-d H:i:s')
                    : 'no-time';
            } catch (\Exception $e) {
                $formattedTime = 'invalid-time';
            }

            return [
                'value' => $value ?? 'no-value',
                'time' => $formattedTime,
                'ri' => $cin['ri'] ?? 'no-ri',
            ];
        }

        return [
            'value' => 'Gagal ambil data',
            'time' => '-',
            'ri' => '-',
        ];
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

    /**
     * Memperbarui data sensor.
     */
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

    /**
     * Menghapus data sensor dari database.
     */
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
        $device = 'ph'; // Ganti sesuai device
        $cin = $this->fetchLatestData($device);

        if (empty($cin)) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        $value = $cin['value'] ?? '';
        $timeFormatted = $cin['time'] ?? '';
        $resourceId = $cin['ri'] ?? '';

        $filePath = storage_path('app/public/data-ph.csv'); // Ubah nama file jadi 'ph'
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addRow(['Resource ID', 'Waktu', 'Nilai']);
        $writer->addRow([
            $resourceId,
            $timeFormatted,
            $value,
        ]);

        return response()->download($filePath)->deleteFileAfterSend();
    }

    public function fetchAndStore()
    {
        $device = 'ph'; // Ganti sesuai device
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
            SensorPh::updateOrCreate(
                [
                    'parameter' => 'ph',
                    'waktu'     => $formattedTime,
                ],
                [
                    'ri'    => $cin['ri'] ?? 'no-ri',
                    'value' => $value['ph'] ?? 0
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!',
                'data' => $value
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
