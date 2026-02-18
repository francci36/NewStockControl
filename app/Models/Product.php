<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',  // Stock actuel
        'supplier_id',
        'stock_threshold', // Seuil d'alerte
    ];

    protected $attributes = [
        'stock_threshold' => 5, // Valeur par défaut
        'quantity' => 0, // Stock initial à 0
    ];

    // Relation avec le fournisseur
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relation avec les commandes (via table pivot)
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Relation avec le stock (si vous gardez la table stocks séparée)
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    // Obtenir le stock disponible (depuis la colonne quantity)
    public function getStockAttribute()
    {
        return $this->quantity;
    }

    // Vérifier si le stock est bas
    public function isLowStock()
    {
        return $this->quantity <= $this->stock_threshold;
    }
}