<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard administrateur
     */
    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalOrders' => Order::count(),
            'totalProducts' => Product::count(),
            'totalSuppliers' => Supplier::count(),
            'totalTransactions' => Transaction::count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentOrders' => Order::with('user')->latest()->take(5)->get(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Gestion des utilisateurs
     */
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Statistiques avancées
     */
    public function statistics()
    {
        // Statistiques des commandes par mois
        $ordersByMonth = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top produits
        $topProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();

        // Activité des utilisateurs
        $userActivity = User::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.statistics', compact('ordersByMonth', 'topProducts', 'userActivity'));
    }

    /**
     * Configuration de l'application
     */
    public function config()
    {
        return view('admin.config');
    }

    public function updateConfig(Request $request)
    {
        // Sauvegarder la configuration
        foreach ($request->except('_token') as $key => $value) {
            config(["app.$key" => $value]);
        }

        return redirect()->route('admin.config')
            ->with('success', 'Configuration mise à jour.');
    }
}