<?php

namespace App\Http\Controllers;

use App\Models\InputHarian;
use App\Models\PeriodeTanam;
use App\Models\KategoriSampel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class InputanHarianController extends Controller
{
    protected $apiKey = 'a74d1dcd4e6e87bf:b84bfdd53b7d2f84';
    protected $appName = 'interest';
    protected $deviceList = ['ph', 'pota', 'phospor', 'EC', 'Nitrogen', 'humidity', 'temp'];


    public function index(Request $request)
    {
        $query = InputHarian::with('periodeTanam');

        if ($request->filled('filter_periode_tanam_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_tanam_id);
        }

        // Ambil hasil query dengan sorting dan pagination, serta sertakan query string untuk menjaga filter saat pindah halaman
        $inputHarians = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Ambil periode tanam yang sedang berlangsung, sertakan relasi ke tanaman jika perlu
        $periodeTanams = PeriodeTanam::where('status', 'on going')->get();

        $kategoriSampels = KategoriSampel::all();

        return view('input_harian.index', compact(
            'inputHarians',
            'periodeTanams',
            'kategoriSampels'
        ));
    }


    public function create()
    {
        $periodeTanams = PeriodeTanam::with('periode_tanam')->where('status', 'on going')->get();
        $kategoriSampels = KategoriSampel::all();
        return view('input_harian.create', compact('periode_tanams', 'kategoriSampels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_tanam_id' => 'required|exists:periode_tanams,id',
            'kategori_sampel_id' => 'required|exists:kategori_sampel,id',
            'pupuk' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'waktu' => 'nullable|date',
            'panjang_daun' => 'nullable|numeric',
            'lebar_daun' => 'nullable|numeric',
            'ph' => 'nullable|numeric',
            'pota' => 'nullable|numeric',
            'phospor' => 'nullable|numeric',
            'EC' => 'nullable|numeric',
            'Nitrogen' => 'nullable|numeric',
            'humidity' => 'nullable|numeric',
            'temp' => 'nullable|numeric',
        ]);

        $mapping = [
            'ph' => 'ph',
            'pota' => 'pota',
            'phospor' => 'phospor',
            'EC' => 'EC',
            'Nitrogen' => 'Nitrogen',
            'humidity' => 'humidity',
            'temp' => 'temp',
        ];

        $data = $request->only([
            'periode_tanam_id',
            'kategori_sampel_id',
            'pupuk',
            'panjang_daun',
            'lebar_daun',
        ]);

        $data['waktu'] = $request->filled('waktu') ? Carbon::parse($request->waktu) : Carbon::now();

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

        // Simpan ke database
        try {
            InputHarian::create($data);
            return redirect()->back()->with('success', 'Data Periode Tanam berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function show(InputHarian $periodeTanam)
    {
        $periodeTanam->load('periodeTanam');
        return view('periode_tanam.show', compact('periodeTanam'));
    }

    public function edit(InputHarian $input_harian)
    {
        $tanamans = PeriodeTanam::all();
        return view('input_harian.edit', compact('input_harian', 'tanamans'));
    }

    public function update(Request $request, InputHarian $input_harian)
    {
        $request->validate([
            'periode_tanam_id' => 'nullable|exists:periode_tanam,id',
            'pupuk' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'waktu' => 'nullable|date',
            'panjang_daun' => 'nullable|numeric',
            'lebar_daun' => 'nullable|numeric',
        ]);

        $data = $request->only(['periode_tanam_id', 'pupuk', 'panjang_daun', 'lebar_daun']);

        if ($request->hasFile('foto')) {
            if ($input_harian->foto && file_exists(public_path('uploads/foto_periode/' . $input_harian->foto))) {
                unlink(public_path('uploads/foto_periode/' . $input_harian->foto));
            }

            $foto = $request->file('foto');
            $fotoName = time() . '.' . $foto->getClientOriginalExtension();
            $foto->move(public_path('uploads/foto_periode'), $fotoName);
            $data['foto'] = $fotoName;
        }

        if ($request->filled('waktu')) {
            $data['waktu'] = Carbon::parse($request->waktu);
        }

        $input_harian->update($data);

        return redirect()->route('input_harian.index')->with('success', 'Input harian berhasil diperbarui.');
    }

    public function destroy(InputHarian $input_harian)
    {
        if ($input_harian->foto && file_exists(public_path('uploads/foto_periode/' . $input_harian->foto))) {
            unlink(public_path('uploads/foto_periode/' . $input_harian->foto));
        }

        $input_harian->delete();

        return redirect()->route('input_harian.index')->with('success', 'Periode Tanam berhasil dihapus.');
    }

    private function fetchSensorValueFromAntares($device)
    {
        $deviceLower = strtolower($device);
        if (!in_array($deviceLower, array_map('strtolower', $this->deviceList))) {
            return null;
        }

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

            $cin = $response->json('m2m:cin');
            $value = $cin['con'] ?? null;

            if (is_string($value)) {
                $value = trim($value, "\"'");
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $value = $decoded;
                }
            }

            if (is_array($value) && isset($value[$deviceLower])) {
                return floatval($value[$deviceLower]);
            }

            return is_numeric($value) ? floatval($value) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function adjustValue($device, $value)
    {
        if (!is_numeric($value)) return $value;

        $deviceLower = strtolower($device);

        $adjustments = [
            'ph' => 100,
            'ec' => 100,
            'humidity' => 10,
            'moisture' => 10,
            'temp' => 10,
        ];

        $integerDevices = ['pota', 'phospor', 'nitrogen'];

        if (isset($adjustments[$deviceLower])) {
            return $value / $adjustments[$deviceLower];
        } elseif (in_array($deviceLower, $integerDevices)) {
            return (int) $value;
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
            default => $formatted,
        };
    }

    private function parseNumber($value)
    {
        if (is_null($value)) return null;

        $clean = str_replace(['.', ',', 'dS/m', '%', '°C'], ['', '.', '', '', ''], $value);

        return is_numeric($clean) ? (float) $clean : null;
    }
}
