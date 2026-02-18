<?php
// app/Http/Middleware/ShareCartCount.php

namespace App\Http\Middleware;

use Closure;
use App\Models\Cart;
use Illuminate\Http\Request;

class ShareCartCount
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('shop*')) {
            $userId = auth()->id();
            $sessionId = session()->get('cart_session_id');
            
            $cartCount = 0;
            
            if ($userId) {
                $cartCount = Cart::where('user_id', $userId)->count();
            } elseif ($sessionId) {
                $cartCount = Cart::where('session_id', $sessionId)->count();
            }
            
            view()->share('cartCount', $cartCount);
        }

        return $next($request);
    }
}