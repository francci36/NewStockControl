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
        'short_description',
        'price',
        'quantity',
        'supplier_id',
        'category_id',
        'image',
        'stock_threshold',
        'is_active',
    ];

    protected $attributes = [
        'stock_threshold' => 5,
        'quantity' => 0,
        'is_active' => true,
    ];

    // Relation avec le fournisseur
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relation avec la catégorie (À AJOUTER)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation avec les commandes
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    // Relation avec le stock
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    // Obtenir le stock disponible
    public function getStockAttribute()
    {
        return $this->quantity;
    }

    // Vérifier si le stock est bas
    public function isLowStock()
    {
        return $this->quantity <= $this->stock_threshold;
    }

    // Scope pour les produits actifs
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope pour les produits en stock
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    // Relation avec le vendeur
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


}