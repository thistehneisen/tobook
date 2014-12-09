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

    'raven' => [
        'dsn'   => 'https://fe0de91c9a1c43db821016b2183f53e8:26ca78fdb8584dcc8951c411556f884f@app.getsentry.com/34333',
        'level' => 'error'
    ],
];
