<?php

$head_script = '';

return [
    'name' => 'ToBook.lv',
    'languages' => ['lv', 'ru', 'en'],
    'default_language' => 'lv',
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

    //--------------------------------------------------------------------------
    // Admin Setting Form
    //--------------------------------------------------------------------------
    'settings' => [
        'site_name'        => ['type' => 'Text', 'default' => 'ToBook.lv'],
        'head_script'      => ['type' => 'Textarea', 'default' => ''],
        'bottom_script'    => ['type' => 'Textarea', 'default' => ''],
        'allow_robots'     => ['type' => 'Radio', 'default' => true],
        //----------------------------------------------------------------------
        //  Country code
        //----------------------------------------------------------------------
        'phone_country_code' => ['type' => 'Text', 'default' => '371'],
        //----------------------------------------------------------------------
        //  Meta data
        //----------------------------------------------------------------------
        'meta_title'       => ['type' => 'Text', 'default' => 'ToBook.lv'],
        'meta_description' => ['type' => 'Text', 'default' => ''],
        'meta_keywords'    => ['type' => 'Text', 'default' => ''],
        //----------------------------------------------------------------------
        //  Social configuration
        //----------------------------------------------------------------------
        'social_facebook'    => ['type' => 'Text', 'default' => ''],
        'social_linkedin'    => ['type' => 'Text', 'default' => ''],
        'social_google-plus' => ['type' => 'Text', 'default' => ''],
        //----------------------------------------------------------------------
        //  Footer copyright info
        //----------------------------------------------------------------------
        'copyright_name'   => ['type' => 'Text', 'default' => 'ToBook.lv'],
        'copyright_url'    => ['type' => 'Text', 'default' => 'http://www.tobook.lv'],
        //----------------------------------------------------------------------
        //  The commission rate that takes from businesses
        //  Default is 30%
        //----------------------------------------------------------------------
        'commission_rate'    => ['type' => 'Text', 'default' => 0.3],
    ]
];
