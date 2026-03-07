<?php
// database/migrations/xxxx_xx_xx_merge_category_translations_into_json.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Récupérer toutes les catégories
        $categories = DB::table('categories')->get();
        
        foreach ($categories as $category) {
            // Récupérer les traductions existantes
            $translations = DB::table('category_translations')
                ->where('category_id', $category->id)
                ->get()
                ->pluck('name', 'locale')
                ->toArray();
            
            // Ajouter la valeur existante du champ name comme fallback français
            if (!isset($translations['fr']) && !empty($category->name)) {
                // Si name est déjà un JSON, le décoder
                if (is_string($category->name) && str_starts_with($category->name, '{')) {
                    $existingJson = json_decode($category->name, true);
                    if (is_array($existingJson)) {
                        $translations = array_merge($translations, $existingJson);
                    }
                } else {
                    $translations['fr'] = $category->name;
                }
            }
            
            // Mettre à jour la catégorie avec le JSON
            if (!empty($translations)) {
                DB::table('categories')
                    ->where('id', $category->id)
                    ->update(['name' => json_encode($translations)]);
            }
        }

        // Supprimer la table des traductions
        Schema::dropIfExists('category_translations');
    }

    public function down(): void
    {
        // Recréer la table si nécessaire (pour rollback)
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        });
    }
};