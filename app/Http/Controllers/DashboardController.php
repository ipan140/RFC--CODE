<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    private $headers;

    public function __construct()
    {
        // Header API untuk autentikasi
        $this->headers = [
            'X-M2M-Origin' => '44dbb85550128192:c079ec55758e79bb',
            'Content-Type' => 'application/json;ty=4',
            'Accept' => 'application/json'
        ];
    }

    /**
     * Fungsi untuk mengambil data dari API
     */
    private function fetchData($url)
    {
        try {
            $response = Http::withHeaders($this->headers)->get($url);

            // Periksa apakah response berhasil (status 200)
            if ($response->successful()) {
                $data = json_decode($response->body(), true);
                return $data['m2m:cin']['con'] ?? null;
            } else {
                return null; // Return null jika gagal
            }
        } catch (\Exception $e) {
            return null; // Return null jika terjadi error
        }
    }

    public function index()
    {
        try {
            // Ambil data dari API Antares
            $data_pH = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/ph/la');
            $data_ec = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/EC/la');
            $data_sm = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/humidity/la');
            $data_st = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/temp/la');
            $data_nit = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/Nitrogen/la');
            $data_kal = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/pota/la');
            $data_phos = $this->fetchData('https://platform.antares.id:8443/~/antares-cse/antares-id/interest/phospor/la');

            // Tambahkan variabel $title
            $title = 'Dashboard';

            // Kirim data ke tampilan dashboard
            return view('dashboard', compact(
                'title', 'data_pH', 'data_ec', 'data_sm', 'data_st', 'data_nit', 'data_kal', 'data_phos'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data', 'message' => $e->getMessage()], 500);
        }
    }
}
