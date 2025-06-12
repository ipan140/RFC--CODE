<?php

namespace App\Http\Controllers;

use App\Models\InputHarian;
use App\Models\PeriodeTanam;
use App\Models\KategoriSampel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;

class RiwayatTanamanController extends Controller
{
    public function index(Request $request)
    {
        $query = InputHarian::with(['periode', 'kategoriSampel']);

        if ($request->filled('filter_periode_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_id);
        }

        if ($request->filled('kategori_sampel_id')) {
            $query->where('kategori_sampel_id', $request->kategori_sampel_id);
        }

        // Filter tanggal berdasarkan kolom waktu
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tglAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tglAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

            $query->whereBetween('waktu', [$tglAwal, $tglAkhir]);
        }

        $inputHarians = $query->orderBy('waktu', 'desc')->paginate(10)->withQueryString();
        $periodeTanams = PeriodeTanam::all();
        $kategoriSampels = KategoriSampel::all();

        return view('riwayat_tanaman.index', compact('inputHarians', 'periodeTanams', 'kategoriSampels'));
    }

    public function export(Request $request)
    {
        $query = InputHarian::with(['periode', 'kategoriSampel']);

        if ($request->filled('filter_periode_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_id);
        }

        if ($request->filled('kategori_sampel_id')) {
            $query->where('kategori_sampel_id', $request->kategori_sampel_id);
        }

        // Filter tanggal berdasarkan kolom waktu
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tglAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tglAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();

            $query->whereBetween('waktu', [$tglAwal, $tglAkhir]);
        }

        $inputHarians = $query->orderBy('waktu', 'desc')->get();

        if ($inputHarians->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diekspor.');
        }

        $filePath = storage_path('app/public/riwayat_tanam.csv');
        $writer = SimpleExcelWriter::create($filePath);

        $writer->addRow([
            'Nama Tanaman',
            'Kategori Sampel',
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

        foreach ($inputHarians as $inputHarian) {
            $writer->addRow([
                $inputHarian->periode->nama_tanaman ?? '-',
                $inputHarian->kategoriSampel->nama ?? '-',
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
