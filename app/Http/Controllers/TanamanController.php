<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tanaman;
use Carbon\Carbon;

class TanamanController extends Controller
{
    public function index()
    {
        $tanamans = Tanaman::all();
        return view('tanaman.index', compact('tanamans'));
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

        Tanaman::create([
            'nama_tanaman' => $request->nama_tanaman,
            'deskripsi' => $request->deskripsi,
            'tanggal_tanam' => $tanggalTanam,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data tanaman berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tanaman = Tanaman::findOrFail($id);

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

        $tanaman->update($data);

        return redirect()->back()->with('success', 'Data tanaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tanaman = Tanaman::findOrFail($id);
        $tanaman->delete();

        return redirect()->back()->with('success', 'Data tanaman berhasil dihapus.');
    }
}
