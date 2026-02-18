<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Vérifier si l'utilisateur est admin
        if (!auth()->user()->isAdmin()) {
            // Rediriger les utilisateurs non-admin vers la boutique
            return redirect()->route('shop.home')
                ->with('error', 'Accès non autorisé. Vous devez être administrateur.');
        }

        return $next($request);
    }
}