<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Liste des locales disponibles
     */
    protected $availableLocales = ['fr', 'en', 'pt', 'es', 'de'];

    public function handle(Request $request, Closure $next)
    {
        // Log pour debug
        Log::info('=== SETLOCALE MIDDLEWARE EXÉCUTÉ ===');
        Log::info('Session locale: ' . Session::get('locale'));
        
        // Récupérer la locale de la session ou utiliser la locale par défaut
        $locale = Session::get('locale', config('app.locale', 'fr'));
        
            // Normalisation : pt_BR -> pt (maintenant que le dossier s'appelle pt)
        if ($locale === 'pt_BR') {
            $locale = 'pt';
            Session::put('locale', $locale);
        }
        
        App::setLocale($locale);
        
        return $next($request);
    
        
        // Vérifier que la locale est valide
        if (!in_array($locale, $this->availableLocales)) {
            $locale = 'fr'; // Fallback sur français
            Session::put('locale', $locale);
            Log::warning('Locale invalide, fallback sur fr');
        }
        
        // Appliquer la locale à l'application
        App::setLocale($locale);
        
        Log::info('App locale set to: ' . App::getLocale());
        Log::info('=====================================');
        
        return $next($request);
    }
}