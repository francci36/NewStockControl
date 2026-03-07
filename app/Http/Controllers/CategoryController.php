<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Crée rapidement une catégorie via AJAX
     */
    public function quickCreate(Request $request)
    {
        try {
            // Validation simple
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            // Générer le slug à partir du nom
            $slug = Str::slug($request->name);
            
            // Vérifier si le slug existe déjà et le rendre unique si nécessaire
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Créer la catégorie
            $category = Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => null,
                'image' => null,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Erreur création catégorie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
}