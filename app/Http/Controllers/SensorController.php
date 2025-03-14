<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;

class SensorController extends Controller
{
    // Menampilkan daftar sensor
    public function index()
    {
        $sensors = Sensor::all();
        return view('sensor', compact('sensors'));
    }

    // Menampilkan form tambah sensor
    public function create()
    {
        return view('tambah_sensor', [
            'title' => 'Tambah Sensor',
            'username' => 'Admin',
            'roles' => 'Admin',
            'image' => 'pakdekan.png',
            'nama_lengkap' => 'Dr. Helmy Widyantara'
        ]);
    }

    // Menyimpan sensor baru ke database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        Sensor::create($validatedData);

        return redirect()->route('sensor.index')->with('success', 'Sensor berhasil ditambahkan!');
    }

    // Menampilkan form edit sensor
    public function edit(Sensor $sensor)
    {
        return view('edit_sensor', compact('sensor'));
    }

    // Menyimpan perubahan sensor ke database
    public function update(Request $request, Sensor $sensor)
    {
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        $sensor->update($validatedData);

        return redirect()->route('sensor.index')->with('success', 'Sensor berhasil diperbarui!');
    }

    // Menghapus sensor dari database
    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return redirect()->route('sensor.index')->with('success', 'Sensor berhasil dihapus!');
    }
}
