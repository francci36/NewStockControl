<?php
// app/Http/Controllers/Client/OrderController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Affiche l'historique des commandes du client
     */
    public function index()
    {
        $user = auth()->user();
        
        $orders = Order::where('user_id', $user->id)
            ->with('products')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    /**
     * Affiche les détails d'une commande spécifique
     */
    public function show(Order $order)
    {
        // Vérifier que la commande appartient bien à l'utilisateur connecté
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette commande.');
        }

        $order->load('products');
        
        return view('client.orders.show', compact('order'));
    }
    // Affiche le suivi d'une commande spécifique
    public function tracking(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['statusHistory.user', 'products', 'payment']);
        
        return view('client.orders.tracking', compact('order'));
    }
}