<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Activitylog\Models\Activity;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'log_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'subject_type' => $this->faker->word,
            'event' => $this->faker->word,
            'subject_id' => $this->faker->randomDigitNotNull,
            'causer_type' => User::class,
            'causer_id' => User::factory(),  // Menggunakan factory untuk User
            'properties' => json_encode(['data' => $this->faker->sentence]),
            'batch_uuid' => $this->faker->uuid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
