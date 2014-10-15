<?php

use Monolog\Logger;

return [
    'hosts' => [
        'localhost:9200'
     ],
    'logPath' => 'path/to/your/elasticsearch/log',
    'logLevel' => Logger::INFO
];
