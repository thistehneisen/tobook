<?php

return [
    'fetch' => PDO::FETCH_CLASS,
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'varaa_test',
            'username'  => 'varaa_test',
            'password'  => 'varaa_test',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'varaa_',
        ],
    ],
];
