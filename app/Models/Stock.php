<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity'];

    // Relation avec la catégorie
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Relation avec les commandes
    public function orders() {
        return $this->belongsToMany(Order::class);
    }
}
