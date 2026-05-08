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
        // Create an Admin
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create an Executive
        \App\Models\User::factory()->create([
            'name' => 'Executive User',
            'email' => 'exec@example.com',
            'password' => bcrypt('password'),
            'role' => 'executive',
        ]);

        // Create an Advisor
        \App\Models\User::factory()->create([
            'name' => 'Advisor User',
            'email' => 'advisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'advisor',
        ]);
    }
}
