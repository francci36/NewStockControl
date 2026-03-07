<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_address_fields_to_vendors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Ajouter les champs d'adresse complémentaires
            $table->string('city')->nullable()->after('address');
            $table->string('postal_code', 20)->nullable()->after('city');
            $table->string('country')->nullable()->after('postal_code');
            
            // Ajouter le champ website
            $table->string('website')->nullable()->after('bank_account');
            
            // Note: Les champs 'address' et 'phone' existent déjà dans votre table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'postal_code',
                'country',
                'website'
            ]);
        });
    }
};