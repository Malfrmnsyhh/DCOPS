<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DevicePort;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DevicePort>
 */
class DevicePortFactory extends Factory
{
    protected $model = DevicePort::class;

    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'name' => 'eth'.fake()->unique()->numberBetween(0, 9999),
            'type' => 'ethernet',
            'speed_mbps' => fake()->randomElement([100, 1000, 10000]),
            'mac_address' => fake()->unique()->macAddress(),
            'status' => 'active',
        ];
    }
}
