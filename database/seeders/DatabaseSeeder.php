<?php
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        Role::insert([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Doctor'],
            ['id' => 3, 'name' => 'Staff'],
            ['id' => 4, 'name' => 'Patient']
        ]);

        // Create test user with profile
        $user = User::create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'is_active' => 1
        ]);

        $user->profile()->create([
            'first_name' => 'Test',

            'last_name' => 'User',
            'phone' => '1234567890',
            'gender_id' => 1
        ]);
    }
}
