<?php
// database/migrations/2026_02_17_164845_add_shipping_fields_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les ajouter
            if (!Schema::hasColumn('orders', 'shipping_name')) {
                $table->string('shipping_name')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping_name');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_address');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_zip')) {
                $table->string('shipping_zip')->nullable()->after('shipping_city');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_country')) {
                $table->string('shipping_country')->nullable()->after('shipping_zip');
            }
            
            if (!Schema::hasColumn('orders', 'shipping_phone')) {
                $table->string('shipping_phone')->nullable()->after('shipping_country');
            }
            
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('shipping_phone');
            }
            
            // Ne pas ajouter total_amount car il existe déjà
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_address',
                'shipping_city',
                'shipping_zip',
                'shipping_country',
                'shipping_phone',
                'payment_method',
                // Ne pas supprimer total_amount car il existait déjà
            ]);
        });
    }
};