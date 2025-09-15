<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->unique();
            
            // User Management
            $table->boolean('user_create')->default(false);
            $table->boolean('user_update')->default(false);
            $table->boolean('user_delete')->default(false);
            $table->boolean('user_view')->default(false);

            // Service Management
            $table->boolean('service_create')->default(false);
            $table->boolean('service_update')->default(false);
            $table->boolean('service_delete')->default(false);
            $table->boolean('service_view')->default(false);

            // Orders / Transactions
            $table->boolean('order_create')->default(false);
            $table->boolean('order_update')->default(false);
            $table->boolean('order_delete')->default(false);
            $table->boolean('order_view')->default(false);

            // Schedules (drop-off/pickup)
            $table->boolean('schedules_create')->default(false);
            $table->boolean('schedules_update')->default(false);
            $table->boolean('schedules_delete')->default(false);
            $table->boolean('schedules_view')->default(false);

            // Payments
            $table->boolean('payments_create')->default(false);
            $table->boolean('payments_update')->default(false);
            $table->boolean('payments_delete')->default(false);
            $table->boolean('payments_view')->default(false);

            // Inventory
            $table->boolean('inventory_create')->default(false);
            $table->boolean('inventory_update')->default(false);
            $table->boolean('inventory_delete')->default(false);
            $table->boolean('inventory_view')->default(false);

            // Reports
            $table->tinyInteger('report_view_level')->default(0); // 0 = none, 1 = week, 2 = full

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
