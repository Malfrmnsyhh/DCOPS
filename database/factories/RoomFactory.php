<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'code' => strtoupper(fake()->unique()->bothify('ROOM-##')),
            'name' => 'Server Room '.fake()->randomLetter(),
            'floor' => (string) fake()->numberBetween(1, 5),
            'area_sqm' => fake()->randomFloat(2, 50, 500),
            'status' => 'active',
        ];
    }
}
