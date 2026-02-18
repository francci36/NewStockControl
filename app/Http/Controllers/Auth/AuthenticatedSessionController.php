<?php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

   public function store(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        $request->session()->regenerate();

        $user = Auth::user();
        
        // Redirection basée sur le rôle
        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }
        
        // Pour les utilisateurs normaux, retour à la boutique
        return redirect()->intended('/');
    }

    throw ValidationException::withMessages([
        'email' => __('auth.failed'),
    ]);
}

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}