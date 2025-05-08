<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    // Tampilkan semua mitra
    public function index()
    {
        $mitras = Mitra::all();
        return view('mitra.index', compact('mitras'));
    }

    // Tampilkan form create (jika ada halaman terpisah)
    public function create()
    {
        return view('mitra.create');
    }

    // Simpan mitra baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'lokasi'      => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:mitras,email',
            'telepon'     => 'required|string|max:20',
            'foto_mitra'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_mitra')) {
            $filename = $request->file('foto_mitra')->store('mitras', 'public');
            $validated['foto_mitra'] = $filename;
        }

        Mitra::create($validated);

        return redirect()->route('mitra.index')->with('success', 'Data mitra berhasil ditambahkan.');
    }

    // Tampilkan detail mitra (jika pakai halaman khusus)
    public function show($id)
    {
        $mitra = Mitra::findOrFail($id);
        return view('mitra.show', compact('mitra'));
    }

    // Tampilkan form edit mitra
    public function edit(Mitra $mitra)
    {
        return view('mitra.edit', compact('mitra'));
    }

    // Update data mitra
    public function update(Request $request, Mitra $mitra)
    {
        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'lokasi'      => 'required|string|max:255',
            'email'       => 'required|email|unique:mitras,email,' . $mitra->id,
            'telepon'     => 'required|string|max:20',
            'foto_mitra'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto_mitra')) {
            // Hapus foto lama jika ada
            if ($mitra->foto_mitra && Storage::disk('public')->exists($mitra->foto_mitra)) {
                Storage::disk('public')->delete($mitra->foto_mitra);
            }

            // Simpan foto baru
            $filename = $request->file('foto_mitra')->store('mitras', 'public');
            $validated['foto_mitra'] = $filename;
        }

        $mitra->update($validated);

        return redirect()->route('mitra.index')->with('success', 'Data mitra berhasil diperbarui.');
    }

    // Hapus mitra
    public function destroy(Mitra $mitra)
    {
        try {
            if ($mitra->foto_mitra && Storage::disk('public')->exists($mitra->foto_mitra)) {
                Storage::disk('public')->delete($mitra->foto_mitra);
            }

            $mitra->delete();

            return redirect()->route('mitra.index')->with('success', 'Data mitra berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mitra.index')->with('error', 'Gagal menghapus data mitra.');
        }
    }
}
