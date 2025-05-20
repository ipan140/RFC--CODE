<?php

namespace App\Http\Controllers;

use App\Models\InputHarian;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PeriodeTanamController extends Controller
{
    /**
     * Menampilkan daftar input harian yang belum selesai
     */
    public function index(Request $request)
    {
        $query = InputHarian::whereHas('tanaman', function ($q) {
            $q->where('status', '!=', 'selesai');
        })->with('tanaman');

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('waktu', [$request->tanggal_mulai, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_mulai')) {
            // Jika hanya tanggal_mulai diisi, cari data di tanggal tersebut
            $query->whereDate('waktu', $request->tanggal_mulai);
        }

        // Filter tanaman
        if ($request->filled('filter_tanaman_id')) {
            $query->where('tanaman_id', $request->filter_tanaman_id);
        }

        $inputHarians = $query->orderBy('waktu', 'desc')->paginate(10);
        $tanamans = Tanaman::where('status', '!=', 'selesai')->get();

        return view('periode_tanam.index', compact('inputHarians', 'tanamans'));
    }

    /**
     * Mengekspor data input harian ke CSV
     */
    public function export(Request $request)
    {
        $query = InputHarian::whereHas('tanaman', function ($q) {
            $q->where('status', '!=', 'selesai');
        })->with('tanaman');

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('waktu', [$request->tanggal_mulai, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu', $request->tanggal_mulai);
        }

        // Filter tanaman
        if ($request->filled('filter_tanaman_id')) {
            $query->where('tanaman_id', $request->filter_tanaman_id);
        }

        $input_harians = $query->orderBy('waktu', 'desc')->get();

        if ($input_harians->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        $filePath = storage_path('app/public/Periode_tanam.csv');
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

        foreach ($input_harians as $inputHarian) {
            $writer->addRow([
                $inputHarian->tanaman->nama_tanaman ?? '-',
                $inputHarian->nama_periode ?? '-',
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

        return response()->download($filePath)->deleteFileAfterSend();
    }
}
