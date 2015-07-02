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
        'mysql_backup' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => $_ENV['DB_NAME'] . '_backup',
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'varaa_',
        ],
    ],
];
