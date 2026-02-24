<?php
// app/Http/Middleware/VendorMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Vérifier si l'utilisateur est admin (les admins ont aussi accès)
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Vérifier si l'utilisateur est un vendeur approuvé
        if (!$user->isVendor()) {
            if ($user->vendor && $user->vendor->status === 'pending') {
                return redirect()->route('vendor.pending')
                    ->with('info', 'Votre demande est en cours de traitement.');
            }
            
            return redirect()->route('home')
                ->with('error', 'Accès réservé aux vendeurs.');
        }

        return $next($request);
    }
}