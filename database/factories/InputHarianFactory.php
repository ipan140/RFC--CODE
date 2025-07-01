<?php

namespace Database\Factories;

use App\Models\InputHarian;
use App\Models\PeriodeTanam;
use App\Models\KategoriSampel;
use Illuminate\Database\Eloquent\Factories\Factory;

class InputHarianFactory extends Factory
{
    protected $model = InputHarian::class;

    public function definition(): array
    {
        return [
            'periode_tanam_id' => PeriodeTanam::factory(),
            'kategori_sampel_id' => KategoriSampel::factory(),
            'waktu' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'pupuk' => $this->faker->word(),
            'panjang_daun' => $this->faker->randomFloat(2, 1, 20),
            'lebar_daun' => $this->faker->randomFloat(2, 1, 15),
            'ph' => $this->faker->randomFloat(2, 5.5, 7.5),
            'pota' => $this->faker->numberBetween(0, 100),
            'phospor' => $this->faker->numberBetween(0, 100),
            'EC' => $this->faker->randomFloat(2, 1.0, 3.0),
            'Nitrogen' => $this->faker->numberBetween(0, 100),
            'humidity' => $this->faker->randomFloat(2, 30, 90),
            'temp' => $this->faker->randomFloat(2, 20, 35),
        ];
    }
}
