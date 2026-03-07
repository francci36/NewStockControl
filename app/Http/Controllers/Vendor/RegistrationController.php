<?php
// app/Http/Controllers/Vendor/RegistrationController.php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use App\Notifications\VendorRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     */
     public function create()
    {
        $user = auth()->user();
        
        // Vérifier si l'utilisateur peut devenir vendeur
        if (!$user->canBecomeVendor()) {
            // CHANGEMENT ICI : rediriger vers shop.home au lieu de home
            return redirect()->route('shop.home')
                ->with('error', 'Vous ne pouvez pas demander à devenir vendeur.');
        }

        // Vérifier si l'utilisateur a déjà une demande en attente
        if ($user->vendor && $user->vendor->status === 'pending') {
            return redirect()->route('vendor.pending')
                ->with('info', 'Vous avez déjà une demande en cours de traitement.');
        }

        return view('vendor.register');
    }

    /**
     * Traite la demande d'inscription
     */
     public function store(Request $request)
    {
        $request->validate([
             'shop_name' => 'required|string|max:255|unique:vendors',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'phone' => 'required|string|max:20',
            'business_number' => 'required|string|max:50',
            'bank_account' => 'required|string|max:50',
            'terms' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();

            // Créer la demande de vendeur
            $vendor = Vendor::create([
                'user_id' => auth()->id(),
                'shop_name' => $request->shop_name,
                'slug' => Vendor::generateUniqueSlug($request->shop_name),
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'website' => $request->website,
                'phone' => $request->phone,
                'business_number' => $request->business_number,
                'bank_account' => $request->bank_account,
                'commission_rate' => 10.00, // Commission par défaut
                'status' => 'pending',
            ]);

            // 🔔 NOTIFICATION : Envoyer à l'admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new VendorRequestNotification($vendor));
            }

            DB::commit();

            return redirect()->route('vendor.pending')
                ->with('success', 'Votre demande a été envoyée. Nous vous contacterons sous 48h.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur inscription vendeur: ' . $e->getMessage());
            
            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Page d'attente après inscription
     */
    public function pending()
    {
        $vendor = auth()->user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.register');
        }

        return view('vendor.pending', compact('vendor'));
    }
}