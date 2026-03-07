<?php
// database/migrations/xxxx_xx_xx_rename_json_columns_in_products.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Supprimer les anciennes colonnes
            $table->dropColumn(['name', 'description', 'short_description']);
        });
        
        Schema::table('products', function (Blueprint $table) {
            // Renommer les colonnes JSON
            $table->renameColumn('name_json', 'name');
            $table->renameColumn('description_json', 'description');
            $table->renameColumn('short_description_json', 'short_description');
        });
    }

    public function down()
    {
        // Opération inverse (si nécessaire)
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
        });
    }
};