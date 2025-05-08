<?php

namespace Database\Factories;

use App\Models\Tanaman;
use Illuminate\Database\Eloquent\Factories\Factory;

class TanamanFactory extends Factory
{
    /**
     * Nama model yang terkait dengan factory ini.
     *
     * @var string
     */
    protected $model = Tanaman::class;

    /**
     * Definisi default untuk atribut model.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_tanaman' => $this->faker->unique()->words(2, true), // contoh: "Bunga Mawar"
            'deskripsi' => $this->faker->sentence(8), // kalimat pendek untuk deskripsi
            'tanggal_tanam' => $this->faker->date('Y-m-d'), // tanggal random
            'status' => $this->faker->randomElement(['on going', 'selesai']), // pilihan status
        ];
    }
}
