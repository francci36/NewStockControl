<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::all(); // Récupère tous les utilisateurs
        return view('admin.dashboard', compact('users'));
        dd('Méthode dashboard atteinte.'); 
    }
    


}
