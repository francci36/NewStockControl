<?php
// database/migrations/xxxx_xx_xx_add_order_id_and_user_id_to_transactions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->after('type');
            $table->foreignId('user_id')->nullable()->constrained()->after('order_id');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['order_id', 'user_id']);
        });
    }
};