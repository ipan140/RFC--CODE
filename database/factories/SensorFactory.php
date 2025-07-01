<?php

namespace Database\Factories;

use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition(): array
    {
        return [
            'parameter' => $this->faker->randomElement(['PH', 'POTA', 'PHOSPHOR', 'EC', 'NITROGEN', 'HUMIDITY', 'TEMP']),
            'value' => $this->faker->randomFloat(2, 0, 14),
            'waktu' => Carbon::now()->format('Y-m-d H:i:s'),
            'ri' => '/antares-cse/cin-' . $this->faker->uuid,
        ];
    }
}
