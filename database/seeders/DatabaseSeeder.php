<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'phone' => '0123456789',
        'role' => 'admin',
        'password' => bcrypt('admin123'),
    ]);

    // Create multiple users using the factory
    \App\Models\User::factory()->count(6)->create();

    }
}
