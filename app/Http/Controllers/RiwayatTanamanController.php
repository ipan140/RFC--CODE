<?php

namespace App\Http\Controllers;

use App\Models\InputHarian;
use App\Models\PeriodeTanam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RiwayatTanamanController extends Controller
{
    public function index(Request $request)
    {
        $query = InputHarian::with('periode');

        // Filter logika...
        if ($request->filled('filter_periode_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu', '<=', $request->tanggal_akhir);
        }

        $inputHarians = $query->orderBy('waktu', 'desc')->paginate(10);

        // ✅ Pastikan ini ada:
        $periodeTanams = PeriodeTanam::all();

        return view('riwayat_tanaman.index', compact('inputHarians', 'periodeTanams'));
    }


    public function export(Request $request)
    {
        // Ambil data dengan relasi periode tanam
        $query = InputHarian::with('periode');

        // Filter berdasarkan periode tanam (bukan tanaman)
        if ($request->filled('filter_periode_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu', '<=', $request->tanggal_akhir);
        }

        $input_harians = $query->orderBy('waktu', 'desc')->get();

        if ($input_harians->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        // Buat file CSV
        $filePath = storage_path('app/public/riwayat_tanam.csv');
        $writer = SimpleExcelWriter::create($filePath);

        // Header CSV
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

        // Data baris
        foreach ($input_harians as $inputHarian) {
            $writer->addRow([
                $inputHarian->periode->nama_tanaman ?? '-',
                $inputHarian->periode->nama_periode ?? '-',
                $inputHarian->waktu ?? '-',
                $inputHarian->pupuk ?? '-',
                $inputHarian->panjang_daun ?? '-',
                $inputHarian->lebar_daun ?? '-',
                $inputHarian->ph ?? '-',
                $inputHarian->pota ?? '-',
                $inputHarian->phospor ?? '-',
                $inputHarian->EC ?? '-',
                $inputHarian->Nitrogen ?? '-',
                $inputHarian->humidity ?? '-',
                $inputHarian->temp ?? '-',
            ]);
        }

        // Unduh dan hapus setelah dikirim
        return response()->download($filePath)->deleteFileAfterSend();
    }
}
