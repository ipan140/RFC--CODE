<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProyekController extends Controller
{
    /**
     * Menampilkan daftar proyek.
     */
    public function index()
    {
        $proyeks = Proyek::all();
        return view('proyek.index', compact('proyeks'));
    }

    /**
     * Menampilkan form tambah proyek.
     */
    public function create()
    {
        return view('proyek.create');
    }

    /**
     * Menyimpan proyek baru ke database.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'deskripsi' => 'required',
        'tanggal' => 'required|date',
        'foto_proyek' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $fotoPath = null;

    if ($request->hasFile('foto_proyek')) {
        $fotoPath = $request->file('foto_proyek')->store('proyek_fotos', 'public');
    }

    Proyek::create([
        'nama' => $request->nama,
        'deskripsi' => $request->deskripsi,
        'tanggal' => $request->tanggal,
        'foto_proyek' => $fotoPath,
    ]);

    return redirect()->back()->with('success', 'Proyek berhasil ditambahkan!');
}
    public function show(Proyek $proyek)
    {
        return view('proyek.show', compact('proyek'));
    }

    /**
     * Menampilkan form edit proyek.
     */
    public function edit(Proyek $proyek)
    {
        return view('proyek.edit', compact('proyek'));
    }

    /**
     * Memperbarui data proyek.
     */

    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'foto_proyek' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ];

        // Cek apakah ada upload foto baru
        if ($request->hasFile('foto_proyek')) {
            // Hapus foto lama kalau ada
            if ($proyek->foto_proyek && Storage::exists('public/' . $proyek->foto_proyek)) {
                Storage::delete('public/' . $proyek->foto_proyek);
            }

            // Simpan foto baru
            $path = $request->file('foto_proyek')->store('foto_proyek', 'public');
            $data['foto_proyek'] = $path;
        }

        $proyek->update($data);

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Menghapus proyek.
     */
    public function destroy(Proyek $proyek)
    {
        $proyek->delete();

        return redirect()->route('proyek.index')->with('success', 'Proyek berhasil dihapus.');
    }
}
