<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add this field for user roles
        'profile_photo_url', // Add this field for profile photo uploads
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

        /**
     * Relation avec le vendeur
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Vérifie si l'utilisateur est un vendeur approuvé
     */
    public function isVendor()
    {
        return $this->role === 'vendor' && $this->vendor && $this->vendor->status === 'active';
    }

    /**
     * Vérifie si l'utilisateur peut devenir vendeur
     */
    public function canBecomeVendor()
    {
        return !$this->vendor && $this->role !== 'admin';
    }

    /**
     * Met à jour le rôle de l'utilisateur en vendeur
     */
    public function setAsVendor()
    {
        $this->role = 'vendor';
        $this->save();
    }
}
