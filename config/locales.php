<?php

return [
    'default' => env('APP_LOCALE', 'es'),

    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),

    'supported' => [
        'es' => [
            'code' => 'es',
            'label' => 'ES',
            'name' => 'Spanish',
            'native_name' => "Espa\u{00F1}ol",
            'flag' => "\u{1F1EA}\u{1F1F8}",
            'direction' => 'ltr',
            'enabled' => true,
            'fallback' => 'en',
            'hreflang' => 'es',
        ],
        'en' => [
            'code' => 'en',
            'label' => 'EN',
            'name' => 'English',
            'native_name' => 'English',
            'flag' => "\u{1F1FA}\u{1F1F8}",
            'direction' => 'ltr',
            'enabled' => true,
            'fallback' => 'en',
            'hreflang' => 'en',
        ],
        'ar' => [
            'code' => 'ar',
            'label' => 'AR',
            'name' => 'Arabic',
            'native_name' => "\u{0627}\u{0644}\u{0639}\u{0631}\u{0628}\u{064A}\u{0629}",
            'flag' => "\u{1F1F8}\u{1F1E6}",
            'direction' => 'rtl',
            'enabled' => true,
            'fallback' => 'en',
            'hreflang' => 'ar',
        ],
    ],
];
