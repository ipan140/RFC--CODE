<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra;

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        $mitras = [
            [
                'nama' => 'PUSPA LEBO',
                'lokasi' => 'UPT Pengembangan Agribisnis Tanaman Pangan dan Hortikultura Agro Wisata Puspa Lebo, DKPP Surabaya, Pagesangan II/56, Kec. Jambangan, Surabaya 60233',
                'email' => 'puspalebo.blogspot.com',
                'telepon' => '081234951713',
                'foto_mitra' => null,
            ],
            [
                'nama' => "D'Durian Park",
                'lokasi' => 'Jl. Cemorosewu, Segunung, Wonosalam, Kec. Wonosalam, Kabupaten Jombang, Jawa Timur 61476, Indonesia',
                'email' => 'dedurian.park@gmail.com',
                'telepon' => '0822-2941-9828',
                'foto_mitra' => null,
            ],
            [
                'nama' => 'DKPP Surabaya',
                'lokasi' => 'Pagesangan II/56, Kec. Jambangan, Surabaya 60233',
                'email' => 'dinaskppsby@surabaya.go.id',
                'telepon' => '081388111588',
                'foto_mitra' => null,
            ],
        ];

        foreach ($mitras as $data) {
            Mitra::create($data);
        }
    }
}
