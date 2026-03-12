<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // Ajoutez cette ligne

class Product extends Model
{
    use HasFactory, HasTranslations; // Ajoutez HasTranslations

    protected $fillable = [
        'name',
        'sku',
        'description',
        'short_description',
        'price',
        'quantity',
        'supplier_id',
        'category_id',
        'vendor_id',
        'image',
        'stock_threshold',
        'is_active',
    ];

    protected $attributes = [
        'stock_threshold' => 5,
        'quantity' => 0,
        'is_active' => true,
    ];

    // Définir les champs traduisibles (pour Spatie)
    public $translatable = ['name', 'description', 'short_description'];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'short_description' => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }

    // Relation avec les traductions (table séparée)
    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }
    
    // Scope pour charger les traductions
    public function scopeWithTranslation($query, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        
        return $query->with(['translations' => function($q) use ($locale) {
            $q->where('locale', $locale);
        }]);
    }
    
    // ✅ ACCESSEUR POUR LE FRONT OFFICE (utilise Spatie d'abord, puis la table)
    public function getTranslatedNameAttribute()
    {
        try {
            // Essayer Spatie Translatable d'abord
            return $this->getTranslation('name', app()->getLocale());
        } catch (\Exception $e) {
            // Fallback sur l'ancienne méthode
            $locale = app()->getLocale();
            $shortLocale = str_contains($locale, '_') ? explode('_', $locale)[0] : $locale;
            
            if ($this->relationLoaded('translations')) {
                $translation = $this->translations->firstWhere('locale', $locale) ?? 
                              $this->translations->firstWhere('locale', $shortLocale);
                
                if ($translation && $translation->name) {
                    return $translation->name;
                }
            }
            
            $names = $this->name;
            if (is_array($names)) {
                return $names[$locale] ?? $names[$shortLocale] ?? $names['fr'] ?? 'Produit';
            }
            
            return $names ?? 'Produit';
        }
    }
    
    public function getTranslatedDescriptionAttribute()
    {
        try {
            return $this->getTranslation('description', app()->getLocale());
        } catch (\Exception $e) {
            $locale = app()->getLocale();
            $shortLocale = str_contains($locale, '_') ? explode('_', $locale)[0] : $locale;
            
            if ($this->relationLoaded('translations')) {
                $translation = $this->translations->firstWhere('locale', $locale) ?? 
                              $this->translations->firstWhere('locale', $shortLocale);
                
                if ($translation && $translation->description) {
                    return $translation->description;
                }
            }
            
            $descriptions = $this->description;
            if (is_array($descriptions)) {
                return $descriptions[$locale] ?? $descriptions[$shortLocale] ?? $descriptions['fr'] ?? '';
            }
            
            return $descriptions ?? '';
        }
    }
    
    // Accesseur pour la courte description, avec fallback similaire
    public function getTranslatedShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        
        // Utiliser Spatie Translatable
        try {
            return $this->getTranslation('short_description', $locale);
        } catch (\Exception $e) {
            // Fallback
            $shortDescriptions = $this->short_description;
            if (is_array($shortDescriptions)) {
                return $shortDescriptions[$locale] ?? $shortDescriptions['fr'] ?? '';
            }
            return $shortDescriptions ?? '';
        }
    }

    // ✅ ACCESSEUR POUR L'ADMIN
    public function getAdminNameAttribute()
    {
        if (is_array($this->name)) {
            return $this->name['fr'] ?? array_values($this->name)[0] ?? 'Sans nom';
        }
        return $this->name ?? 'Sans nom';
    }

    public function getAdminDescriptionAttribute()
    {
        if (is_array($this->description)) {
            return $this->description['fr'] ?? array_values($this->description)[0] ?? '';
        }
        return $this->description ?? '';
    }

    public function getAdminShortDescriptionAttribute()
    {
        if (is_array($this->short_description)) {
            return $this->short_description['fr'] ?? array_values($this->short_description)[0] ?? '';
        }
        return $this->short_description ?? '';
    }

    // Méthodes utilitaires
    public function isLowStock()
    {
        return $this->quantity <= $this->stock_threshold;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
 * Récupère la description courte traduite de façon sécurisée
 */
    public function getSafeShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $shortDesc = $this->getTranslation('short_description', $locale);
        
        // Vérifier si c'est du JSON vide ou null
        if (!empty($shortDesc) && $shortDesc !== '[]' && $shortDesc !== 'null' && !is_array($shortDesc)) {
            return $shortDesc;
        }
        
        // Fallback sur la description complète
        $desc = $this->getTranslation('description', $locale);
        if (!empty($desc) && $desc !== '[]' && $desc !== 'null' && !is_array($desc)) {
            return Str::limit($desc, 50);
        }
        
        return '';
    }
}