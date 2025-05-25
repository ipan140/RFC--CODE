<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KategoriSampelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kategori_sampel')->insert([
            [
                'nama' => 'Tanah Gambut',
                'deskripsi' => 'Tanah gambut dengan kandungan organik tinggi.',
                'tanaman_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Tanah Liat',
                'deskripsi' => 'Tanah liat yang cocok untuk tanaman padi.',
                'tanaman_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Tanah Berpasir',
                'deskripsi' => 'Tanah berpasir dengan drainase baik.',
                'tanaman_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
