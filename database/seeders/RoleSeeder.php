<?php
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Manager'],
            ['id' => 3, 'name' => 'Editor'],
            ['id' => 4, 'name' => 'User'], // This matches your default role_id in the controller
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
