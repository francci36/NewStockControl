<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('status');
            $table->string('carrier')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('carrier');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->json('tracking_events')->nullable()->after('delivered_at');
            $table->string('reference')->unique()->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number',
                'carrier', 
                'shipped_at',
                'delivered_at',
                'tracking_events',
                'reference'
            ]);
        });
    }
};