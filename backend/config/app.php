<?php

return [

    'name' => env('APP_NAME', 'Hospital System'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => env('APP_TIMEZONE', 'UTC'),

    'locale' => env('APP_LOCALE', 'en'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(explode(',', (string) env('APP_PREVIOUS_KEYS', ''))),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    // Force https:// on generated URLs when the app runs behind a
    // TLS-terminating proxy or load balancer (APP_FORCE_HTTPS=true).
    'force_https' => env('APP_FORCE_HTTPS', false),

    // Minimum length for new/changed passwords. The legacy sha1(md5())
    // hashing of stored passwords is untouched.
    'password_min_length' => 8,

];
