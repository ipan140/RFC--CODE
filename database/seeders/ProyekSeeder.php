<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proyek;

class ProyekSeeder extends Seeder
{
    public function run(): void
    {
        $proyeks = [
            [
                'nama' => 'Budi Daya Tanaman Anggur',
                'deskripsi' => 'Anggur ditanam pada rooftop dengan teknologi IoT, memungkinkan penyiraman dan perawatan otomatis.',
                'tanggal' => '2025-05-01',
                'foto_proyek' => null,
            ],
            [
                'nama' => 'Smart Green House Melon',
                'deskripsi' => 'Smart Green House untuk Melon. Melon ditanam di Smart Green House berbasis IoT. "Golden Melon" adalah varietas unggulan yang kami kembangkan.',
                'tanggal' => '2025-06-15',
                'foto_proyek' => null,
            ],
            [
                'nama' => 'Budi Daya Jeruk & Pepaya',
                'deskripsi' => 'Budidaya Pohon Jeruk & Pepaya. Tanaman jeruk dan pepaya dibudidayakan di Smart Rooftop Kampus Telkom Surabaya untuk penelitian dan inovasi IoT.',
                'tanggal' => '2025-07-20',
                'foto_proyek' => null,
            ],
            [
                'nama' => 'Budi Daya Pisang & Mangga',
                'deskripsi' => 'Budidaya Pohon Pisang & Mangga. Pohon pisang tumbuh di dekat "Smart Pond", kolam ikan otomatis berbasis IoT. Pohon mangga juga mulai berbuah dengan baik.',
                'tanggal' => '2025-08-10',
                'foto_proyek' => null,
            ],
        ];

        foreach ($proyeks as $data) {
            Proyek::create($data);
        }
    }
}
