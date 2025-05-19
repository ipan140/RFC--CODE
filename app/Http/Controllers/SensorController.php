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

    // Tampilkan daftar semua sensor manual
    public function index()
    {
        // Ambil data sensor terfilter dari database, langsung dengan query
        $sensorsManual = Sensor::whereRaw('value REGEXP "^[0-9]+(\.[0-9]+)?$"')
            ->orderByDesc('waktu')
            ->paginate(10);

        // Mapping data sesuai kebutuhan
        $dataSensor = $sensorsManual->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'parameter' => $item->parameter,
                'ri' => $item->ri,
                'time' => $item->waktu,
                'value' => $item->value,
                'source' => 'manual'
            ];
        });

        // Replace collection dengan data hasil map
        $sensorsManual->setCollection($dataSensor);

        return view('sensor.index', [
            'dataSensor' => $sensorsManual,
        ]);
    }


    // Form tambah data sensor manual
    public function create()
    {
        return view('sensor.create');
    }

    // Simpan data sensor manual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parameter' => 'required|string|max:255',
            'value' => 'required|numeric',
            'waktu' => 'required|date',
            'ri' => 'required|string|max:255',
        ]);

        Sensor::create($validated);

        return redirect()->route('sensor.index')->with('success', 'Data sensor berhasil ditambahkan.');
    }

    // Form edit sensor manual
    public function edit(Sensor $sensor)
    {
        return view('sensor.edit', compact('sensor'));
    }

    // Update data sensor manual
    public function update(Request $request, Sensor $sensor)
    {
        $validatedData = $request->validate([
            'parameter' => 'required|string|max:255',
            'value' => 'required|numeric',
            'waktu' => 'required|date_format:Y-m-d\TH:i',
            'ri' => 'required|string|max:255',
        ]);

        // Ubah waktu ke format yang sesuai DB: 'Y-m-d H:i:s'
        $validatedData['waktu'] = Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['waktu'])->format('Y-m-d H:i:s');

        try {
            $sensor->update($validatedData);
            return redirect()->route('sensor.index')->with('success', 'Sensor berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui sensor: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui sensor.');
        }
    }


    public function show(Sensor $sensor)
    {
        return view('sensor.show', compact('sensor'));
    }

    // Hapus data sensor
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

    // Ambil dan tampilkan data realtime dari device Antares
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

    // Ambil data terakhir dari satu device
    private function fetchLatestData($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        $response = Http::withHeaders([
            'X-M2M-Origin' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->get($url);

        if (!$response->successful()) {
            return ['value' => 'Gagal ambil data', 'time' => '-', 'ri' => '-'];
        }

        $cin = $response->json('m2m:cin') ?? null;
        if (!$cin) {
            return ['value' => 'no-value', 'time' => 'no-time', 'ri' => 'no-ri'];
        }

        $value = $this->parseValue($cin['con'] ?? null, $device);
        $formattedTime = $this->formatTime($cin['ct'] ?? ($cin['creationTime'] ?? null));

        return [
            'value' => $value ?? 'no-value',
            'time' => $formattedTime,
            'ri' => $cin['ri'] ?? 'no-ri',
        ];
    }

    // Ambil semua data dari satu device
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

        return collect($cinList)->map(function ($item) use ($device) {
            return [
                'ri' => $item['ri'] ?? 'no-ri',
                'time' => $this->formatTime($item['ct'] ?? null),
                'value' => $this->parseValue($item['con'] ?? null, $device),
            ];
        })->toArray();
    }

    // Parsing nilai dari data Antares
    private function parseValue($value, $device = null)
    {
        if (is_string($value)) {
            $value = trim($value, "\"'");
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

    // Format waktu dari Antares ke format Laravel
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

    // Ambil data terbaru dari semua device dan simpan ke DB
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

                $value = $this->parseValue($cin['con'] ?? null, $device);

                if (!is_numeric($value)) {
                    $results[] = [
                        'device' => $device,
                        'success' => false,
                        'message' => 'Format data tidak valid'
                    ];
                    continue;
                }

                $rawTime = $cin['ct'] ?? ($cin['creationTime'] ?? null);
                try {
                    $waktu = Carbon::createFromFormat('Ymd\THis', substr($rawTime, 0, 15))->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $waktu = now()->format('Y-m-d H:i:s');
                }

                Sensor::updateOrCreate(
                    [
                        'parameter' => strtoupper($device),
                        'ri' => $cin['ri'] ?? 'no-ri',
                        'waktu' => $waktu,
                    ],
                    ['value' => $value]
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

    // Export semua data sensor ke file CSV
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
