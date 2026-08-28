<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Site>
 */
class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SITE-##')),
            'name' => fake()->city().' Data Center',
            'address' => fake()->address(),
            'city' => fake()->city(),
            'status' => 'active',
        ];
    }
}
