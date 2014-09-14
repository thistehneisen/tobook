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
    'flash_deal' => [
        // Only ID of root categories. Children IDs will be fetched later
        'categories' => [
            1,  // beauty_hair
            5,  // restaurant
            14,  // car
            25,  // activities
        ],
        // Number of flash deals to be shown in the frontpage
        'limit' => 8
    ],
    'premium_modules' => [
        'appointment' => [
            'url' => route('as.index'),
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
        'marketing' => [
            'url' => route('mt.consumers.index'),
            'enable' => true,
        ]
    ]
];
