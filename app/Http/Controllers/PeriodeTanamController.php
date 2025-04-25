<?php

namespace App\Http\Controllers;

use App\Models\PeriodeTanam;
use App\Models\Tanaman;
use Illuminate\Http\Request;

class PeriodeTanamController extends Controller
{
    /**
     * Menampilkan daftar semua periode tanam.
     */
    public function index(Request $request)
    {
        $query = PeriodeTanam::with('tanaman');

        if ($request->has('filter_tanaman_id') && $request->filter_tanaman_id != '') {
            $query->where('tanaman_id', $request->filter_tanaman_id);
        }

        $periode_tanams = $query->orderBy('tanggal_mulai', 'desc')->get();

        $tanamans = Tanaman::all();

        return view('periode_tanam.index', compact('periode_tanams', 'tanamans'));
    }

    /**
     * Menampilkan form untuk menambah periode tanam.
     */
    public function create()
    {
        $tanamans = Tanaman::all();
        return view('periode_tanam.create', compact('tanamans'));
    }

    /**
     * Menyimpan data periode tanam baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanaman_id'       => 'required|exists:tanaman,id',
            'nama_periode'     => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|in:Belum,Sudah',
            'keterangan'       => 'nullable|string',
        ]);

        PeriodeTanam::create($validated);

        return redirect()->route('periode_tanam.index')
            ->with('success', 'Periode Tanam berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari satu periode tanam.
     */
    public function show(PeriodeTanam $periodeTanam)
    {
        $periodeTanam->load('tanaman');

        return view('periode_tanam.show', compact('periodeTanam'));
    }

    /**
     * Menampilkan form edit untuk periode tanam.
     */
    public function edit(PeriodeTanam $periodeTanam)
    {
        $tanamans = Tanaman::all();

        return view('periode_tanam.edit', compact('periodeTanam', 'tanamans'));
    }

    /**
     * Memperbarui data periode tanam di database.
     */
    public function update(Request $request, PeriodeTanam $periodeTanam)
    {
        $validated = $request->validate([
            'tanaman_id'       => 'required|exists:tanaman,id',
            'nama_periode'     => 'required|string|max:255',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|in:Belum,Sudah',
            'keterangan'       => 'nullable|string',
        ]);

        $periodeTanam->update($validated); // ✅ update ke database

        return redirect()->route('periode_tanam.index')
            ->with('success', 'Periode Tanam berhasil diperbarui.');
    }


    /**
     * Menghapus data periode tanam dari database.
     */
    public function destroy(PeriodeTanam $periodeTanam)
    {
        $periodeTanam->delete();

        return redirect()->route('periode_tanam.index')
            ->with('success', 'Periode Tanam berhasil dihapus.');
    }
    
}
