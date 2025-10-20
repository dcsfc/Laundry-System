<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $superadminRole = Role::where('name', 'superadmin')->first();
        $administratorRole = Role::where('name', 'administrator')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Create default users
        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@latino.com',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567890',
                'role_id' => $superadminRole->id,
                'status' => 'active',
                'created_by' => null, 
            ],
            [
                'name' => 'Administrator',
                'email' => 'administrator@latino.com',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567891',
                'role_id' => $administratorRole->id,
                'status' => 'active',
                'created_by' => 1, 
            ],
            [
                'name' => 'Staff Member',
                'email' => 'staff@latino.com',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567892',
                'role_id' => $staffRole->id,
                'status' => 'active',
                'created_by' => 1, // Created by superadmin
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@latino.com',
                'password' => Hash::make('password123'),
                'phone_number' => '+1234567893',
                'role_id' => $customerRole->id,
                'status' => 'active',
                'created_by' => 1, 
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
