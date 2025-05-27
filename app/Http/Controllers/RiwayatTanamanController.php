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

        if ($request->filled('filter_periode_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu', '<=', $request->tanggal_akhir);
        }

        // 🔽 Tambahkan filter kategori jika dipilih
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $inputHarians = $query->orderBy('waktu', 'desc')->paginate(10);
        $periodeTanams = PeriodeTanam::all();

        // 🔽 List kategori untuk dropdown (bisa juga dari DB jika dinamis)
        $kategoriList = ['daun', 'tanah', 'air', 'udara'];

        return view('riwayat_tanaman.index', compact('inputHarians', 'periodeTanams', 'kategoriList'));
    }

    public function export(Request $request)
    {
        $query = InputHarian::with('periode');

        if ($request->filled('filter_periode_id')) {
            $query->where('periode_tanam_id', $request->filter_periode_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('waktu', '<=', $request->tanggal_akhir);
        }

        // 🔽 Tambahkan filter kategori ke ekspor
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
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
            'Kategori',
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
                $inputHarian->periode->nama_tanaman ?? '-',
                $inputHarian->periode->nama_periode ?? '-',
                $inputHarian->kategori ?? '-', // Tambahkan kolom kategori di CSV
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
