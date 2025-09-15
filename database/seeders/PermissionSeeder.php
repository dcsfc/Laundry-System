<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin - Full access to everything
        DB::table('permissions')->insert([
            'role_id' => 1,
            'user_create' => true,
            'user_update' => true,
            'user_delete' => true,
            'user_view' => true,
            'service_create' => true,
            'service_update' => true,
            'service_delete' => true,
            'service_view' => true,
            'order_create' => true,
            'order_update' => true,
            'order_delete' => true,
            'order_view' => true,
            'schedules_create' => true,
            'schedules_update' => true,
            'schedules_delete' => true,
            'schedules_view' => true,
            'payments_create' => true,
            'payments_update' => true,
            'payments_delete' => true,
            'payments_view' => true,
            'inventory_create' => true,
            'inventory_update' => true,
            'inventory_delete' => true,
            'inventory_view' => true,
            'report_view_level' => 2, // Full access
        ]);

        // Administrator - Management access
        DB::table('permissions')->insert([
            'role_id' => 2,
            'user_create' => true,
            'user_update' => true,
            'user_delete' => false,
            'user_view' => true,
            'service_create' => true,
            'service_update' => true,
            'service_delete' => false,
            'service_view' => true,
            'order_create' => true,
            'order_update' => true,
            'order_delete' => false,
            'order_view' => true,
            'schedules_create' => true,
            'schedules_update' => true,
            'schedules_delete' => false,
            'schedules_view' => true,
            'payments_create' => true,
            'payments_update' => true,
            'payments_delete' => false,
            'payments_view' => true,
            'inventory_create' => true,
            'inventory_update' => true,
            'inventory_delete' => false,
            'inventory_view' => true,
            'report_view_level' => 2, // Full access
        ]);

        // Staff - Operational access
        DB::table('permissions')->insert([
            'role_id' => 3,
            'user_create' => false,
            'user_update' => false,
            'user_delete' => false,
            'user_view' => true,
            'service_create' => false,
            'service_update' => false,
            'service_delete' => false,
            'service_view' => true,
            'order_create' => true,
            'order_update' => true,
            'order_delete' => false,
            'order_view' => true,
            'schedules_create' => true,
            'schedules_update' => true,
            'schedules_delete' => false,
            'schedules_view' => true,
            'payments_create' => true,
            'payments_update' => false,
            'payments_delete' => false,
            'payments_view' => true,
            'inventory_create' => false,
            'inventory_update' => false,
            'inventory_delete' => false,
            'inventory_view' => true,
            'report_view_level' => 1, // Weekly access
        ]);

        // Customer - Limited access
        DB::table('permissions')->insert([
            'role_id' => 4,
            'user_create' => false,
            'user_update' => false,
            'user_delete' => false,
            'user_view' => false,
            'service_create' => false,
            'service_update' => false,
            'service_delete' => false,
            'service_view' => true,
            'order_create' => true,
            'order_update' => false,
            'order_delete' => false,
            'order_view' => true,
            'schedules_create' => false,
            'schedules_update' => false,
            'schedules_delete' => false,
            'schedules_view' => true,
            'payments_create' => false,
            'payments_update' => false,
            'payments_delete' => false,
            'payments_view' => true,
            'inventory_create' => false,
            'inventory_update' => false,
            'inventory_delete' => false,
            'inventory_view' => false,
            'report_view_level' => 0, // No access
        ]);
    }
}
