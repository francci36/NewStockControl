<?php
// database/migrations/xxxx_xx_xx_add_type_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('type')->default('supplier')->after('supplier_id');
            $table->foreignId('client_id')->nullable()->constrained('users')->after('type');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['type', 'client_id']);
        });
    }
};