<?php
// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'type', // 'entry' ou 'exit'
        'order_id', // Ajouter ce champ (nullable)
        'user_id',  // Qui a effectué la transaction
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'float',
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    // Accesseur pour le type en français
    public function getTypeLabelAttribute()
    {
        return $this->type === 'entry' ? 'Entrée' : 'Sortie';
    }

    // Accesseur pour la couleur du type
    public function getTypeColorAttribute()
    {
        return $this->type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    // Accesseur pour la valeur totale
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Scope pour les entrées
    public function scopeEntries($query)
    {
        return $query->where('type', 'entry');
    }

    // Scope pour les sorties
    public function scopeExits($query)
    {
        return $query->where('type', 'exit');
    }
}