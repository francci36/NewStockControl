<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // ... vos autres méthodes ...
    
    /**
     * Crée rapidement une catégorie via AJAX
     */
    public function quickCreate(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories',
                'slug' => 'required|string|max:255|unique:categories',
                'description' => 'nullable|string',
                'is_active' => 'boolean'
            ]);
            
            $category = Category::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true
            ]);
            
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur création rapide catégorie: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}