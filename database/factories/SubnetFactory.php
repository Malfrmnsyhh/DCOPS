<?php

namespace Database\Factories;

use App\Models\Subnet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subnet>
 */
class SubnetFactory extends Factory
{
    protected $model = Subnet::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word().' Subnet',
            'cidr' => '10.'.fake()->unique()->numberBetween(20, 250).'.1.0/24',
            'gateway' => null,
            'vlan_id' => fake()->numberBetween(100, 999),
            'description' => null,
        ];
    }
}
