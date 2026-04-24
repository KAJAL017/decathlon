<?php

return [
    'public_key' => env('IMAGEKIT_PUBLIC_KEY', ''),
    'private_key' => env('IMAGEKIT_PRIVATE_KEY', ''),
    'url_endpoint' => env('IMAGEKIT_URL_ENDPOINT', ''),
    
    // Image transformation presets
    'presets' => [
        'category_image' => [
            'width' => 500,
            'height' => 500,
            'quality' => 80,
            'format' => 'webp',
        ],
        'category_banner' => [
            'width' => 1920,
            'height' => 400,
            'quality' => 85,
            'format' => 'webp',
        ],
        'category_icon' => [
            'width' => 64,
            'height' => 64,
            'quality' => 90,
            'format' => 'webp',
        ],
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'quality' => 75,
            'format' => 'webp',
        ],
    ],
];
