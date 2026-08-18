<?php

namespace Database\Factories;

use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DeviceType>
 */
class DeviceTypeFactory extends Factory
{
    protected $model = DeviceType::class;

    public function definition(): array
    {
        $name = fake()->unique()->company().' '.fake()->randomElement(['Server', 'Switch', 'Storage Array']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category' => fake()->randomElement(['network', 'server', 'storage', 'other']),
            'default_u_size' => fake()->randomElement([1, 2, 4]),
        ];
    }
}
