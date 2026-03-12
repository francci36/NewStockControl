<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'stock_threshold')) {
            Schema::table('products', function (Blueprint $table) {
                $table->integer('stock_threshold')->default(5);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'stock_threshold')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stock_threshold');
            });
        }
    }
};