<?php
// app/Http/Controllers/Vendor/CategoryController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        
        // Récupérer UNIQUEMENT les sous-catégories (celles qui ont un parent)
        $categories = Category::with('parent')
            ->whereNotNull('parent_id')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->paginate(20);
        
        return view('vendor.categories.index', compact('categories'));
    }

    public function create()
    {
        // Récupérer les catégories principales pour le select
        $mainCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('vendor.categories.create', compact('mainCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_category' => 'required|exists:categories,id',
            'name' => 'required|string|max:255', // Un seul champ name
        ]);

        $slug = Str::slug($request->name);
        
        // Vérifier l'unicité du slug
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // HasTranslations s'occupe de stocker la traduction
        // La valeur entrée sera la traduction française par défaut
        Category::create([
            'parent_id' => $request->main_category,
            'name' => $request->name, // Sera stocké comme valeur par défaut (français)
            'slug' => $slug,
            'is_active' => true,
        ]);

        return redirect()->route('vendor.categories.index')
            ->with('success', 'Sous-catégorie créée avec succès.');
    }

    public function edit(Category $category)
    {
        // Vérifier que c'est bien une sous-catégorie
        if (is_null($category->parent_id)) {
            abort(403, 'Vous ne pouvez pas modifier une catégorie principale.');
        }

        // Récupérer les catégories principales
        $mainCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('vendor.categories.edit', compact('category', 'mainCategories'));
    }

    public function update(Request $request, Category $category)
    {
        if (is_null($category->parent_id)) {
            abort(403);
        }

        $request->validate([
            'main_category' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            'parent_id' => $request->main_category,
            'name' => $request->name,
        ]);

        return redirect()->route('vendor.categories.index')
            ->with('success', 'Sous-catégorie mise à jour avec succès.');
    }

    public function destroy(Category $category)
    {
        if (is_null($category->parent_id)) {
            abort(403);
        }

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une catégorie qui contient des produits.');
        }

        $category->delete();

        return redirect()->route('vendor.categories.index')
            ->with('success', 'Sous-catégorie supprimée avec succès.');
    }
}