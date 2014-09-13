<?php
return [
    // Name of folder used for upload items
    'dashboard_hide_inactive' => true,
    'upload_folder'           => 'uploads',
    'languages'               => ['fi', 'en'],
    'oneapi' => [
        'username' => 'varaa6',
        'password' => 'varaa12'
    ],
    'hashid' => [
        'length'   => 8,
        'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ],
    'premium_modules' => [
        'appointment' => [
            'url' => route('appointment.index'),
            'enable' => true,
        ],
        'cashier' => [
            'url' => route('cashier.index'),
            'enable' => true,
        ],
        'restaurant' => [
            'url' => route('restaurant.index'),
            'enable' => true,
        ],
        'timeslot' => [
            'url' => route('timeslot.index'),
            'enable' => true,
        ],
        'loyalty' => [
            'url' => route('loyalty.index'),
            'enable' => true,
        ],
    ]
];
