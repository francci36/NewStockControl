<?php
// app/Http/Controllers/Shop/CheckoutController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Payment;
use App\Models\OrderStatusHistory;
use App\Mail\OrderConfirmation;
use App\Mail\NewOrderNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
   
    /**
     * Affiche la page de checkout
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('error', __('messages.empty_cart'));
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $shipping = $total >= 50 ? 0 : 5.99;
        $finalTotal = $total + $shipping;

        return view('shop.checkout.index', compact('cartItems', 'total', 'shipping', 'finalTotal'));
    }

    /**
     * Créer un PaymentIntent Stripe
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|integer|min:50'
            ]);

            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => 'eur',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()->email,
                    'user_name' => auth()->user()->name
                ]
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id
            ]);

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Erreur création payment intent: ' . $e->getMessage());
            return response()->json(['error' => __('messages.payment_intent_error')], 500);
        }
    }

    /**
     * Traite la commande
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:card,cash',
            'terms' => 'required|accepted',
            'stripe_payment_intent_id' => 'nullable|string',
            'stripe_payment_method_id' => 'nullable|string',
        ]);

        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.cart.index')
                ->with('error', __('messages.empty_cart'));
        }

        // ==============================================
        // VÉRIFICATION APPROFONDIE DES STOCKS
        // ==============================================
        $stockErrors = [];
        foreach ($cartItems as $item) {
            if ($item->product->quantity < $item->quantity) {
                $stockErrors[] = __('messages.stock_error_item', [
                    'product' => $item->product->translated_name,
                    'stock' => $item->product->quantity
                ]);
            }
        }

        if (!empty($stockErrors)) {
            return redirect()->route('shop.cart.index')
                ->with('stock_errors', $stockErrors)
                ->with('error', __('messages.stock_insufficient'));
        }
        // ==============================================

        try {
            DB::beginTransaction();

            // Créer la commande client
            $order = Order::create([
                'user_id' => auth()->id(),
                'client_id' => auth()->id(),
                'supplier_id' => null,
                'type' => 'client',
                'date' => now(),
                'status' => Order::STATUS_PENDING,
                'shipping_name' => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_zip' => $request->shipping_zip,
                'shipping_country' => $request->shipping_country,
                'shipping_phone' => $request->shipping_phone,
                'payment_method' => $request->payment_method,
                'total_amount' => 0, // Sera mis à jour après l'ajout des produits
            ]);

            // Créer l'historique de statut
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => Order::STATUS_PENDING,
                'comment' => __('messages.order_created'),
                'user_id' => auth()->id(),
            ]);

            $totalAmount = 0;

            // Ajouter les produits à la commande
            foreach ($cartItems as $item) {
                // Attacher le produit à la commande
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
                    'user_id' => auth()->id(),
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

            // Si paiement par carte, vérifier le statut Stripe
            if ($request->payment_method === 'card' && $request->stripe_payment_intent_id) {
                Stripe::setApiKey(config('services.stripe.secret'));
                
                try {
                    $paymentIntent = PaymentIntent::retrieve($request->stripe_payment_intent_id);
                    
                    if ($paymentIntent->status !== 'succeeded') {
                        throw new \Exception(__('messages.payment_not_completed'));
                    }

                    // Créer l'enregistrement de paiement
                    Payment::create([
                        'order_id' => $order->id,
                        'user_id' => auth()->id(),
                        'payment_method' => 'stripe',
                        'payment_id' => $paymentIntent->id,
                        'status' => 'completed',
                        'amount' => $totalAmount,
                        'currency' => 'eur',
                        'payer_email' => auth()->user()->email,
                        'payer_name' => auth()->user()->name,
                        'payment_details' => [
                            'payment_method' => $request->stripe_payment_method_id,
                            'status' => $paymentIntent->status
                        ]
                    ]);

                    // Mettre à jour le statut de la commande
                    $order->update(['status' => Order::STATUS_PAID]);
                    
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => Order::STATUS_PAID,
                        'comment' => __('messages.payment_received'),
                        'user_id' => auth()->id(),
                    ]);

                } catch (\Exception $e) {
                    Log::error('Erreur vérification paiement Stripe: ' . $e->getMessage());
                    throw new \Exception(__('messages.payment_verification_error'));
                }
            } else {
                // Paiement à la livraison
                $order->update(['status' => Order::STATUS_PROCESSING]);
                
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => Order::STATUS_PROCESSING,
                    'comment' => __('messages.order_processing'),
                    'user_id' => auth()->id(),
                ]);
            }

            // Vider le panier
            $this->clearCart();

            DB::commit();

            // ==============================================
            // ENVOI DES NOTIFICATIONS PAR EMAIL
            // ==============================================
            
            // 1. Email de confirmation au client
            try {
                Mail::to($order->client->email)->send(new OrderConfirmation($order));
                Log::info('Email de confirmation envoyé au client', [
                    'order_id' => $order->id, 
                    'email' => $order->client->email
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur envoi email confirmation client: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'exception' => $e
                ]);
            }

            // 2. Notification à l'admin
            try {
                $adminEmail = env('ADMIN_EMAIL', 'francci36@gmail.com');
                Mail::to($adminEmail)->send(new NewOrderNotification($order));
                Log::info('Notification admin envoyée', [
                    'order_id' => $order->id,
                    'email' => $adminEmail
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur envoi notification admin: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'exception' => $e
                ]);
            }

            return redirect()->route('shop.checkout.confirmation', $order)
                ->with('success', __('messages.order_confirmed'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur checkout: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', __('messages.checkout_error') . ' ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Page de confirmation de commande
     */
    public function confirmation(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['products' => function($query) {
            $query->withTranslation();
        }]);
        
        // Calculer le total
        $order->total = $order->products->sum(function($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });

        return view('shop.checkout.confirmation', compact('order'));
    }

    /**
     * Récupère les articles du panier
     */
    private function getCartItems()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');

        if ($userId) {
            return Cart::with('product.category', 'product.supplier')
                ->where('user_id', $userId)
                ->get();
        }

        if ($sessionId) {
            return Cart::with('product.category', 'product.supplier')
                ->where('session_id', $sessionId)
                ->get();
        }

        return collect();
    }

    /**
     * Vide le panier
     */
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