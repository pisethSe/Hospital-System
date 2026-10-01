<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'sanctum'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | The legacy application had two separate login tables (his_admin and
    | his_docs). Both are exposed here so tokens can be issued for either
    | role while keeping the original tables untouched.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        // No provider here on purpose: tokens are issued to two separate
        // models (his_admin and his_docs) and Sanctum resolves each
        // tokenable by its own morph type. A fixed provider would reject
        // doctor tokens (Guard::hasValidProvider).
        'sanctum' => [
            'driver' => 'sanctum',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\Admin::class),
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'his_pwdresets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
