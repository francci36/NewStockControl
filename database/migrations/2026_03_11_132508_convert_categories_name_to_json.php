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
        $categories = DB::table('categories')->get();
        
        // Modifier la colonne pour qu'elle accepte le JSON
        Schema::table('categories', function (Blueprint $table) {
            $table->json('name')->change();
        });

        // Convertir les données existantes au format JSON
        foreach ($categories as $category) {
            $name = $category->name;
            
            // Vérifier si c'est déjà du JSON
            $isJson = json_decode($name, true) !== null;
            
            if (!$isJson) {
                // Créer un tableau JSON avec le nom en français par défaut
                $jsonName = json_encode(['fr' => $name]);
                
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['name' => $jsonName]);
            }
        }
    }

    public function down(): void
    {
        // Revenir en arrière (optionnel)
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->change();
        });
    }
};