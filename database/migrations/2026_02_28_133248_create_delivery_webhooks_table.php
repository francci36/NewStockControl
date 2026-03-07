<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('delivery_webhooks', function (Blueprint $table) {
        $table->id();
        $table->string('provider'); // 'colissimo', 'ups', 'dhl'
        $table->string('tracking_number');
        $table->foreignId('order_id')->nullable()->constrained();
        $table->string('event'); // 'delivered', 'in_transit', 'exception'
        $table->json('payload');
        $table->json('raw_data')->nullable();
        $table->string('status')->default('received');
        $table->timestamp('processed_at')->nullable();
        $table->timestamps();
        
        $table->index('tracking_number');
        $table->index('status');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_webhooks');
    }
};
