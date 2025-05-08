<?php

namespace Database\Factories;

use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SensorFactory extends Factory
{
    /**
     * Nama model yang terkait dengan factory.
     *
     * @var string
     */
    protected $model = Sensor::class;

    /**
     * Definisikan data default untuk model `Sensor`.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parameter' => $this->faker->randomElement(['ph', 'pota', 'phospor', 'EC', 'Nitrogen', 'humidity', 'temp']),
            'value' => $this->faker->randomFloat(2, 0, 10),  // Misalnya nilai antara 0 dan 10 dengan 2 desimal
            'time' => Carbon::now()->format('Y-m-d H:i:s'),
            'ri' => $this->faker->uuid,  // Menggunakan UUID sebagai nilai acak
        ];
    }
}
