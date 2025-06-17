<?php

namespace App\Http\Controllers;

use App\Models\KategoriSampel;
use App\Models\PeriodeTanam;
use Illuminate\Http\Request;

class SampelController extends Controller
{
    /**
     * Menampilkan daftar kategori sampel, dengan filter opsional berdasarkan periode tanam.
     */
    public function index(Request $request)
    {
        // Validasi input request
        $request->validate([
            'periode_tanam_id' => 'nullable|exists:periode_tanams,id',
        ]);

        // Ambil data kategori sampel beserta relasi periode tanam, tanaman, dan hitung input harian
        $query = KategoriSampel::with('periodeTanam.tanaman')
            ->withCount('inputHarians');

        // Filter berdasarkan periode tanam jika disediakan
        if ($request->filled('periode_tanam_id')) {
            $query->where('periode_tanam_id', $request->periode_tanam_id);
        }

        $kategoriSampels = $query->get();
        $periodeTanams = PeriodeTanam::all();

        // Kirim data ke view
        return view('sampel.index', compact('kategoriSampels', 'periodeTanams'));
    }
}
