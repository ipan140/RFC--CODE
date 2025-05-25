<?php

namespace App\Http\Controllers;

use App\Models\KategoriSampel;
use App\Models\PeriodeTanam;
use Illuminate\Http\Request;

class KategoriSampelController extends Controller
{
    /**
     * Tampilkan daftar kategori sampel, dengan filter berdasarkan periode tanam (jika ada).
     */
    public function index(Request $request)
    {
        $request->validate([
            'periode_tanam_id' => 'nullable|exists:periode_tanams,id',
        ]);

        $query = KategoriSampel::with('periodeTanam');

        if ($request->filled('periode_tanam_id')) {
            $query->where('periode_tanam_id', $request->periode_tanam_id);
        }

        $kategoriSampels = $query->get();
        $periodeTanams = PeriodeTanam::all();

        return view('kategori_sampel.index', compact('kategoriSampels', 'periodeTanams'));
    }

    /**
     * Tampilkan form untuk menambahkan kategori sampel baru.
     */
    public function create()
    {
        $periodeTanams = PeriodeTanam::all();
        return view('kategori_sampel.create', compact('periodeTanams'));
    }

    /**
     * Simpan data kategori sampel baru ke database.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'periode_tanam_id' => 'required|exists:periode_tanams,id', // Wajib sekarang
        'nama' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
    ]);

    // Cek data yang tervalidasi


    KategoriSampel::create([
        'periode_tanam_id' => $validatedData['periode_tanam_id'],
        'nama' => $validatedData['nama'],
        'deskripsi' => $validatedData['deskripsi'] ?? null,
    ]);

    return redirect()->route('kategori_sampel.index')->with('success', 'Kategori Sampel berhasil ditambahkan.');
}
    /**
     * Tampilkan form edit untuk kategori sampel tertentu.
     */
    public function edit($id)
    {
        $kategori = KategoriSampel::findOrFail($id);
        $periodeTanams = PeriodeTanam::all();

        return view('kategori_sampel.edit', compact('kategori', 'periodeTanams'));
    }

    /**
     * Perbarui data kategori sampel yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'periode_tanam_id' => 'required|exists:periode_tanams,id',
        ]);

        $kategori = KategoriSampel::findOrFail($id);
        $kategori->update($validated);

        return redirect()->route('kategori_sampel.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori sampel berdasarkan ID.
     */
    public function destroy($id)
    {
        $kategori = KategoriSampel::findOrFail($id);
        $kategori->delete();

        return redirect()->route('kategori_sampel.index')->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Ambil daftar kategori berdasarkan ID periode tanam (digunakan misalnya untuk AJAX).
     */
    public function getByPeriodeTanam($periode_tanam_id)
    {
        $kategoriSampels = KategoriSampel::where('periode_tanam_id', $periode_tanam_id)->get();
        return response()->json($kategoriSampels);
    }
}
