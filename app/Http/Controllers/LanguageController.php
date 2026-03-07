<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        Log::info('=== LANGUAGE CONTROLLER ===');
        Log::info('Changement vers: ' . $locale);
        
        // Vérifier si la langue est disponible
        if (array_key_exists($locale, config('app.available_locales'))) {
            // Stocker dans la session et forcer la sauvegarde
            Session::put('locale', $locale);
            Session::save(); // Force l'écriture en session
            
            // Appliquer immédiatement
            App::setLocale($locale);
            
            Log::info('Session locale après set: ' . Session::get('locale'));
            Log::info('App locale après set: ' . App::getLocale());
            
            session()->flash('success', 'Langue changée en ' . $locale);
        } else {
            Log::warning('Langue non disponible: ' . $locale);
            session()->flash('error', 'Langue non disponible');
        }
        
        Log::info('===========================');
        
        return redirect()->back();
    }
}