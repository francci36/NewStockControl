<?php

// app/Models/Commande.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'product',
        'quantity',
        'status',
        'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
