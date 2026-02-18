<?php
// app/Http/Controllers/Shop/CartController.php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Vérifier le stock
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Stock insuffisant. Stock disponible: ' . $product->quantity);
        }

        // Vérifier la quantité dans le panier actuel
        $currentCartQuantity = 0;
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');
        
        if ($userId) {
            $existingCart = Cart::where('user_id', $userId)
                ->where('product_id', $request->product_id)
                ->first();
            if ($existingCart) {
                $currentCartQuantity = $existingCart->quantity;
            }
        } elseif ($sessionId) {
            $existingCart = Cart::where('session_id', $sessionId)
                ->where('product_id', $request->product_id)
                ->first();
            if ($existingCart) {
                $currentCartQuantity = $existingCart->quantity;
            }
        }

        $totalQuantity = $currentCartQuantity + $request->quantity;
        if ($totalQuantity > $product->quantity) {
            return back()->with('error', 'Quantité totale dans le panier dépasse le stock. Maximum: ' . $product->quantity);
        }

        // Identifier l'utilisateur (connecté ou session)
        if (!$userId && !$sessionId) {
            $sessionId = Str::random(40);
            session()->put('cart_session_id', $sessionId);
        }

        // Ajouter au panier
        Cart::updateOrCreate(
            $userId 
                ? ['user_id' => $userId, 'product_id' => $request->product_id]
                : ['session_id' => $sessionId, 'product_id' => $request->product_id],
            ['quantity' => $totalQuantity]
        );

        return redirect()->route('shop.cart.index')
            ->with('success', 'Produit ajouté au panier !');
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Vérifier que le panier appartient à l'utilisateur
        if (!$this->cartBelongsToUser($cart)) {
            abort(403);
        }

        // Vérifier le stock
        if ($cart->product->quantity < $request->quantity) {
            return back()->with('error', 'Stock insuffisant. Maximum: ' . $cart->product->quantity);
        }

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('shop.cart.index')
            ->with('success', 'Panier mis à jour !');
    }

    public function remove(Cart $cart)
    {
        if (!$this->cartBelongsToUser($cart)) {
            abort(403);
        }

        $cart->delete();

        return redirect()->route('shop.cart.index')
            ->with('success', 'Produit retiré du panier !');
    }

    public function clear()
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');

        if ($userId) {
            Cart::where('user_id', $userId)->delete();
        } elseif ($sessionId) {
            Cart::where('session_id', $sessionId)->delete();
        }

        return redirect()->route('shop.cart.index')
            ->with('success', 'Panier vidé !');
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

    private function cartBelongsToUser(Cart $cart)
    {
        $userId = auth()->id();
        $sessionId = session()->get('cart_session_id');

        if ($userId && $cart->user_id == $userId) {
            return true;
        }

        if ($sessionId && $cart->session_id == $sessionId) {
            return true;
        }

        return false;
    }
}