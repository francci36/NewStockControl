<?php
// database/migrations/xxxx_xx_xx_create_payments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('payment_method');
            $table->string('payment_id')->nullable();
            $table->string('status');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payer_email')->nullable();
            $table->string('payer_name')->nullable();
            $table->json('payment_details')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->string('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'payment_method']);
            $table->index('payment_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};