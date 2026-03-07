<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Vendor;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Rediriger vers le contrôleur admin ou vendor selon le contexte
        if (auth()->user()->isAdmin()) {
            return app(Admin\OrderController::class)->index($request);
        } elseif (auth()->user()->isVendor()) {
            return app(Vendor\OrderController::class)->index($request);
        }
        
        return redirect()->route('shop.home');
    }

    public function create(Request $request)
    {
        $supplier_id = $request->get('supplier_id');
        $suppliers = Supplier::orderBy('name')->get();
        
        // Récupérer les produits avec leurs noms traduits
        $products = $supplier_id 
            ? Product::where('supplier_id', $supplier_id)->get()->map(function($product) {
                $product->admin_name = $product->admin_name;
                return $product;
            })
            : collect();

        return view('admin.orders.create', compact('suppliers', 'products', 'supplier_id'));
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
                'type' => 'supplier',
            ]);

            // 2. Calculer le total et préparer les items
            $totalAmount = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                
                // S'assurer que le vendor_id est bien défini (peut être null)
                $vendorId = $product->vendor_id ?? null;
                
                $items[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'vendor_id' => $vendorId,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $totalAmount += $product->price * $item['quantity'];
            }

            // 3. Insérer tous les items en une seule requête (optimisation)
            $order->items()->insert($items);

            // 4. Mettre à jour le total de la commande
            $order->total_amount = $totalAmount;
            $order->save();

            DB::commit();

            Log::info('Commande créée avec succès', [
                'order_id' => $order->id,
                'items_count' => count($items),
                'items_with_vendor' => count(array_filter($items, fn($i) => !is_null($i['vendor_id']))),
            ]);

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
        if (auth()->user()->isAdmin()) {
            return app(Admin\OrderController::class)->show($order);
        } elseif (auth()->user()->isVendor()) {
            return app(Vendor\OrderController::class)->show($order->id);
        }
        
        return redirect()->route('shop.home');
    }

    public function edit($id)
    {
        // Seulement pour les commandes fournisseurs
        return app(Admin\OrderController::class)->edit($id);
    }

    public function update(Request $request, $id)
    {
        // Seulement pour les commandes fournisseurs
        return app(Admin\OrderController::class)->update($request, $id);
    }

    /**
     * Mise à jour du statut avec création automatique de transaction
     */
   public function updateStatus(Request $request, Order $order)
{
    // Valider que le statut est dans la liste des statuts autorisés
    $request->validate([
        'status' => 'required|in:pending,processing,shipped,delivered,arrived,cancelled,refunded,paid',
        'comment' => 'nullable|string|max:500',
    ]);

    try {
        DB::beginTransaction();

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Vérifier les permissions selon le type d'utilisateur
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        $isVendor = $user->isVendor();
        
        if ($isVendor) {
            // Vérifier que le vendeur a des items dans cette commande
            $hasVendorItems = $order->items()
                ->where('vendor_id', $user->vendor->id)
                ->exists();
                
            if (!$hasVendorItems) {
                throw new \Exception('Vous ne pouvez pas modifier cette commande.');
            }
            
            // Restrictions pour les vendeurs
            $allowedTransitions = [
                'pending' => ['processing', 'shipped'],
                'processing' => ['shipped'],
            ];
            
            if (!in_array($newStatus, $allowedTransitions[$oldStatus] ?? [])) {
                throw new \Exception('Transition de statut non autorisée.');
            }
        }

        // Utiliser la méthode avec historique
        $order->updateStatusWithHistory(
            $newStatus, 
            $request->comment ?? ($isVendor ? 'Mise à jour par le vendeur' : 'Mise à jour par l\'admin'),
            $user->id
        );

        // ===== GESTION DES STOCKS ET TRANSACTIONS =====
        
        // Pour les commandes fournisseurs
        if ($order->type === 'supplier') {
            // Réception de commande : création de transactions d'entrée
            if ($oldStatus === Order::STATUS_PENDING && $newStatus === Order::STATUS_ARRIVED) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    
                    // 1. Créer une transaction de stock (ENTRÉE)
                    Transaction::create([
                        'type' => 'entry',
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total_value' => $item->quantity * $item->price,
                        'user_id' => $user->id,
                        'notes' => "Réception automatique - Commande fournisseur #{$order->id}"
                    ]);
                    
                    // 2. Créer une transaction financière (DÉPENSE)
                    FinancialTransaction::create([
                        'reference' => 'PURCHASE-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                        'type' => 'expense',
                        'category' => 'purchase',
                        'amount' => $item->price * $item->quantity,
                        'tax' => 0, // À ajuster selon votre configuration TVA
                        'total' => $item->price * $item->quantity,
                        'status' => 'completed',
                        'payment_method' => 'bank_transfer',
                        'description' => "Achat fournisseur #{$order->id} - {$product->admin_name}",
                        'transaction_date' => now(),
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'supplier_id' => $order->supplier_id,
                    ]);
                    
                    // Augmenter le stock
                    $product->quantity += $item->quantity;
                    $product->save();
                    
                    // Mettre à jour la table stocks
                    Stock::updateOrCreate(
                        ['product_id' => $product->id],
                        ['quantity' => $product->quantity]
                    );
                    
                    Log::info('Stock augmenté pour commande fournisseur', [
                        'order_id' => $order->id,
                        'product' => $product->admin_name,
                        'quantity' => $item->quantity
                    ]);
                }
            }
        } 
        // Pour les commandes clients
        else {
            // Commande validée : création de transactions de sortie et commissions
            if ($oldStatus === Order::STATUS_PENDING && in_array($newStatus, [Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED])) {
                $totalAmount = 0;
                
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $totalAmount += $item->quantity * $item->price;
                    
                    // 1. Créer une transaction de stock (SORTIE)
                    Transaction::create([
                        'type' => 'exit',
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total_value' => $item->quantity * $item->price,
                        'user_id' => $user->id,
                        'notes' => "Vente client - Commande #{$order->id}"
                    ]);
                    
                    // Diminuer le stock
                    $product->quantity -= $item->quantity;
                    $product->save();
                    
                    // Mettre à jour la table stocks
                    Stock::updateOrCreate(
                        ['product_id' => $product->id],
                        ['quantity' => $product->quantity]
                    );
                    
                    // 2. GÉNÉRER LES COMMISSIONS POUR LES PRODUITS MARKETPLACE
                    if ($item->vendor_id) {
                        $vendor = Vendor::find($item->vendor_id);
                        
                        if ($vendor && $vendor->commission_rate > 0) {
                            // Calculer la commission (pourcentage du prix de vente)
                            $commissionAmount = ($item->price * $item->quantity) * ($vendor->commission_rate / 100);
                            
                            Commission::create([
                                'vendor_id' => $vendor->id,
                                'order_id' => $order->id,
                                'order_item_id' => $item->id,
                                'amount' => $commissionAmount,
                                'rate' => $vendor->commission_rate,
                                'status' => 'pending',
                            ]);
                            
                            Log::info('Commission générée', [
                                'order_id' => $order->id,
                                'vendor' => $vendor->admin_shop_name,
                                'amount' => $commissionAmount
                            ]);
                        }
                    }
                    
                    Log::info('Stock diminué pour commande client', [
                        'order_id' => $order->id,
                        'product' => $product->admin_name,
                        'quantity' => $item->quantity
                    ]);
                }
                
                // 3. Créer une transaction financière (REVENU) pour toute la commande
                FinancialTransaction::create([
                    'reference' => 'SALE-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    'type' => 'income',
                    'category' => 'sale',
                    'amount' => $totalAmount / 1.2, // HT (si TVA 20%)
                    'tax' => $totalAmount - ($totalAmount / 1.2), // TVA
                    'total' => $totalAmount,
                    'status' => 'completed',
                    'payment_method' => $order->payment_method ?? 'card',
                    'description' => "Vente client #{$order->id} - " . ($order->shipping_name ?? 'Client'),
                    'transaction_date' => now(),
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                ]);
            }
            
            // Si la commande est annulée, on remet le stock (créer transaction d'entrée)
            if ($newStatus === Order::STATUS_CANCELLED && $oldStatus !== Order::STATUS_CANCELLED) {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    
                    // 1. Créer une transaction d'entrée pour le retour (stock)
                    Transaction::create([
                        'type' => 'entry',
                        'product_id' => $product->id,
                        'order_id' => $order->id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total_value' => $item->quantity * $item->price,
                        'user_id' => $user->id,
                        'notes' => "Annulation commande - Remise en stock #{$order->id}"
                    ]);
                    
                    // 2. Créer une transaction financière de remboursement
                    FinancialTransaction::create([
                        'reference' => 'REFUND-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                        'type' => 'expense',
                        'category' => 'refund',
                        'amount' => $item->price * $item->quantity,
                        'tax' => 0,
                        'total' => $item->price * $item->quantity,
                        'status' => 'completed',
                        'payment_method' => $order->payment_method ?? 'card',
                        'description' => "Remboursement commande #{$order->id} - Annulation",
                        'transaction_date' => now(),
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                    ]);
                    
                    // Remettre le stock
                    $product->quantity += $item->quantity;
                    $product->save();
                    
                    // Mettre à jour la table stocks
                    Stock::updateOrCreate(
                        ['product_id' => $product->id],
                        ['quantity' => $product->quantity]
                    );
                    
                    Log::info('Stock remis pour commande client annulée', [
                        'order_id' => $order->id,
                        'product' => $product->admin_name,
                        'quantity' => $item->quantity
                    ]);
                }
            }
        }

        DB::commit();

        $message = $isVendor 
            ? 'Statut de votre commande mis à jour avec succès.'
            : 'Statut de la commande mis à jour avec succès.';

        // Rediriger vers la bonne route selon le type d'utilisateur
        if ($isVendor) {
            return redirect()->route('vendor.orders.show', $order->id)
                ->with('success', $message);
        }
        
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

            $order->items()->delete();
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Commande supprimée avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * API pour récupérer les produits d'un fournisseur
     */
    public function getProductsBySupplier($supplierId)
    {
        try {
            Log::info('Chargement produits pour fournisseur: ' . $supplierId);
            
            $products = Product::where('supplier_id', $supplierId)
                            ->where('is_active', true)
                            ->get(['id', 'name', 'price', 'quantity', 'vendor_id'])
                            ->map(function ($product) {
                                // Ajouter le nom formaté pour le JavaScript
                                $product->admin_name = $product->admin_name;
                                return $product;
                            });
            
            Log::info('Produits trouvés: ' . $products->count());
            
            return response()->json($products);
        } catch (\Exception $e) {
            Log::error('Erreur chargement produits: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur de chargement'], 500);
        }
    }
}