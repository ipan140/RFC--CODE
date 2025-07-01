<?php

namespace Database\Factories;

use App\Models\KategoriSampel;
use App\Models\PeriodeTanam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriSampel>
 */
class KategoriSampelFactory extends Factory
{
    protected $model = KategoriSampel::class;

    public function definition()
    {
        return [
            'periode_tanam_id' => PeriodeTanam::factory(),
            'nama' => $this->faker->word,
            'deskripsi' => $this->faker->sentence,
        ];
    }
}

