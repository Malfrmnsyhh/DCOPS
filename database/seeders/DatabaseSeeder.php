<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 3 user sesuai PRD §6 — satu per role, password default: "password"
        User::factory()->create([
            'name'  => 'Admin DCOps',
            'email' => 'admin@dcops.local',
            'role'  => 'admin',
        ]);

        User::factory()->create([
            'name'  => 'Engineer DCOps',
            'email' => 'engineer@dcops.local',
            'role'  => 'engineer',
        ]);

        User::factory()->create([
            'name'  => 'Viewer DCOps',
            'email' => 'viewer@dcops.local',
            'role'  => 'viewer',
        ]);

        $this->call(DcopsDummySeeder::class);
    }
}
