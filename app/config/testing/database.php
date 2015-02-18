<?php

// Read CodeCeption configuration
$yml = \Symfony\Component\Yaml\Yaml::parse(base_path('codeception.yml'));
$db = $yml['modules']['config']['Db'];

return [
    'fetch' => PDO::FETCH_CLASS,
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'varaa_test',
            'username'  => $db['user'],
            'password'  => $db['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => 'varaa_',
        ],
    ],
];
