<?php

namespace App\Http\Controllers;

use App\Models\InputHarian;
use App\Models\Tanaman;
use App\Models\PeriodeTanam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RiwayatTanamanController extends Controller
{
    public function index(Request $request)
{
    $query = InputHarian::with('tanaman');

    // Filter opsional (jika ada)
    if ($request->filled('filter_tanaman_id')) {
        $query->where('tanaman_id', $request->filter_tanaman_id);
    }

    if ($request->filled('tanggal_mulai')) {
        $query->whereDate('waktu', '>=', $request->tanggal_mulai);
    }

    if ($request->filled('tanggal_akhir')) {
        $query->whereDate('waktu', '<=', $request->tanggal_akhir);
    }

    $inputHarians = $query->orderBy('waktu', 'desc')->paginate(10);

    $tanamans = Tanaman::all();

    return view('riwayat_tanaman.index', compact('inputHarians', 'tanamans'));
}


    public function export(Request $request)
    {
        $query = InputHarian::with('tanaman');

        if ($request->filled('filter_tanaman_id')) {
            $query->where('tanaman_id', $request->filter_tanaman_id);
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
