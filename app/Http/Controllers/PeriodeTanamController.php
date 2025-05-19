<?php

namespace App\Http\Controllers;

use App\Models\PeriodeTanam;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PeriodeTanamController extends Controller
{
    protected $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected $appName = 'interest';
    protected $deviceList = ['ph', 'pota', 'phospor', 'EC', 'Nitrogen', 'humidity', 'temp'];

    public function index(Request $request)
    {
        $query = PeriodeTanam::with('tanaman');

        if ($request->filled('filter_tanaman_id')) {
            $query->where('tanaman_id', $request->filter_tanaman_id);
        }

        // Ganti get() dengan paginate
        $periode_tanams = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $tanamans = Tanaman::all();

        return view('periode_tanam.index', compact('periode_tanams', 'tanamans'));
    }


    public function create()
    {
        $tanamans = Tanaman::all();
        return view('periode_tanam.create', compact('tanamans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanaman_id'   => 'required|exists:tanaman,id',
            'nama_periode' => 'required|string|max:255',
            'pupuk'        => 'nullable|string', // Keterangan diganti menjadi pupuk
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'waktu'        => 'nullable|date',
            'panjang_daun' => 'nullable|numeric',
            'lebar_daun'   => 'nullable|numeric',

            'ph'           => 'nullable|numeric',
            'pota'         => 'nullable|numeric',
            'phospor'      => 'nullable|numeric',
            'EC'           => 'nullable|numeric',
            'Nitrogen'     => 'nullable|numeric',
            'humidity'     => 'nullable|numeric',
            'temp'         => 'nullable|numeric',
        ]);

        $mapping = [
            'ph'       => 'ph',
            'pota'     => 'pota',
            'phospor'  => 'phospor',
            'EC'       => 'EC',
            'Nitrogen' => 'Nitrogen',
            'humidity' => 'humidity',
            'temp'     => 'temp',
        ];

        $data = $request->only([
            'tanaman_id',
            'nama_periode',
            'pupuk',
            'panjang_daun',
            'lebar_daun' // Keterangan diganti menjadi pupuk
        ]);

        $data['tanggal_mulai'] = $request->filled('waktu') ? Carbon::parse($request->waktu) : Carbon::now();

        foreach ($mapping as $inputKey => $dbField) {
            if ($request->filled($inputKey)) {
                $data[$dbField] = $this->adjustValue($inputKey, $request->input($inputKey));
            } else {
                $sensorValue = $this->fetchSensorValueFromAntares($inputKey);
                if ($sensorValue !== null) {
                    $data[$dbField] = $this->adjustValue($inputKey, $sensorValue);
                }
            }
        }

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto_periode'), $fotoName);
            $data['foto'] = $fotoName;
        }

        PeriodeTanam::create($data);

        return redirect()->back()->with('success', 'Data Periode Tanam berhasil disimpan!');
    }

    public function show(PeriodeTanam $periodeTanam)
    {
        $periodeTanam->load('tanaman');
        return view('periode_tanam.show', compact('periodeTanam'));
    }

    public function edit(PeriodeTanam $periodeTanam)
    {
        $tanamans = Tanaman::all();
        return view('periode_tanam.edit', compact('periodeTanam', 'tanamans'));
    }

    public function update(Request $request, PeriodeTanam $periodeTanam)
    {
        $request->validate([
            'tanaman_id'   => 'required|exists:tanaman,id',
            'nama_periode' => 'required|string|max:255',
            'pupuk'        => 'nullable|string', // Keterangan diganti menjadi pupuk
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'waktu'        => 'nullable|date',
            'panjang_daun' => 'nullable|numeric',
            'lebar_daun'   => 'nullable|numeric',
        ]);

        $data = $request->only([
            'tanaman_id',
            'nama_periode',
            'pupuk',
            'panjang_daun',
            'lebar_daun' // Keterangan diganti menjadi pupuk
        ]);

        if ($request->hasFile('foto')) {
            if ($periodeTanam->foto && file_exists(public_path('uploads/foto_periode/' . $periodeTanam->foto))) {
                unlink(public_path('uploads/foto_periode/' . $periodeTanam->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto_periode'), $fotoName);
            $data['foto'] = $fotoName;
        }

        if ($request->filled('waktu')) {
            $data['tanggal_mulai'] = Carbon::parse($request->waktu);
        }

        $periodeTanam->update($data);

        return redirect()->route('periode_tanam.index')->with('success', 'Periode Tanam berhasil diperbarui.');
    }

    public function destroy(PeriodeTanam $periodeTanam)
    {
        if ($periodeTanam->foto && file_exists(public_path('uploads/foto_periode/' . $periodeTanam->foto))) {
            unlink(public_path('uploads/foto_periode/' . $periodeTanam->foto));
        }

        $periodeTanam->delete();

        return redirect()->route('periode_tanam.index')->with('success', 'Periode Tanam berhasil dihapus.');
    }

    private function fetchSensorValueFromAntares($device)
    {
        $url = "https://platform.antares.id:8443/~/antares-cse/antares-id/{$this->appName}/{$device}/la";

        try {
            $response = Http::withHeaders([
                'X-M2M-Origin' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->get($url);

            if (!$response->successful()) {
                return null;
            }

            $cin = $response['m2m:cin'] ?? null;
            $value = $cin['con'] ?? null;

            if (is_string($value)) {
                $value = trim($value, "\"'");
                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            if (is_array($value) && isset($value[$device])) {
                return floatval($value[$device]);
            }

            return is_numeric($value) ? floatval($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function adjustValue($device, $value)
    {
        if (!is_numeric($value)) return $value;

        $device = strtolower($device);

        $adjustments = [
            'ph' => 100,
            'EC' => 100,
            'humidity' => 10,
            'moisture' => 10,
            'temp' => 10,
        ];

        $integerDevices = ['pota', 'phospor', 'Nitrogen'];

        if (isset($adjustments[$device])) {
            return $value / $adjustments[$device];
        } elseif (in_array($device, $integerDevices)) {
            return (int) $value;
        }

        return $value;
    }

    private function formatNumber($value, $device)
    {
        if (!is_numeric($value)) return $value;

        $formatted = fmod($value, 1) !== 0.0 ? number_format($value, 2, ',', '.') : number_format($value, 0, ',', '.');

        return match (strtolower($device)) {
            'ec' => $formatted . ' dS/m',
            'humidity' => $formatted . ' %',
            'temp' => $formatted . ' °C',
            default => $formatted
        };
    }

    private function parseNumber($value)
    {
        if (is_null($value)) return null;
        $clean = str_replace(['.', ',', 'dS/m', '%', '°C'], ['', '.', '', '', ''], $value);
        return is_numeric($clean) ? (float) $clean : null;
    }
}
