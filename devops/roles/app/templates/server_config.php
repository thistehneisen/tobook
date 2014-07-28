<?php
{% if env == 'stag' %}
define('BYPASS_PAYMENT', true);
{% endif %}

return [
    'db' => [
        'host'     => 'localhost',
        'user'     => '{{ dbuser }}',
        'password' => '{{ dbpassword }}',
        'name'     => '{{ dbname }}'
    ]
];
