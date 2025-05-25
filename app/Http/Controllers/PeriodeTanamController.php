<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeriodeTanam;
use Carbon\Carbon;

class PeriodeTanamController extends Controller
{
    public function index()
    {
        $periodeTanams = PeriodeTanam::latest()->paginate(10);
        return view('periode_tanam.index', compact('periodeTanams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tanam' => 'required|date',
            'status' => 'required|in:on going,selesai',
        ]);

        $tanggalTanam = Carbon::parse($request->tanggal_tanam)
            ->setTimeFromTimeString(now()->format('H:i:s'));

        PeriodeTanam::create([
            'nama_tanaman' => $request->nama_tanaman,
            'deskripsi' => $request->deskripsi,
            'tanggal_tanam' => $tanggalTanam,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Periode tanam berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $periode = PeriodeTanam::findOrFail($id);

        $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tanam' => 'required|date',
            'status' => 'required|in:on going,selesai',
        ]);

        $data = [
            'nama_tanaman' => $request->nama_tanaman,
            'deskripsi' => $request->deskripsi,
            'tanggal_tanam' => Carbon::parse($request->tanggal_tanam)
                ->setTimeFromTimeString(now()->format('H:i:s')),
            'status' => $request->status,
        ];

        $periode->update($data);

        return redirect()->back()->with('success', 'Periode tanam berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $periode = PeriodeTanam::findOrFail($id);
        $periode->delete();

        return redirect()->back()->with('success', 'Periode tanam berhasil dihapus.');
    }
}
