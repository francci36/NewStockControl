<?php
// app/Helpers/CategoryHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class CategoryHelper
{
    /**
     * Retourne l'icône Font Awesome pour une catégorie
     */
    public static function getIcon($category)
    {
        // Récupérer le nom selon le type de paramètre
        if (is_object($category)) {
            $name = strtolower($category->translated_name);
        } elseif (is_string($category)) {
            $name = strtolower($category);
        } elseif (is_array($category)) {
            $name = strtolower($category['fr'] ?? $category['en'] ?? '');
        } else {
            return 'tag';
        }

        // Mapping des catégories principales de la boutique
        $icons = [
            // 🐶 ANIMAUX
            'alimentation pour chiens' => 'dog',
            'alimentation chiens' => 'dog',
            'alimentation pour chats' => 'cat',
            'alimentation chats' => 'cat',
            
            // 🦴 ACCESSOIRES
            'accessoires' => 'bone',
            
            // 💊 SOINS ET SANTÉ
            'soins et santé' => 'heartbeat',
            'soins' => 'heartbeat',
            'santé' => 'heartbeat',
            
            // 🐟 AQUARIOPHILIE
            'aquariophilie' => 'fish',
            
            // 🍷 VINS (catégorie principale)
            'vins' => 'wine-glass',
            
            // 🍳 CUISINE
            'produit de cuisine' => 'utensils',
            'cuisine' => 'utensils',
            
            // 🌱 JARDIN
            'jardin' => 'seedling',
        ];

        // Vérification exacte d'abord
        if (isset($icons[$name])) {
            return $icons[$name];
        }

        // Sinon, chercher une correspondance partielle
        foreach ($icons as $key => $icon) {
            if (str_contains($name, $key)) {
                return $icon;
            }
        }

        // Log pour déboguer
        Log::info('Catégorie non reconnue: ' . $name);

        return 'tag'; // Icône par défaut
    }
}