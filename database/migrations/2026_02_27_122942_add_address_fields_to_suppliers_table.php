<?php

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
        Schema::table('suppliers', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les ajouter
            if (!Schema::hasColumn('suppliers', 'address')) {
                $table->string('address')->nullable()->after('telephone');
            }
            
            if (!Schema::hasColumn('suppliers', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('suppliers', 'postal_code')) {
                $table->string('postal_code', 20)->nullable()->after('city');
            }
            
            if (!Schema::hasColumn('suppliers', 'country')) {
                $table->string('country')->nullable()->after('postal_code');
            }
            
            if (!Schema::hasColumn('suppliers', 'website')) {
                $table->string('website')->nullable()->after('country');
            }
            
            if (!Schema::hasColumn('suppliers', 'tax_id')) {
                $table->string('tax_id')->nullable()->after('website');
            }
            
            if (!Schema::hasColumn('suppliers', 'payment_terms')) {
                $table->string('payment_terms')->nullable()->after('tax_id');
            }
            
            if (!Schema::hasColumn('suppliers', 'is_preferred')) {
                $table->boolean('is_preferred')->default(false)->after('payment_terms');
            }
            
            // NE PAS AJOUTER is_active car elle existe déjà
            // if (!Schema::hasColumn('suppliers', 'is_active')) {
            //     $table->boolean('is_active')->default(true)->after('is_preferred');
            // }
            
            if (!Schema::hasColumn('suppliers', 'notes')) {
                $table->text('notes')->nullable()->after('is_active'); // is_active existe déjà
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Supprimer uniquement les colonnes qui ont été ajoutées
            $columns = [
                'address',
                'city',
                'postal_code',
                'country',
                'website',
                'tax_id',
                'payment_terms',
                'is_preferred',
                'notes'
            ];
            
            // Ne supprimer que les colonnes qui existent
            foreach ($columns as $column) {
                if (Schema::hasColumn('suppliers', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // NE PAS supprimer is_active
        });
    }
};