<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Log pour debug
        Log::info('=== SETLOCALE MIDDLEWARE EXÉCUTÉ ===');
        Log::info('Session locale: ' . Session::get('locale'));
        
        // Récupérer la locale de la session ou utiliser la locale par défaut
        $locale = Session::get('locale', config('app.locale', 'fr'));
        
        // Appliquer la locale à l'application
        App::setLocale($locale);
        
        Log::info('App locale set to: ' . App::getLocale());
        Log::info('=====================================');
        
        return $next($request);
    }
}