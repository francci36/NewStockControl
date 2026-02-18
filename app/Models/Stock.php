<?php
// app/Models/Stock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Synchroniser avec la quantité du produit
    public function syncWithProduct()
    {
        if ($this->product) {
            $this->quantity = $this->product->quantity;
            $this->save();
        }
    }
}