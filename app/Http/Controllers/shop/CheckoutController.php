<?php
// app/Http/Controllers/Shop/CheckoutController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Payment;
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

    /**
     * Créer un PaymentIntent Stripe
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|integer|min:50'
            ]);

            // Initialiser Stripe avec la clé secrète
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
            return response()->json(['error' => 'Erreur lors de la création du paiement'], 500);
        }
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
            'payment_method' => 'required|in:card,cash',
            'terms' => 'required|accepted',
            'stripe_payment_intent_id' => 'required_if:payment_method,card|string|nullable',
            'stripe_payment_method_id' => 'required_if:payment_method,card|string|nullable',
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
                        throw new \Exception('Le paiement n\'a pas été complété');
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

                } catch (\Exception $e) {
                    Log::error('Erreur vérification paiement Stripe: ' . $e->getMessage());
                    throw new \Exception('Erreur lors de la vérification du paiement');
                }
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

            // 2. Notification à l'admin (email configuré dans .env)
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

            // 3. Optionnel : Notification SMS (si vous avez un service)
            // try {
            //     // Code pour SMS avec Twilio ou autre service
            // } catch (\Exception $e) {
            //     Log::error('Erreur envoi SMS: ' . $e->getMessage());
            // }

            return redirect()->route('shop.checkout.confirmation', $order)
                ->with('success', 'Commande confirmée ! Un email de confirmation vous a été envoyé.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur checkout: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
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

        $order->load('products'); // ← Enlevez 'payment' d'ici si présent
        
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