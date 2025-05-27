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
        // Ambil semua periode tanam beserta jumlah kategori sampel (kategori_sampels_count)
        $periodes = PeriodeTanam::withCount('kategoriSampels')->get();

        // Ambil input harian beserta relasi periode tanam (paginate)
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
        $periode = PeriodeTanam::with('kategoriSampel')->find($id);

        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Periode tanam tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'kategori_sampel' => $periode->kategoriSampel ?? [],
            'status' => $periode->status,
            'nama_tanaman' => $periode->nama_tanaman,
            'tanggal_tanam' => $periode->tanggal_tanam,
        ]);
    }
}
