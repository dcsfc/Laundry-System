<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update any existing data to match new enum values
        DB::table('orders')->where('status', 'scheduled')->update(['status' => 'pending']);
        DB::table('orders')->where('status', 'priced')->update(['status' => 'confirmed']);
        DB::table('orders')->where('status', 'in_progress')->update(['status' => 'processing']);
        DB::table('orders')->where('status', 'canceled')->update(['status' => 'cancelled']);
        
        // For SQLite, we need to recreate the table
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'processing', 'ready_for_pickup', 'completed', 'cancelled'])->default('pending')->after('pickup_date');
        });
    }

    public function down(): void
    {
        // Revert data back to old enum values
        DB::table('orders')->where('status', 'pending')->update(['status' => 'scheduled']);
        DB::table('orders')->where('status', 'confirmed')->update(['status' => 'priced']);
        DB::table('orders')->where('status', 'processing')->update(['status' => 'in_progress']);
        DB::table('orders')->where('status', 'cancelled')->update(['status' => 'canceled']);
        
        // For SQLite, recreate the column
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['scheduled', 'priced', 'in_progress', 'completed', 'canceled'])->default('scheduled')->after('pickup_date');
        });
    }
};