<?php

return [
    'enabled' => env('TEMPLATE_PREVIEWS_ENABLED', true),

    'chrome_path' => env('TEMPLATE_PREVIEWS_CHROME_PATH'),

    'directory' => 'template-previews',

    'viewport' => [
        'width' => env('TEMPLATE_PREVIEWS_WIDTH', 430),
        'height' => env('TEMPLATE_PREVIEWS_HEIGHT', 760),
    ],

    'thumbnail' => [
        'width' => env('TEMPLATE_PREVIEWS_THUMB_WIDTH', 360),
        'height' => env('TEMPLATE_PREVIEWS_THUMB_HEIGHT', 520),
    ],

    'timeout' => env('TEMPLATE_PREVIEWS_TIMEOUT', 30),
];
