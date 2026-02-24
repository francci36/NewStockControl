<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    
     protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'quantity',
        'price',
        'shipped_at',
        'tracking_number',
        'carrier',
        'status'
    ];

        protected $casts = [
        'shipped_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    // Relation avec une commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relation avec un produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation avec un vendeur
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Accessoire pour le prix total (quantité x prix unitaire)
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }
}