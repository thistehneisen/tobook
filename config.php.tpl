<?php
// Remove this const if you don't want to bypass payment
if (!defined('BYPASS_PAYMENT')) {
    define('BYPASS_PAYMENT', true);
}

return [
    'db' => [
        'host'     => 'localhost',
        'user'     => 'root',
        'password' => '',
        'name'     => 'varaa'
    ],
    'mandrill' => [
        'key' => 'CTzo04IR5HYpn9LjCGV-7A'
    ],
    // Use to generate Hashids for iframe of Restaurant Booking
    'secret_key' => 'TYQXavN3XmjLuuEf'
];
