<?php

return [
    // ... autres services ...

    'vendor' => [
        'webhook_secret' => env('VENDOR_WEBHOOK_SECRET', 'your-secret-key-here'),
    ],

    'carriers' => [
        'colissimo' => [
            'api_url' => env('COLISSIMO_API_URL', 'https://api.laposte.fr/ssu/v1/'),
            'api_key' => env('COLISSIMO_API_KEY'),
            'webhook_secret' => env('COLISSIMO_WEBHOOK_SECRET'),
        ],
        'chronopost' => [
            'api_url' => env('CHRONOPOST_API_URL', 'https://api.chronopost.fr/tracking/'),
            'api_key' => env('CHRONOPOST_API_KEY'),
            'webhook_secret' => env('CHRONOPOST_WEBHOOK_SECRET'),
        ],
    ],
];