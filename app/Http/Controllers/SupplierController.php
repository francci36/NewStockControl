<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        // Paginer les résultats avec le comptage des produits et commandes
        $suppliers = Supplier::withCount(['products', 'orders'])->paginate(10);
        
        // Calculer les totaux pour les statistiques
        $totalProducts = Supplier::withCount('products')->get()->sum('products_count');
        $totalOrders = Supplier::withCount('orders')->get()->sum('orders_count');
        
        return view('suppliers.index', compact('suppliers', 'totalProducts', 'totalOrders'));
    }


    public function create()
    {
        $products = Product::all(); // récupère tous les produits
        return view('suppliers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'telephone' => 'required|string|max:20',
            // NOUVEAUX CHAMPS
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:50',
            'is_preferred' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // Ajouter les valeurs par défaut pour les champs booléens
        $validated['is_preferred'] = $request->has('is_preferred');
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Créer le fournisseur
        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur ajouté avec succès');
    }

    public function show($id)
    {
        $supplier = Supplier::with(['products', 'orders'])->findOrFail($id);
        
        // Statistiques de base
        $productsCount = $supplier->products->count();
        $ordersCount = $supplier->orders->count();
        $totalPurchases = $supplier->orders->sum('total_amount');
        
        // Panier moyen
        $averageOrderValue = $ordersCount > 0 ? round($totalPurchases / $ordersCount, 2) : 0;
        
        // Achats mensuels pour le graphique
        $monthlyPurchases = $supplier->orders()
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(total_amount) as total'))
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('suppliers.show', compact(
            'supplier', 
            'productsCount', 
            'ordersCount', 
            'totalPurchases',
            'averageOrderValue',
            'monthlyPurchases'
        ));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $id,
            'telephone' => 'required|string|max:20',
            // NOUVEAUX CHAMPS
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:50',
            'is_preferred' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        // Ajouter les valeurs pour les champs booléens
        $validated['is_preferred'] = $request->has('is_preferred');
        $validated['is_active'] = $request->has('is_active');

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')->with('success', 'Fournisseur mis à jour avec succès');
    }

    public function destroy($id)
    {
        // Trouver le fournisseur
        $supplier = Supplier::findOrFail($id);

        // Supprimer les produits associés à ce fournisseur
        $supplier->products()->delete();

        // Supprimer le fournisseur
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function storeWithProducts(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock_threshold' => 'nullable|integer|min:0',
            'products.*.description' => 'nullable|string', // Ajouter la description
        ]);

        DB::beginTransaction();

        try {
            // Trouver le fournisseur
            $supplier = Supplier::findOrFail($validatedData['supplier_id']);

            // Ajouter les produits associés
            foreach ($validatedData['products'] as $productData) {
                $supplier->products()->create([
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? null, // Description facultative
                    'price' => $productData['price'],
                    'stock_threshold' => $productData['stock_threshold'] ?? 5, // Valeur par défaut
                    'quantity' => 0, // Initialisation à 0 par défaut
                ]);
            }

            DB::commit(); // Valider la transaction
            return redirect()->route('suppliers.index')->with('success', 'Produits ajoutés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur
            Log::error('Erreur lors de l\'ajout des produits : ', ['error' => $e->getMessage()]);
            return redirect()->route('suppliers.index')->with('error', 'Une erreur est survenue lors de l\'ajout des produits.');
        }
    }

}