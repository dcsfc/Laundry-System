<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'gcash', 'credit_card', 'paypal'])->default('cash');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
