<?php

return [
    'required' => 'Le champ :attribute est obligatoire.',
    'required_if' => 'Le champ :attribute est obligatoire quand :other est :value.',
    'email' => 'Le champ :attribute doit être une adresse email valide.',
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],
    'max' => [
        'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    ],
    'unique' => 'Le champ :attribute est déjà utilisé.',
    'confirmed' => 'La confirmation du champ :attribute ne correspond pas.',
    
    'attributes' => [
        'shipping_name' => 'nom complet',
        'shipping_address' => 'adresse complète',
        'shipping_city' => 'ville',
        'shipping_zip' => 'code postal',
        'shipping_country' => 'pays',
        'shipping_phone' => 'téléphone',
        'payment_method' => 'mode de paiement',
        'terms' => 'conditions générales',
        'email' => 'e-mail',
        'password' => 'mot de passe',
        'name' => 'nom',
        'quantity' => 'quantité',
        'price' => 'prix',
    ],
];