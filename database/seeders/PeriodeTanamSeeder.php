<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeriodeTanam;

class PeriodeTanamSeeder extends Seeder
{
    public function run(): void
    {
        $periodeTanams = [
            [
                'nama_tanaman' => 'Anggur',
                'deskripsi' => 'Anggur ditanam menggunakan sistem IOT di rooftop.',
                'tanggal_tanam' => '2025-01-05 07:00:00',
                'status' => 'selesai',
            ],
            [
                'nama_tanaman' => 'Melon',
                'deskripsi' => 'Melon ditanam di smart greenhouse dengan sistem IOT.',
                'tanggal_tanam' => '2025-01-10 08:00:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Jeruk',
                'deskripsi' => 'Jeruk dibudidayakan di rooftop ITTelkom Surabaya.',
                'tanggal_tanam' => '2025-01-12 09:00:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Pepaya',
                'deskripsi' => 'Pepaya tumbuh subur di rooftop ITTelkom.',
                'tanggal_tanam' => '2025-01-14 09:30:00',
                'status' => 'on going',
            ],
            [
                'nama_tanaman' => 'Pisang',
                'deskripsi' => 'Pisang ditanam di dekat kolam smart pond.',
                'tanggal_tanam' => '2025-01-15 10:00:00',
                'status' => 'selesai',
            ],
            [
                'nama_tanaman' => 'Mangga',
                'deskripsi' => 'Pohon mangga sudah mulai berbuah.',
                'tanggal_tanam' => '2025-01-17 10:30:00',
                'status' => 'selesai',
            ],
        ];

        foreach ($periodeTanams as $data) {
            PeriodeTanam::create($data);
        }
    }
}
