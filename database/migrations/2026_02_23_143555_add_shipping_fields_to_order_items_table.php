<?php
// database/migrations/xxxx_xx_xx_add_shipping_fields_to_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->timestamp('shipped_at')->nullable()->after('price');
            $table->string('tracking_number')->nullable()->after('shipped_at');
            $table->string('carrier')->nullable()->after('tracking_number');
            $table->string('status')->default('pending')->after('carrier');
            $table->timestamp('delivered_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['shipped_at', 'tracking_number', 'carrier', 'status', 'delivered_at']);
        });
    }
};