<?php
{% if env == 'stag' %}
if (!defined('BYPASS_PAYMENT')) {
    define('BYPASS_PAYMENT', true);
}
{% endif %}

return [
    'db' => [
        'host'     => 'localhost',
        'user'     => '{{ dbuser }}',
        'password' => '{{ dbpassword }}',
        'name'     => '{{ dbname }}'
    ],
    'mandrill' => [
        'key' => '{{ mandrill_password }}'
    ],
    'secret_key' => '{{ secret_key }}'
];
