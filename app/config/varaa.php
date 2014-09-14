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
            'route_name' => 'as.index',
            'enable' => true,
        ],
        'cashier' => [
            'route_name' => 'cashier.index',
            'enable' => true,
        ],
        'restaurant' => [
            'route_name' => 'restaurant.index',
            'enable' => true,
        ],
        'timeslot' => [
            'route_name' => 'timeslot.index',
            'enable' => true,
        ],
        'loyalty' => [
            'route_name' => 'loyalty.index',
            'enable' => true,
        ],
        'marketing' => [
            'route_name' => 'mt.consumers.index',
            'enable' => true,
        ]
    ]
];
