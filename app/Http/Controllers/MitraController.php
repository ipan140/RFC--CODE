<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    // Menampilkan halaman daftar mitra
    public function index()
    {
        $mitras = Mitra::all();
        return view('mitra.index', compact('mitras'));
    }
    public function create()
    {
        return view('mitra.create');
    }

    // Menyimpan data mitra baru dari modal create
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:mitras,email',
            'telepon' => 'required|string|max:15',
        ]);

        Mitra::create($validated);

        return redirect()->route('mitra.index')->with('success', 'Data mitra berhasil ditambahkan.');
    }

    // Menampilkan halaman edit mitra
    public function edit(Mitra $mitra)  // pakai Route Model Binding
    {
        return view('mitra.edit', compact('mitra'));
    }

    // Proses update data mitra
    public function update(Request $request, Mitra $mitra)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:mitras,email,' . $mitra->id,
            'telepon' => 'required|string|max:15',
        ]);

        $mitra->update($validated);

        return redirect()->route('mitra.index')->with('success', 'Data mitra berhasil diperbarui.');
    }

    public function show($id)
    {
        $mitra = Mitra::findOrFail($id); // Ambil data mitra berdasarkan ID
        return view('mitra.show', compact('mitra'));
    }


    // Proses hapus mitra
    public function destroy(Mitra $mitra)
    {
        try {
            $mitra->delete();
            return redirect()->route('mitra.index')->with('success', 'Data mitra berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mitra.index')->with('error', 'Gagal menghapus data mitra.');
        }
    }
}

