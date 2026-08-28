<?php

namespace Database\Factories;

use App\Models\AlarmRule;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AlarmRule>
 */
class AlarmRuleFactory extends Factory
{
    protected $model = AlarmRule::class;

    public function definition(): array
    {
        return [
            'sensor_id' => Sensor::factory(),
            'sensor_type' => null,
            'name' => fake()->sentence(3),
            'operator' => '>',
            'threshold_min' => null,
            'threshold_max' => fake()->randomFloat(2, 30, 80),
            'severity' => fake()->randomElement(['warning', 'critical']),
            'auto_create_ticket' => false,
            'is_active' => true,
        ];
    }
}
