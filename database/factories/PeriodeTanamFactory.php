<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeriodeTanam>
 */
class PeriodeTanamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_tanaman' => $this->faker->randomElement(['Anggur', 'Melon', 'Jeruk', 'Pepaya', 'Pisang', 'Mangga']),
            'deskripsi' => $this->faker->sentence(8),
            'tanggal_tanam' => $this->faker->dateTimeBetween('2025-01-01', '2025-02-01'),
            'status' => $this->faker->randomElement(['selesai', 'on going']),
        ];
    }
}