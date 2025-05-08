<?php

namespace Database\Factories;

use App\Models\Mitra;
use Illuminate\Database\Eloquent\Factories\Factory;

class MitraFactory extends Factory
{
    protected $model = Mitra::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'lokasi' => $this->faker->city,
            'email' => $this->faker->unique()->safeEmail,
            'telepon' => $this->faker->phoneNumber,
            'foto_mitra' => null,
        ];
    }
}
