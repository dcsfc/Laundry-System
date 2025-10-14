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
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('type', ['new', 'improvement', 'fix', 'maintenance', 'alert'])->default('new')->after('message');
            $table->string('link')->nullable()->after('type');
            $table->enum('visible_to', ['all', 'admin', 'staff', 'customer'])->default('all')->after('link');
            $table->timestamp('expires_at')->nullable()->after('visible_to');
            $table->boolean('is_pinned')->default(false)->after('expires_at');
            $table->boolean('is_active')->default(true)->after('is_pinned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['type', 'link', 'visible_to', 'expires_at', 'is_pinned', 'is_active']);
        });
    }
};