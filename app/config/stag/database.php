<?php

return [
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => $_ENV['DB_NAME'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'varaa_',
        ],
        'old' => [
            'driver'    => 'mysql',
            'host'      => '213.136.71.213',
            'database'  => 'varaa_wpdb',
            'username'  => 'varaa_userdb',
            'password'  => 'E$7iS6Km)TNM',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'wp_',
        ]
    ],
];
