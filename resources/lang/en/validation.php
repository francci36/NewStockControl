<?php

return [
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute must not exceed :max characters.',
    ],
    'unique' => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute confirmation does not match.',
    
    'attributes' => [
        'shipping_name' => 'full name',
        'shipping_address' => 'full address',
        'shipping_city' => 'city',
        'shipping_zip' => 'postal code',
        'shipping_country' => 'country',
        'shipping_phone' => 'phone',
        'payment_method' => 'payment method',
        'terms' => 'terms and conditions',
        'email' => 'email',
        'password' => 'password',
        'name' => 'name',
        'quantity' => 'quantity',
        'price' => 'price',
    ],
];