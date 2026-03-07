<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'min' => [
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    ],
    'max' => [
        'string' => 'O campo :attribute não pode ter mais que :max caracteres.',
    ],
    'unique' => 'O campo :attribute já está sendo utilizado.',
    'confirmed' => 'A confirmação do campo :attribute não coincide.',
    
    'attributes' => [
        'shipping_name' => 'nome completo',
        'shipping_address' => 'endereço completo',
        'shipping_city' => 'cidade',
        'shipping_zip' => 'CEP',
        'shipping_country' => 'país',
        'shipping_phone' => 'telefone',
        'payment_method' => 'forma de pagamento',
        'terms' => 'termos e condições',
        'email' => 'e-mail',
        'password' => 'senha',
        'name' => 'nome',
        'quantity' => 'quantidade',
        'price' => 'preço',
    ],
];