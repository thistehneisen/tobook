<?php
return [
    'name' => 'ClearBooking',
    'languages' => ['sv', 'en', 'fi'],
    'footer' => [
        'copyright' => [
            'name' => 'Clearsense',
            'url' => 'http://www.clearsense.se',
        ],
        'social' => [
            'facebook'      => 'https://facebook.com/ClearSenseSE',
            'linkedin'      => 'https://www.linkedin.com/company/clearsense-sverige',
            'google-plus'   => 'https://plus.google.com/+lokaldelen/posts',
            'youtube'       => '',
        ]
    ],
    'premium_modules' => [
        'appointment' => [
            'route_name' => 'as.index',
            'enable' => true,
        ],
        'restaurant' => [
            'route_name' => 'restaurant.index',
            'enable' => false,
        ],
        'loyalty' => [
            'route_name' => 'lc.offers.index',
            'enable' => false,
        ],
        'flashdeal' => [
            'route_name' => 'fd.index',
            'enable' => true,
        ],
        'consumers' => [
            'route_name' => 'consumer-hub.index',
            'enable' => true,
        ]
    ],
    'flash_deal' => [
        'show_front_page' => false
    ],
];
