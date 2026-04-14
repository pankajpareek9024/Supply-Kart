<?php

use App\Models\User;

return [

    'defaults' => [
        'guard'     => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // Admin guard
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],

        // Customer guard for B2B wholesale customers
        'customer' => [
            'driver'   => 'session',
            'provider' => 'customers',
        ],

        // Delivery Boy guard
        'delivery_boy' => [
            'driver'   => 'session',
            'provider' => 'delivery_boys',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => env('AUTH_MODEL', User::class),
        ],

        // Admin provider
        'admins' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],

        // Customer provider — maps to the 'customers' table
        'customers' => [
            'driver' => 'eloquent',
            'model'  => App\Models\customer::class,
        ],

        // Delivery Boy provider
        'delivery_boys' => [
            'driver' => 'eloquent',
            'model'  => App\Models\DeliveryBoy::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
