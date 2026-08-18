<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    protected $model = Device::class;

    public function definition(): array
    {
        return [
            'rack_id' => null,
            'device_type_id' => DeviceType::factory(),
            'hostname' => fake()->unique()->domainWord().'-'.fake()->numberBetween(1, 999),
            'serial_number' => strtoupper(fake()->unique()->bothify('SN-????-####')),
            'manufacturer' => fake()->randomElement(['Dell', 'HPE', 'Cisco', 'Juniper']),
            'model' => fake()->bothify('Model-????'),
            'position_u' => null,
            'u_size' => 1,
            'power_watt' => fake()->numberBetween(100, 800),
            'status' => 'standby',
            'installed_at' => null,
        ];
    }
}
