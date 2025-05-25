<?php

namespace App\Http\Controllers;

use App\Models\PeriodeTanam;
use App\Models\InputHarian;
use Illuminate\Http\Request;

class TanamanController extends Controller
{
    /**
     * Menampilkan halaman daftar periode tanam dan input harian.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua periode tanam (beserta nama tanaman jika ada relasi)
        $periodes = PeriodeTanam::all();

        // Ambil input harian beserta relasi tanaman (untuk pagination di view)
        $inputHarians = InputHarian::with('periodeTanam')->paginate(10);

        return view('tanaman.index', compact('periodes', 'inputHarians'));
    }
    /**
     * Mengambil data kategori sampel berdasarkan ID periode tanam (AJAX).
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getKategoriPengamatan($id)
    {
        $periode = PeriodeTanam::find($id);

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tanam tidak ditemukan',
            ], 404);
        }

        // Ambil kategori sampel yang terkait dengan periode tanam ini
        // Misalnya: jika kategori_sampel punya relasi ke periode_tanam lewat periode_tanam_id
        $kategoriSampel = $periode->kategoriSampel ?? [];

        return response()->json([
            'success' => true,
            'kategori_sampel' => $kategoriSampel,
            'status' => $periode->status,
        ]);
    }
}
