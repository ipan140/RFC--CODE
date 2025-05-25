<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\Proyek;

class HomeController extends Controller
{
    public function index()
    {
        $mitras = Mitra::all();
        $proyeks = Proyek::all(); // Tambahkan ini untuk ambil data projek
        
        return view('home.index', compact('mitras', 'proyeks'));
    }
}
