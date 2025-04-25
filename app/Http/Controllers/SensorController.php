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
        return view('sensor.index', compact('sensors'));
    }

    // Menampilkan form tambah sensor
    public function create()
    {
        return view('sensor.create', [
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
        // Validasi data input
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        // Menyimpan data sensor baru
        Sensor::create($validatedData);

        // Redirect ke daftar sensor dengan pesan sukses
        return redirect()->route('sensor.index')->with('success', 'Sensor berhasil ditambahkan!');
    }

    // Menampilkan form edit sensor
    public function edit(Sensor $sensor)
    {
        return view('sensor.edit', compact('sensor'));
    }

    // Menyimpan perubahan sensor ke database
    public function update(Request $request, Sensor $sensor)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        // Memperbarui data sensor
        $sensor->update($validatedData);

        // Redirect ke daftar sensor dengan pesan sukses
        return redirect()->route('sensor.index')->with('success', 'Sensor berhasil diperbarui!');
    }

    // Menghapus sensor dari database
    public function destroy(Sensor $sensor)
    {
        // Menghapus data sensor
        $sensor->delete();

        // Redirect ke daftar sensor dengan pesan sukses
        return redirect()->route('sensor.index')->with('success', 'Sensor berhasil dihapus!');
    }
}
