<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    
    public function index(Request $request)
{
    $query = Order::with(['supplier', 'client', 'products']);
    
    // Filtrer par type
    if ($request->has('type') && $request->type == 'client') {
        $query->where('type', 'client');
    } else {
        $query->where('type', 'supplier'); // Par défaut, afficher les commandes fournisseurs
    }
    
    $orders = $query->orderBy('created_at', 'desc')->paginate(20);
    
    return view('orders.index', compact('orders'));
}

    public function create(Request $request)
    {
        $supplier_id = $request->get('supplier_id');
        $suppliers = Supplier::all();
        $products = $supplier_id ? Product::where('supplier_id', $supplier_id)->get() : collect();

        return view('orders.create', compact('suppliers', 'products', 'supplier_id'));
    }

    public function store(Request $request)
{
    // Validation
    $validated = $request->validate([
        'supplier_id' => 'required|exists:suppliers,id',
        'date' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    try {
        DB::beginTransaction();

        // 1. Créer la commande
        $order = Order::create([
            'user_id' => auth()->id(),
            'supplier_id' => $validated['supplier_id'],
            'date' => $validated['date'],
            'status' => Order::STATUS_PENDING,
            'type' => 'supplier', // Ajoutez ceci si nécessaire
        ]);

        // 2. Calculer le total
        $totalAmount = 0;
        $productsToAttach = [];

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $productsToAttach[$item['product_id']] = [
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ];
            $totalAmount += $product->price * $item['quantity'];
        }

        // 3. Attacher les produits à la commande
        $order->products()->attach($productsToAttach);

        // 4. Mettre à jour le total de la commande
        $order->total_amount = $totalAmount;
        $order->save();

        DB::commit();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Commande créée avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur création commande: ' . $e->getMessage());
        
        return back()->with('error', 'Erreur: ' . $e->getMessage())
                     ->withInput();
    }
}

    public function show(Order $order)
    {
        $order->load(['user', 'supplier', 'products']);
        return view('orders.show', compact('order'));
    }

public function updateStatus(Request $request, Order $order)
{
    // Valider que le statut est dans la liste des statuts autorisés
    $request->validate([
        'status' => 'required|in:' . implode(',', [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED,
            Order::STATUS_ARRIVED,
            Order::STATUS_CANCELLED
        ]),
        'comment' => 'nullable|string|max:500',
    ]);

    try {
        DB::beginTransaction();

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Utiliser la méthode avec historique
        $order->updateStatusWithHistory(
            $newStatus, 
            $request->comment ?? 'Mise à jour du statut',
            auth()->id()
        );

        // Gestion des stocks selon le type de commande
        if ($order->type === 'supplier') {
            // Pour les commandes fournisseurs
            if ($oldStatus === Order::STATUS_PENDING && $newStatus === Order::STATUS_ARRIVED) {
                foreach ($order->products as $product) {
                    $product->quantity += $product->pivot->quantity;
                    $product->save();
                    
                    Stock::updateOrCreate(
                        ['product_id' => $product->id],
                        ['quantity' => $product->quantity]
                    );
                }
                Log::info('Stock augmenté pour commande fournisseur', ['order_id' => $order->id]);
            }
        } else {
            // Pour les commandes clients
            // Si la commande est annulée, on pourrait remettre le stock
            if ($newStatus === Order::STATUS_CANCELLED) {
                foreach ($order->products as $product) {
                    $product->quantity += $product->pivot->quantity;
                    $product->save();
                    
                    Stock::updateOrCreate(
                        ['product_id' => $product->id],
                        ['quantity' => $product->quantity]
                    );
                }
                Log::info('Stock remis pour commande client annulée', ['order_id' => $order->id]);
            }
        }

        DB::commit();

        // Message personnalisé selon le type
        $message = $order->type === 'client' 
            ? 'Statut de la commande client mis à jour avec succès.'
            : 'Statut de la commande fournisseur mis à jour avec succès.';

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur mise à jour statut: ' . $e->getMessage());
        
        return back()->with('error', 'Erreur lors de la mise à jour du statut: ' . $e->getMessage());
    }
}

    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            if ($order->status === Order::STATUS_ARRIVED) {
                return back()->with('error', 'Impossible de supprimer une commande déjà arrivée.');
            }

            $order->products()->detach();
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Commande supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function getProductsBySupplier($supplierId)
    {
        $products = Product::where('supplier_id', $supplierId)
                          ->select('id', 'name', 'price')
                          ->get();
        
        return response()->json($products);
    }
}