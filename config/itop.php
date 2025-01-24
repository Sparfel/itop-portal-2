<?php

/*
   |--------------------------------------------------------------------------
   | Connexion to iTop
   |--------------------------------------------------------------------------
   | we configure more than one iTop.
   | itop1 = production
   | itop2 = Test
   | ....
   */

return [
    0 => [
        'name'     => env('ITOP_0_NAME', 'default_name'),
        'protocol' => env('ITOP_0_PROTOCOL', 'https'),
        'url'      => env('ITOP_0_URL', 'default_url'),
        'user'     => env('ITOP_0_USER', 'default_user'),
        'password' => env('ITOP_0_PASSWORD', 'default_password'),
        'debug'    => env('ITOP_0_DEBUG', 0),
    ],

    1 => [
        'name'     => env('ITOP_1_NAME', 'default_name'),
        'protocol' => env('ITOP_1_PROTOCOL', 'https'),
        'url'      => env('ITOP_1_URL', 'default_url'),
        'user'     => env('ITOP_1_USER', 'default_user'),
        'password' => env('ITOP_1_PASSWORD', 'default_password'),
        'debug'    => env('ITOP_1_DEBUG', 0),
    ],
];
