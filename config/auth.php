<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'profiles',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'profiles',
        ],
    ],

    'providers' => [
        'profiles' => [
            'driver' => 'eloquent',
            'model' => App\Models\Profile::class,
        ],
    ],

    'passwords' => [
        'profiles' => [
            'provider' => 'profiles',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];