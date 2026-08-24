<?php

namespace Database\Factories;

use App\Models\Rack;
use App\Models\Sensor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sensor>
 */
class SensorFactory extends Factory
{
    protected $model = Sensor::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['temperature', 'humidity', 'power', 'airflow']);

        return [
            'rack_id' => Rack::factory(),
            'code' => strtoupper(fake()->unique()->bothify('SNR-####')),
            'name' => 'Sensor '.ucfirst($type),
            'type' => $type,
            'unit' => match ($type) {
                'temperature' => '°C',
                'humidity' => '%RH',
                'power' => 'W',
                'airflow' => 'CFM',
                default => 'unit',
            },
            'status' => 'active',
            'last_reading_at' => null,
        ];
    }
}
