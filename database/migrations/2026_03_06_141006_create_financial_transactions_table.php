<?php
// database/migrations/xxxx_xx_xx_create_financial_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->enum('type', ['income', 'expense', 'commission', 'refund']);
            $table->enum('category', ['sale', 'purchase', 'fee', 'commission', 'withdrawal', 'adjustment']);
            $table->decimal('amount', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status', ['pending', 'completed', 'cancelled', 'failed'])->default('pending');
            $table->enum('payment_method', ['card', 'cash', 'bank_transfer', 'check', 'paypal', 'stripe', 'other'])->nullable();
            $table->text('description')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('payment_proof')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'status']);
            $table->index('transaction_date');
            $table->index('reference');
        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_transactions');
    }
};