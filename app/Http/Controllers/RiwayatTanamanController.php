<?php

namespace App\Http\Controllers;

use App\Models\RiwayatTanaman;
use App\Models\Tanaman;
use App\Models\PeriodeTanam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RiwayatTanamanController extends Controller
{
    public function index(Request $request)
    {
        $query = PeriodeTanam::with('tanaman');
    
        if ($request->filled('filter_tanaman_id')) {
            $query->where('tanaman_id', $request->filter_tanaman_id);
        }
    
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu', '>=', $request->tanggal_mulai);
        }
    
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu', '<=', $request->tanggal_akhir);
        }
    
        $periode_tanams = $query->orderBy('waktu', 'desc')->get();
        $tanamans = Tanaman::all();
    
        return view('riwayat_tanaman.index', compact('periode_tanams', 'tanamans'));
    }
    

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'tanaman_id'        => 'required|exists:tanaman,id',
    //         'periode_tanam_id'  => 'nullable|exists:periode_tanam,id',
    //         'nama_periode'      => 'nullable|string|max:255',
    //         'waktu'             => 'nullable|date',
    //         'pupuk'             => 'nullable|string|max:255',
    //         'panjang_daun'      => 'nullable|numeric',
    //         'lebar_daun'        => 'nullable|numeric',
    //         'foto'              => 'nullable|image|max:2048',
    //         'ph'                => 'nullable|numeric',
    //         'potasium'          => 'nullable|numeric',
    //         'phospor'           => 'nullable|numeric',
    //         'EC'                => 'nullable|numeric',
    //         'Nitrogen'          => 'nullable|numeric',
    //         'humidity'          => 'nullable|numeric',
    //         'temp'              => 'nullable|numeric',
    //     ]);

    //     if ($request->hasFile('foto')) {
    //         $validated['foto'] = $request->file('foto')->store('foto_riwayat', 'public');
    //     }

    //     RiwayatTanaman::create($validated);

    //     return redirect()->route('riwayat_tanaman.index')->with('success', 'Data riwayat tanam berhasil ditambahkan.');
    // }

    // public function update(Request $request, $id)
    // {
    //     $riwayat = RiwayatTanaman::findOrFail($id);

    //     $validated = $request->validate([
    //         'tanaman_id'        => 'required|exists:tanaman,id',
    //         'periode_tanam_id'  => 'nullable|exists:periode_tanam,id',
    //         'nama_periode'      => 'nullable|string|max:255',
    //         'waktu'             => 'nullable|date',
    //         'pupuk'             => 'nullable|string|max:255',
    //         'panjang_daun'      => 'nullable|numeric',
    //         'lebar_daun'        => 'nullable|numeric',
    //         'foto'              => 'nullable|image|max:2048',
    //         'ph'                => 'nullable|numeric',
    //         'potasium'          => 'nullable|numeric',
    //         'phospor'           => 'nullable|numeric',
    //         'EC'                => 'nullable|numeric',
    //         'Nitrogen'          => 'nullable|numeric',
    //         'humidity'          => 'nullable|numeric',
    //         'temp'              => 'nullable|numeric',
    //     ]);

    //     if ($request->hasFile('foto')) {
    //         if ($riwayat->foto && Storage::disk('public')->exists($riwayat->foto)) {
    //             Storage::disk('public')->delete($riwayat->foto);
    //         }

    //         $validated['foto'] = $request->file('foto')->store('foto_riwayat', 'public');
    //     }

    //     $riwayat->update($validated);

    //     return redirect()->route('riwayat_tanaman.index')->with('success', 'Data riwayat tanam berhasil diperbarui.');
    // }

    // public function destroy($id)
    // {
    //     $riwayat = RiwayatTanaman::findOrFail($id);

    //     if ($riwayat->foto && Storage::disk('public')->exists($riwayat->foto)) {
    //         Storage::disk('public')->delete($riwayat->foto);
    //     }

    //     $riwayat->delete();

    //     return redirect()->route('riwayat_tanaman.index')->with('success', 'Data riwayat tanam berhasil dihapus.');
    // }
}
