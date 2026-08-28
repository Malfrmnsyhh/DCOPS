<?php

namespace Database\Factories;

use App\Models\DevicePort;
use App\Models\PortConnection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PortConnection>
 */
class PortConnectionFactory extends Factory
{
    protected $model = PortConnection::class;

    public function definition(): array
    {
        return [
            'from_port_id' => DevicePort::factory(),
            'to_port_id' => DevicePort::factory(),
            'cable_type' => fake()->randomElement(['cat5', 'cat6', 'fiber', 'coax']),
            'cable_label' => strtoupper(fake()->bothify('LNK-###')),
            'length_m' => fake()->randomFloat(2, 0.5, 10),
        ];
    }
}
