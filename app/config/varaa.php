<?php
return [
    'dashboard_hide_inactive' => true,
    'cart' => [
        //----------------------------------------------------------------------
        //  The maximum minutes to hold items in cart
        //----------------------------------------------------------------------
        'hold_time' => 15
    ],
    //----------------------------------------------------------------------
    //  The commission rate that takes from businesses
    //  Default is 30%
    //----------------------------------------------------------------------
    'commission_rate' => 0.3,
    //--------------------------------------------------------------------------
    //  Name of folder used for upload items
    //--------------------------------------------------------------------------
    'upload_folder' => 'uploads',
    //--------------------------------------------------------------------------
    //  Default languages of the site, in this order
    //--------------------------------------------------------------------------
    'languages' => ['fi', 'en'],
    //--------------------------------------------------------------------------
    // Symbol of the currency applying to the whole system
    //--------------------------------------------------------------------------
    'currency' => '&euro;',
    //--------------------------------------------------------------------------
    // Search
    //--------------------------------------------------------------------------
    'search' => [
        'geo_distance' => '100km',
    ],
    //--------------------------------------------------------------------------
    // Special settings for HashID
    //--------------------------------------------------------------------------
    'hashid' => [
        'length'   => 8,
        'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
    ],
    //--------------------------------------------------------------------------
    // Settings for flash deals displayed in the homepage
    //--------------------------------------------------------------------------
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
    //--------------------------------------------------------------------------
    // The available modules/services that provide to customers
    //--------------------------------------------------------------------------
    'premium_modules' => [
        'appointment' => [
            'route_name' => 'as.index',
            'enable' => true,
        ],
        'restaurant' => [
            'route_name' => 'restaurant.index',
            'enable' => true,
        ],
        'loyalty' => [
            'route_name' => 'lc.consumers.index',
            'enable' => true,
        ],
        'flashdeal' => [
            'route_name' => 'fd.index',
            'enable' => true,
        ],
        'consumers' => [
            'route_name' => 'consumer-hub.index',
            'enable' => true,
        ]
    ]
];
