<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Exclure TOUTES les routes API (déjà présent)
        'api/*',
        
        // Exclure spécifiquement les webhooks vendeurs (optionnel - déjà couvert par api/*)
        // '/api/webhooks/vendor/*',
        
        // Exclure les webhooks transporteurs (AJOUT IMPORTANT)
        '/webhook/*',
        
        // Si vous avez des webhooks spécifiques, vous pouvez les ajouter individuellement
        // '/webhook/colissimo',
        // '/webhook/ups',
        // '/webhook/dhl',
        
        // En environnement local, vous pouvez exclure les routes de test
        // (si vous en avez besoin)
    ];
}