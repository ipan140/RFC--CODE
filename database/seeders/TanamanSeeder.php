<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tanaman;

class TanamanSeeder extends Seeder
{
    public function run(): void
    {
        $tanamans = [
            [
                'nama_tanaman' => 'Cabai Merah',
                'deskripsi' => 'Cabai merah cocok ditanam di daerah tropis dan digunakan dalam banyak masakan.',
                'tanggal_tanam' => '2025-01-10 07:00:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Tomat',
                'deskripsi' => 'Tomat banyak digunakan sebagai bahan masakan dan salad.',
                'tanggal_tanam' => '2025-01-15 08:00:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Bawang Merah',
                'deskripsi' => 'Bawang merah digunakan sebagai bumbu dasar masakan.',
                'tanggal_tanam' => '2024-12-01 06:30:00',
                'status' => 'selesai',
            ],
            [
                'nama_tanaman' => 'Selada',
                'deskripsi' => 'Selada cocok untuk salad dan sandwich.',
                'tanggal_tanam' => '2025-02-01 09:00:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Bayam',
                'deskripsi' => 'Bayam mengandung zat besi tinggi dan mudah ditanam.',
                'tanggal_tanam' => '2025-01-20 07:30:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Kangkung',
                'deskripsi' => 'Kangkung tumbuh cepat dan populer di masakan tumis.',
                'tanggal_tanam' => '2025-01-05 06:45:00',
                'status' => 'selesai',
            ],
            [
                'nama_tanaman' => 'Terong Ungu',
                'deskripsi' => 'Terong sering digunakan dalam masakan berkuah dan digoreng.',
                'tanggal_tanam' => '2025-01-18 08:15:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Wortel',
                'deskripsi' => 'Wortel kaya vitamin A dan cocok untuk sup.',
                'tanggal_tanam' => '2025-01-12 07:45:00',
                'status' => 'selesai',
            ],
            [
                'nama_tanaman' => 'Mentimun',
                'deskripsi' => 'Mentimun segar biasa digunakan dalam rujak dan lalapan.',
                'tanggal_tanam' => '2025-01-25 09:30:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Kacang Panjang',
                'deskripsi' => 'Kacang panjang tumbuh merambat dan cocok ditanam di pekarangan.',
                'tanggal_tanam' => '2025-01-28 08:00:00',
                'status' => 'selesai',
            ],
        ];

        foreach ($tanamans as $data) {
            Tanaman::create($data);
        }
    }
}
