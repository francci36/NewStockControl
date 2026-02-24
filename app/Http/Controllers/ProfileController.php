<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $fillable = ['name', 'email', 'password', 'profile_photo_url'];

    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

  public function update(Request $request)
{
    $user = $request->user();

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif'], // max 2MB
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
    ];

    // Gestion de la photo de profil
    if ($request->hasFile('profile_photo')) {
        // Vérifier que le fichier est valide
        if (!$request->file('profile_photo')->isValid()) {
            return back()->with('error', 'Le fichier est corrompu ou invalide.');
        }

        try {
            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_photo_url) {
                Storage::disk('public')->delete($user->profile_photo_url);
            }
            
            // Sauvegarder la nouvelle photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_url'] = $path;
            
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du téléchargement de la photo: ' . $e->getMessage());
        }
    }

    $user->update($data);

    return redirect()->route('admin.profile.edit')->with('success', 'Profil mis à jour avec succès !');
}

public function updatePhoto(Request $request)
{
    $request->validate([
        'profile_photo' => 'required|image|max:2048', // max 2MB
    ]);

    $user = $request->user();

    // Supprimer l'ancienne photo si elle existe
    if ($user->profile_photo_url) {
        Storage::disk('public')->delete($user->profile_photo_url);
    }

    // Sauvegarder la nouvelle photo
    $path = $request->file('profile_photo')->store('profile-photos', 'public');
    $user->profile_photo_url = $path;
    $user->save();

    return redirect()->route('admin.profile.edit')
        ->with('photo_success', 'Photo de profil mise à jour avec succès !');
}

    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
