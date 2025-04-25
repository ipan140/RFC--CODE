<?php

namespace App\Http\Controllers;

use App\Models\RiwayatTanaman;
use App\Models\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RiwayatTanamanController extends Controller
{
    public function index(Request $request)
    {
        // Build query for Riwayat Tanaman data
        $query = RiwayatTanaman::with('tanaman');

        // Filter by Tanaman if a filter is applied
        if ($request->has('filter_tanaman_id') && $request->filter_tanaman_id != '') {
            $query->where('tanaman_id', $request->filter_tanaman_id);
        }

        // Fetch the records and order them by date
        $riwayat_tanams = $query->orderBy('tanggal', 'desc')->get();

        // Fetch all available Tanaman data for filtering
        $tanamans = Tanaman::all();

        // Return the view with the data
        return view('riwayat_tanaman.index', compact('riwayat_tanams', 'tanamans'));
    }
}
