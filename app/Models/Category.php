<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; 

class Category extends Model
{
    use HasFactory, HasTranslations; 

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];

    // Définir les champs traduisibles
    public $translatable = ['name', 'description'];

    /**
     * Relation pour les sous-catégories (enfants)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relation pour la catégorie parente
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // ✅ SUPPRIMEZ LA RELATION translations() - ELLE N'EST PLUS UTILISÉE
    // public function translations()
    // {
    //     return $this->hasMany(CategoryTranslation::class);
    // }
    
    // ✅ ACCESSEUR POUR LE NOM TRADUIT (simplifié)
    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->getTranslation('name', $locale);
    }
    
    public function getTranslatedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->getTranslation('description', $locale);
    }
    
    // ✅ ACCESSEUR POUR L'ADMIN (version française)
    public function getAdminNameAttribute()
    {
        return $this->getTranslation('name', 'fr');
    }

    public function getAdminDescriptionAttribute()
    {
        return $this->getTranslation('description', 'fr');
    }
    
    // ✅ SUPPRIMEZ LE SCOPE withTranslation - IL N'EST PLUS NÉCESSAIRE
    // public function scopeWithTranslation($query, $locale = null)
    // {
    //     $locale = $locale ?? app()->getLocale();
    //     return $query->with(['translations' => function($q) use ($locale) {
    //         $q->where('locale', $locale);
    //     }]);
    // }
}