<?php
// database/migrations/xxxx_xx_xx_add_json_columns_to_products.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Ajouter des colonnes JSON temporaires
            $table->json('name_json')->nullable();
            $table->json('description_json')->nullable();
            $table->json('short_description_json')->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_json', 'description_json', 'short_description_json']);
        });
    }
};