<?php

namespace Database\Seeders;

use App\Models\Role;
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
        $adminRole = Role::query()->firstOrCreate(['name' => Role::ADMIN]);
        $managerRole = Role::query()->firstOrCreate(['name' => Role::MANAGER]);
        $playerRole = Role::query()->firstOrCreate(['name' => Role::PLAYER]);

        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'password', 'role_id' => $adminRole->id]
        );

        User::query()->updateOrCreate(
            ['email' => 'manager@example.com'],
            ['name' => 'Manager User', 'password' => 'password', 'role_id' => $managerRole->id]
        );

        User::query()->updateOrCreate(
            ['email' => 'player@example.com'],
            ['name' => 'Player User', 'password' => 'password', 'role_id' => $playerRole->id]
        );
    }
}
