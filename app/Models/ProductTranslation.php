<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = ['product_id', 'locale', 'name', 'description', 'short_description'];
    
    public $timestamps = true;
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}