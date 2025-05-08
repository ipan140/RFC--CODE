<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar log aktivitas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil log dengan relasi causer dan subject, urutkan terbaru, dan paginasi
        $logs = Activity::with(['causer', 'subject'])->latest()->paginate(10);

        return view('logaktivitas.index', compact('logs'));
    }
}
