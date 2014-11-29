<?php
use Monolog\Logger;

return [
    'logPath'  => storage_path().'/logs/elasticsearch.log',
    'logLevel' => Logger::INFO,
    'hosts'    => [
        '127.0.0.1:9200'
    ],
];
