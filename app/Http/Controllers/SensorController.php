<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Str;

class SensorController extends Controller
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

    // Show a list of all sensors and the latest data for each device
    public function index()
{
    $sensorsManual = Sensor::all()
        ->filter(fn($item) => is_numeric($item->value))
        ->map(fn($item) => [
            'id' => $item->id,
            'parameter' => $item->parameter,
            'ri' => $item->ri,
            'time' => $item->waktu,
            'value' => $item->value,
            'source' => 'manual'
        ]);

    return view('sensor.index', [
        'dataSensor' => $sensorsManual,
    ]);
}

    // Show the form to create a new sensor
    public function create()
    {
        return view('sensor.create');
    }

    // Store a new sensor in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parameter' => 'required|string|max:255',
            'value' => 'required|numeric',
            'time' => 'required|date',
            'ri' => 'required|string|max:255',
        ]);

        // Store the data
        Sensor::create([
            'parameter' => $validated['parameter'],
            'value' => $validated['value'],
            'time' => $validated['time'],
            'ri' => $validated['ri'],
        ]);

        return redirect()->route('sensor.index')->with('success', 'Data sensor berhasil ditambahkan.');
    }


    // Show the form to edit an existing sensor
    public function edit(Sensor $sensor)
    {
        return view('sensor.edit', compact('sensor'));
    }

    // Update an existing sensor
    public function update(Request $request, Sensor $sensor)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $sensor->update($validatedData);
            return redirect()->route('sensor.index')->with('success', 'Sensor berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui sensor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui sensor.');
        }
    }

    // Delete an existing sensor
    public function destroy(Sensor $sensor)
    {
        try {
            $sensor->delete();
            return redirect()->route('sensor.index')->with('success', 'Sensor berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus sensor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus sensor.');
        }
    }

    // Fetch and display data for a specific device
    public function getDeviceData($device)
    {
        if (!in_array($device, $this->deviceList)) {
            abort(404, 'Device tidak ditemukan.');
        }

        $data = $this->fetchAllData($device);

        return view('sensor.index', [
            'device' => $device,
            'items' => $data,
        ]);
    }

    // Fetch the latest data for a specific device
    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($url);

        if (!$response->successful()) {
            return [
                'value' => 'Gagal ambil data',
                'time' => '-',
                'ri' => '-',
            ];
        }

        $cin = $response->json('m2m:cin') ?? null;
        if (!$cin) {
            return [
                'value' => 'no-value',
                'time' => 'no-time',
                'ri' => 'no-ri',
            ];
        }

        $value = $this->parseValue($cin['con'] ?? null, $device);
        $formattedTime = $this->formatTime($cin['ct'] ?? ($cin['creationTime'] ?? null));

        return [
            'value' => $value ?? 'no-value',
            'time' => $formattedTime,
            'ri' => $cin['ri'] ?? 'no-ri',
        ];
    }

    // Fetch all data for a specific device
    private function fetchAllData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}?rcn=4";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
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
            $value = $this->parseValue($item['con'] ?? null, $device);
            $formattedTime = $this->formatTime($item['ct'] ?? null);

            $result[] = [
                'ri' => $item['ri'] ?? 'no-ri',
                'time' => $formattedTime,
                'value' => $value ?? 'no-value',
            ];
        }

        return $result;
    }

    // Parse value based on device type
    private function parseValue($value, $device = null)
    {
        if (is_string($value)) {
            $value = trim($value, "\"'");

            // Coba parse JSON jika memungkinkan
            if (Str::startsWith($value, '{') || Str::startsWith($value, '[')) {
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $value = $decoded;
                } else {
                    Log::error("Gagal parse JSON untuk device {$device}. Value: " . $value);
                    return null;
                }
            }
        }

        if (is_array($value)) {
            $value = $value[$device] ?? reset($value);
        }

        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }

        if (is_numeric($value)) {
            return in_array(strtolower($device), ['pota', 'phospor', 'nitrogen'])
                ? round($value, 2)
                : round($value / 100, 2);
        }

        Log::warning("Format data tidak valid untuk device {$device}: " . json_encode($value));
        return null;
    }



    // Format timestamp to readable time
    private function formatTime($rawTime)
    {
        if (!$rawTime) return 'no-time';

        try {
            return Carbon::createFromFormat('Ymd\THis', substr($rawTime, 0, 15))->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::error("Format waktu tidak sesuai: $rawTime");
            return 'invalid-time';
        }
    }

    // Fetch and store all device data
    public function fetchAndStoreAll()
    {
        $results = [];

        foreach ($this->deviceList as $device) {
            $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

            try {
                $response = Http::withHeaders([
                    'X-M2M-Origin' => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->get($url);

                if (!$response->successful()) {
                    $results[] = [
                        'device' => $device,
                        'success' => false,
                        'message' => 'Gagal mengambil data dari Antares'
                    ];
                    continue;
                }

                $cin = $response['m2m:cin'] ?? null;
                if (!$cin) {
                    $results[] = [
                        'device' => $device,
                        'success' => false,
                        'message' => 'Data tidak ditemukan dari Antares'
                    ];
                    continue;
                }

                // Parse nilai
                $valueRaw = $cin['con'] ?? null;
                $value = $this->parseValue($valueRaw, $device);

                if (!is_numeric($value)) {
                    $results[] = [
                        'device' => $device,
                        'success' => false,
                        'message' => 'Format data tidak valid'
                    ];
                    continue;
                }

                // Waktu format
                $rawTime = $cin['ct'] ?? ($cin['creationTime'] ?? null);
                try {
                    $waktu = Carbon::createFromFormat('Ymd\THis', substr($rawTime, 0, 15))->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $waktu = now()->format('Y-m-d H:i:s');
                }

                // Simpan data
                Sensor::updateOrCreate(
                    [
                        'parameter' => strtoupper($device),
                        'ri'        => $cin['ri'] ?? 'no-ri',
                        'waktu'     => $waktu,
                    ],
                    [
                        'value'     => $value
                    ]
                );

                $results[] = [
                    'device' => $device,
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
                    'value' => $value
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'device' => $device,
                    'success' => false,
                    'message' => 'Exception: ' . $e->getMessage()
                ];
            }
        }

        return response()->json([
            'message' => 'Selesai menyimpan semua data sensor',
            'results' => $results
        ]);
    }



    // Show detailed data for a sensor
    public function show($id)
    {
        $sensor = Sensor::findOrFail($id);
        return view('sensor.show', compact('sensor'));
    }

    // Export sensor data to CSV
    public function export()
    {
        $filePath = storage_path('app/public/data-semua-sensor.csv');
        $writer = SimpleExcelWriter::create($filePath);
        $writer->addRow(['Parameter', 'Resource ID', 'Waktu', 'Nilai']);
        $anyData = false;

        foreach ($this->deviceList as $device) {
            $data = $this->fetchLatestData($device);

            if (!empty($data)) {
                $writer->addRow([
                    $device,
                    $data['ri'] ?? '',
                    $data['time'] ?? '',
                    $data['value'] ?? '',
                ]);
                $anyData = true;
            }
        }

        if (!$anyData) {
            return back()->with('error', 'Tidak ada data sensor yang tersedia untuk diekspor.');
        }

        return response()->download($filePath)->deleteFileAfterSend();
    }
}
