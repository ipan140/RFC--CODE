<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tanaman;
use Illuminate\Support\Facades\Storage;
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
            'panjang_daun' => 'required|numeric',
            'lebar_daun' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Gabungkan tanggal dari input dan waktu sekarang
        $tanggalTanam = Carbon::parse($request->tanggal_tanam)
            ->setTimeFromTimeString(now()->format('H:i:s')); // Atur waktu ke sekarang

        $data = [
            'nama_tanaman' => $request->nama_tanaman,
            'deskripsi' => $request->deskripsi,
            'tanggal_tanam' => $tanggalTanam,
            'panjang_daun' => $request->panjang_daun,
            'lebar_daun' => $request->lebar_daun,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_tanaman', 'public');
        }

        Tanaman::create($data);

        return redirect()->back()->with('success', 'Data tanaman berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tanaman = Tanaman::findOrFail($id);

        $request->validate([
            'nama_tanaman' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_tanam' => 'required|date',
            'panjang_daun' => 'required|numeric',
            'lebar_daun' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only([
            'nama_tanaman',
            'deskripsi',
            'tanggal_tanam',
            'panjang_daun',
            'lebar_daun',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($tanaman->foto) {
                Storage::disk('public')->delete($tanaman->foto);
            }

            $data['foto'] = $request->file('foto')->store('foto_tanaman', 'public');
        }

        $tanaman->update($data);

        return redirect()->back()->with('success', 'Data tanaman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tanaman = Tanaman::findOrFail($id);

        if ($tanaman->foto) {
            Storage::disk('public')->delete($tanaman->foto);
        }

        $tanaman->delete();

        return redirect()->back()->with('success', 'Data tanaman berhasil dihapus.');
    }
}
