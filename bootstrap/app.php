<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ===========================================
        // MIDDLEWARES DU GROUPE WEB
        // ===========================================
        // Ajoute SetLocale au groupe de middlewares 'web'
        // Il sera exécuté sur toutes les routes web
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        // ===========================================
        // ALIAS DE MIDDLEWARES
        // ===========================================
        // Permet d'utiliser 'admin' dans les routes au lieu du chemin complet
        $middleware->alias([
            'admin' => AdminMiddleware::class,                     // Vérifie si l'utilisateur est admin
            'vendor' => \App\Http\Middleware\VendorMiddleware::class, // Vérifie si l'utilisateur est vendeur
            'share.cart' => \App\Http\Middleware\ShareCartCount::class, // Partage le nombre d'articles du panier
            'set.locale' => \App\Http\Middleware\SetLocale::class,      // Définit la langue (alias)
        ]);
        
        // ===========================================
        // EXCEPTIONS CSRF
        // ===========================================
        // Exclut les routes webhook de la protection CSRF
        // Car les webhooks viennent de services externes
        $middleware->validateCsrfTokens(except: [
            'api/webhooks/*',
            'webhooks/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gestion des exceptions
        // Vous pouvez personnaliser ici
    })->create();