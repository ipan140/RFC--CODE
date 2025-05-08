<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;

class HomeController extends Controller
{
    public function index()
    {
        $mitras = Mitra::all();
        return view('home', compact('mitras'));
    }
}
