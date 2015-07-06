<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => '',
        'secret' => '',
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'stripe' => [
        'model'  => 'User',
        'secret' => '',
    ],

    'oneapi' => [
        'username' => 'varaa6',
        'password' => 'varaa12'
    ],

    'skrill' => [
        'email'    => 'mikael.dacosta@varaa.com',
        'password' => 'halloween22',
        'secret'   => 'teqaphus6p'
    ],
    'paysera' => [
        'id'       => 63548,
        'password' => 'e6e9d37a0f6a79c25564cade197a8e3c',
    ],
    'checkout' => [
        'id' => '375917',
        'secret' => 'SAIPPUAKAUPPIAS'
    ],
];
