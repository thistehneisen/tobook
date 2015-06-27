<?php

return [
    'DB_USERNAME'       => '{{ dbuser }}',
    'DB_PASSWORD'       => '{{ dbpassword }}',
    'DB_NAME'           => '{{ dbname }}',
    'SECRET_KEY'        => '{{ secret_key }}',
    'MANDRILL_PASSWORD' => '{{ mandrill_password }}',
    'ROUTES_PREFIX'     => '{{ routes_prefix | default("") }}',
];
