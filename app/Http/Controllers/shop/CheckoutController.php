<?php
// app/Http/Controllers/Shop/CheckoutController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.checkout', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:card,cash,bank_transfer',
            'terms' => 'required|accepted',
        ]);

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        try {
            DB::beginTransaction();

            // Vérifier les stocks
            foreach ($cartItems as $item) {
                if ($item->product->quantity < $item->quantity) {
                    throw new \Exception("Stock insuffisant pour {$item->product->name}. Disponible: {$item->product->quantity}");
                }
            }

                    // Créer la commande client
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'client_id' => auth()->id(), // IMPORTANT : Définir le client
                    'supplier_id' => null, // Pas de fournisseur
                    'type' => 'client', // IMPORTANT : Définir le type
                    'date' => now(),
                    'status' => Order::STATUS_PENDING,
                    'shipping_name' => $request->shipping_name,
                    'shipping_address' => $request->shipping_address,
                    'shipping_city' => $request->shipping_city,
                    'shipping_zip' => $request->shipping_zip,
                    'shipping_country' => $request->shipping_country,
                    'shipping_phone' => $request->shipping_phone,
                    'payment_method' => $request->payment_method,
                ]);

            $totalAmount = 0;

            // Ajouter les produits à la commande
            foreach ($cartItems as $item) {
                $order->products()->attach($item->product_id, [
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $totalAmount += $item->product->price * $item->quantity;

                // Mettre à jour le stock
                $item->product->decrement('quantity', $item->quantity);
                
                // Mettre à jour la table stocks
                Stock::updateOrCreate(
                    ['product_id' => $item->product_id],
                    ['quantity' => $item->product->fresh()->quantity]
                );

                // Créer la transaction de sortie
                Transaction::create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'type' => 'exit',
                    'order_id' => $order->id,
                ]);

                Log::info('Vente en ligne', [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'user_id' => auth()->id()
                ]);
            }

            // Mettre à jour le total de la commande
            $order->update(['total_amount' => $totalAmount]);

            // Vider le panier
            $this->clearCart();

            DB::commit();

            return redirect()->route('shop.checkout.confirmation', $order)
                ->with('success', 'Commande confirmée !');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur checkout: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de la commande: ' . $e->getMessage())
                         ->withInput();
        }
    }

    public function confirmation(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('products');
        
        // Calculer le total
        $order->total = $order->products->sum(function($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });

        return view('shop.orders.confirmation', compact('order'));
    }

    private function getCartItems()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');

        if ($userId) {
            return Cart::with('product')
                ->where('user_id', $userId)
                ->get();
        }

        if ($sessionId) {
            return Cart::with('product')
                ->where('session_id', $sessionId)
                ->get();
        }

        return collect();
    }

    private function clearCart()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');

        if ($userId) {
            Cart::where('user_id', $userId)->delete();
        } elseif ($sessionId) {
            Cart::where('session_id', $sessionId)->delete();
        }
    }
}