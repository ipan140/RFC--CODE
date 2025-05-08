<?php

return [

    'defaults' => [
        'guard' => 'web', // default untuk login form biasa
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'sanctum', // pakai sanctum untuk API
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'users', // <- ubah ke 'users' jika tidak ada model Admin terpisah
        ],

        'owner' => [
            'driver' => 'session',
            'provider' => 'users', // <- ubah ke 'users' jika tidak ada model Owner terpisah
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Hapus jika tidak punya model Admin atau Owner
        // 'admins' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\Admin::class,
        // ],

        // 'owners' => [
        //     'driver' => 'eloquent',
        //     'model' => App\Models\Owner::class,
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
