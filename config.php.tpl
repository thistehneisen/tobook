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
        'key' => 'bFOME-jAtMxIjYHaXtVbcQ'
    ],
    // Use to generate Hashids for iframe of Restaurant Booking
    'secret_key' => 'TYQXavN3XmjLuuEf'
];
