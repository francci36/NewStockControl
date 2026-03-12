<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sauvegarder les données existantes
        $products = DB::table('products')->get();
        
        // Modifier les colonnes
        Schema::table('products', function (Blueprint $table) {
            // Convertir name en JSON
            $table->json('name')->change();
            
            // Ajouter short_description si elle n'existe pas
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->json('short_description')->nullable();
            }
            
            // Ajouter category_id
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')
                      ->nullable()
                      ->constrained()
                      ->nullOnDelete();
            }
            
            // description devrait déjà être là, mais on s'assure qu'elle est JSON
            if (Schema::hasColumn('products', 'description')) {
                $table->json('description')->change();
            }
        });

        // Restaurer les données dans le bon format
        foreach ($products as $product) {
            $updateData = [];
            
            // Convertir name en structure JSON
            if ($product->name) {
                $updateData['name'] = json_encode(['fr' => $product->name]);
            }
            
            // Convertir description si elle existe
            if (isset($product->description) && $product->description) {
                $updateData['description'] = json_encode(['fr' => $product->description]);
            }
            
            if (!empty($updateData)) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update($updateData);
            }
        }
    }

    public function down(): void
    {
        // Reconvertir en string simple
        $products = DB::table('products')->get();
        
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->change();
            $table->dropColumn('short_description');
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        foreach ($products as $product) {
            $names = json_decode($product->name, true);
            if (is_array($names) && isset($names['fr'])) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['name' => $names['fr']]);
            }
        }
    }
};