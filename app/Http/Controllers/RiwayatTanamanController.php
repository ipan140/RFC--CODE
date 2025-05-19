<?php

namespace App\Http\Controllers;

use App\Models\Tanaman;
use App\Models\PeriodeTanam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

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

        // Pagination 10 data per halaman
        $periode_tanams = $query->orderBy('waktu', 'desc')->paginate(10);

        $tanamans = Tanaman::all();

        return view('riwayat_tanaman.index', compact('periode_tanams', 'tanamans'));
    }


    public function export(Request $request)
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

        if ($periode_tanams->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        $filePath = storage_path('app/public/riwayat_tanam.csv');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addRow([
            'Nama Tanaman',
            'Nama Periode',
            'Waktu',
            'Pupuk',
            'Panjang Daun',
            'Lebar Daun',
            'pH',
            'Potasium',
            'Phospor',
            'EC',
            'Nitrogen',
            'Humidity',
            'Suhu',
        ]);

        foreach ($periode_tanams as $periode) {
            $writer->addRow([
                $periode->tanaman->nama_tanaman ?? '-',
                $periode->nama_periode ?? '-',
                $periode->waktu ?? '-',
                $periode->pupuk ?? '-',
                $periode->panjang_daun ?? '-',
                $periode->lebar_daun ?? '-',
                $periode->ph ?? '-',
                $periode->pota ?? '-',
                $periode->phospor ?? '-',
                $periode->EC ?? '-',
                $periode->Nitrogen ?? '-',
                $periode->humidity ?? '-',
                $periode->temp ?? '-',
            ]);
        }

        return response()->download($filePath)->deleteFileAfterSend();
    }
}
