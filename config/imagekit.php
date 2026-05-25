<?php

/**
 * ImageKit configuration
 * Keys are stored in the settings table (DB) — not in .env
 * This config file reads from DB via Setting model at runtime
 */
return [
    'public_key'   => env('IMAGEKIT_PUBLIC_KEY', ''),
    'private_key'  => env('IMAGEKIT_PRIVATE_KEY', ''),
    'url_endpoint' => env('IMAGEKIT_URL_ENDPOINT', ''),
];
