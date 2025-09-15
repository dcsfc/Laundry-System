<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Super Admin
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@latino.com',
            'password' => Hash::make('password123'),
            'role_id' => 1, // superadmin role
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create Admin
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@latino.com',
            'password' => Hash::make('password123'),
            'role_id' => 2, // administrator role
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create Staff
        DB::table('users')->insert([
            'name' => 'Staff User',
            'email' => 'staff@latino.com',
            'password' => Hash::make('password123'),
            'role_id' => 3, // staff role
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create Customer
        DB::table('users')->insert([
            'name' => 'Customer User',
            'email' => 'customer@latino.com',
            'password' => Hash::make('password123'),
            'role_id' => 4, // customer role
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}