<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas dengan pagination.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil log dengan relasi 'causer' dan 'subject', urutkan berdasarkan created_at terbaru, paginate 10 per halaman
        $logs = Activity::with(['causer', 'subject'])
            ->latest() // orderBy('created_at', 'desc')
            ->paginate(10);

        // Kirim data logs ke view 'logaktivitas.index'
        return view('logaktivitas.index', compact('logs'));
    }
}

