<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class CategoryHelper
{
    /**
     * Retourne l'icône Font Awesome pour une catégorie
     */
    public static function getIcon($categoryName)
    {
        $icons = [
            'Alimentation pour chiens' => 'dog',
            'Alimentation pour chats' => 'cat',
            'Accessoires' => 'bone',
            'Soins et santé' => 'heartbeat',
            'Aquariophilie' => 'fish',
            'Vins' => 'wine-glass',
            'Produit de cuisine' => 'utensils', 
        ];
        
        // Si la catégorie existe dans le tableau, retourner son icône
        if (isset($icons[$categoryName])) {
            return $icons[$categoryName];
        }
        
        // Log pour déboguer
        Log::info('Catégorie non trouvée dans le tableau fixe: ' . $categoryName);
        
        // Sinon, générer une icône automatique
        $icon = self::getAutomaticIcon($categoryName);
        Log::info('Icône générée automatiquement: ' . $icon . ' pour ' . $categoryName);
        
        return $icon;
    }
    
    /**
     * Génère automatiquement une icône basée sur le nom de la catégorie
     */
    public static function getAutomaticIcon($categoryName)
    {
        $name = strtolower($categoryName);
        
        // Mots-clés par catégorie
        $keywords = [
            'dog' => ['chien', 'canin', 'dog', 'chiens', 'royal canin', 'purina', 'pedigree', 'croquette', 'friandise'],
            'cat' => ['chat', 'chats', 'félin', 'cat', 'whiskas', 'catsan', 'litière', 'arbre'],
            'fish' => ['poisson', 'poissons', 'aqua', 'aquarium', 'fish', 'mer', 'océan', 'eau'],
            'heartbeat' => ['santé', 'soin', 'soins', 'veto', 'vétérinaire', 'antiparasitaire', 'vermifuge', 'frontline', 'milbemax', 'médicament'],
            'bone' => ['accessoire', 'jouet', 'gamelle', 'litière', 'arbre', 'lavabo', 'panier', 'niche'],
            'wine-glass' => ['vin', 'wine', 'boisson', 'alcool', 'bière', 'champagne'],
            'paw' => ['animal', 'animaux', 'pet'],
            'shopping-bag' => ['sac', 'transport', 'bag', 'caisse'],
            'home' => ['maison', 'niche', 'panier', 'abri'],
            'gift' => ['cadeau', 'surprise', 'pack'],
            'star' => ['nouveau', 'nouveauté', 'premium', 'luxe', 'star', 'nouvelle'],
            'utensils' => ['alimentation', 'nourriture', 'pâtée', 'croquette', 'repas', 'cuisine', 'food', 'meal'],
            'tshirt' => ['vêtement', 'habit', 'pull', 'manteau'],
            'car' => ['voiture', 'transport', 'voyage'],
            'tree' => ['naturel', 'bio', 'organique', 'vert'],
            'clock' => ['temps', 'chrono', 'automatique'],
            

        ];
        
        // Chercher une correspondance avec les mots-clés
        foreach ($keywords as $icon => $words) {
            foreach ($words as $word) {
                if (str_contains($name, $word)) {
                    return $icon;
                }
            }
        }
        
        // Si aucune correspondance, utiliser la première lettre
        return self::getIconByFirstLetter($categoryName);
    }
    
    /**
     * Retourne une icône basée sur la première lettre
     */
    public static function getIconByFirstLetter($categoryName)
    {
        $firstLetter = strtoupper(substr($categoryName, 0, 1));
        
        $letterIcons = [
            'A' => 'star',
            'B' => 'book',
            'C' => 'crown',
            'D' => 'dragon',
            'E' => 'egg',
            'F' => 'flag',
            'G' => 'gift',
            'H' => 'heart',
            'I' => 'info-circle',
            'J' => 'jedi',
            'K' => 'key',
            'L' => 'leaf',
            'M' => 'moon',
            'N' => 'newspaper',
            'O' => 'otter',
            'P' => 'paw',
            'Q' => 'question',
            'R' => 'rocket',
            'S' => 'sun',
            'T' => 'tree',
            'U' => 'umbrella',
            'V' => 'volcano',
            'W' => 'water',
            'X' => 'x-ray',
            'Y' => 'yin-yang',
            'Z' => 'zzz',
        ];
        
        return $letterIcons[$firstLetter] ?? 'folder';
    }
}