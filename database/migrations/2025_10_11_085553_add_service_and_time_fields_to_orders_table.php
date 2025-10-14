<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add service relationship
            $table->unsignedBigInteger('service_id')->nullable()->after('staff_id');
            $table->foreign('service_id')->references('id')->on('services');
            
            // Add time fields
            $table->time('dropoff_time')->nullable()->after('dropoff_date');
            $table->time('pickup_time')->nullable()->after('pickup_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['service_id', 'dropoff_time', 'pickup_time']);
        });
    }
};
