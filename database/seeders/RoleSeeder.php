<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'description' => 'Super Administrator with full access'],
            ['name' => 'administrator', 'description' => 'Administrator with management access'],
            ['name' => 'staff', 'description' => 'Staff member with operational access'],
            ['name' => 'customer', 'description' => 'Customer with limited access']
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], [
                'name' => $role['name'],
                'description' => $role['description']
            ]);
        }
    }
}
