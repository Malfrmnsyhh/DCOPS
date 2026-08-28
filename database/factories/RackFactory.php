<?php

namespace Database\Factories;

use App\Models\Rack;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rack>
 */
class RackFactory extends Factory
{
    protected $model = Rack::class;

    public function definition(): array
    {
        return [
            'room_id' => Room::factory(),
            'code' => strtoupper(fake()->unique()->bothify('RCK-##')),
            'name' => 'Rack '.fake()->randomLetter(),
            'u_height' => 42,
            'max_power_kw' => fake()->randomFloat(2, 5, 15),
            'status' => 'active',
        ];
    }
}
