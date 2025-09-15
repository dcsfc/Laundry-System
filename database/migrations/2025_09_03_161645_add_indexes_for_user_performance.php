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
        Schema::table('users', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index('role_id');
            $table->index('status');
            $table->index('created_at');
            $table->index(['role_id', 'status']);
            $table->index(['status', 'created_at']);
        });

        Schema::table('roles', function (Blueprint $table) {
            // Add index for role name lookups
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['role_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};