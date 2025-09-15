<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('staff_id')->nullable();
            $table->date('dropoff_date');
            $table->date('pickup_date');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'gcash', 'credit_card', 'paypal'])->nullable();
            $table->enum('status', ['scheduled', 'priced', 'in_progress', 'completed', 'canceled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreign('staff_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users');

            // Indexes for schedule queries
            $table->index('dropoff_date');
            $table->index('pickup_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
