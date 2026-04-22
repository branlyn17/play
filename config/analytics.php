<?php

return [
    'enabled' => env('ANALYTICS_ENABLED', true),

    'ip_hash_salt' => env('ANALYTICS_IP_HASH_SALT', env('APP_KEY')),

    'location' => [
        'driver' => env('ANALYTICS_GEO_DRIVER', 'headers'),
    ],
];
