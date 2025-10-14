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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // e.g., 'general', 'email', 'notifications'
            $table->string('key')->index(); // e.g., 'business_name', 'smtp_host'
            $table->text('value')->nullable(); // The actual setting value
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
            
            // Ensure unique combination of category and key
            $table->unique(['category', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
