<?php

$head_script = '';

return [
    'name' => 'ToBook.lv',
    'languages' => ['lv', 'en', 'fi'],
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
    // 'settings' => [
    //     'site_name'        => ['type' => 'Text', 'default' => 'EnklareBokning'],
    //     'head_script'      => ['type' => 'Textarea', 'default' => $head_script],
    //     'bottom_script'    => ['type' => 'Textarea', 'default' => ''],
    //     'allow_robots'     => ['type' => 'Radio', 'default' => true],
    //     //----------------------------------------------------------------------
    //     //  Country code
    //     //----------------------------------------------------------------------
    //     'phone_country_code' => ['type' => 'Text', 'default' => '46'],
    //     //----------------------------------------------------------------------
    //     //  Meta data
    //     //----------------------------------------------------------------------
    //     'meta_title'       => ['type' => 'Text', 'default' => 'EnklareBokning | Boka tid för allt'],
    //     'meta_description' => ['type' => 'Text', 'default' => 'Boka tid för massage, klippning, däckbyte, m.m. Sök bland anslutna företag på din ort. Tillhandahålls av ClearSense.'],
    //     'meta_keywords'    => ['type' => 'Text', 'default' => 'EnklareBokning, ClearSense, Tidsbokning, Bokningssystem'],
    //     //----------------------------------------------------------------------
    //     //  Social configuration
    //     //----------------------------------------------------------------------
    //     'social_facebook'    => ['type' => 'Text', 'default' => 'https://facebook.com/ClearSenseSE'],
    //     'social_linkedin'    => ['type' => 'Text', 'default' => 'https://www.linkedin.com/company/clearsense-sverige'],
    //     'social_google-plus' => ['type' => 'Text', 'default' => 'https://plus.google.com/+lokaldelen/posts'],
    //     //----------------------------------------------------------------------
    //     //  Footer copyright info
    //     //----------------------------------------------------------------------
    //     'copyright_name'   => ['type' => 'Text', 'default' => 'Clearsense'],
    //     'copyright_url'    => ['type' => 'Text', 'default' => 'http://www.clearsense.se'],
    //     //----------------------------------------------------------------------
    //     // Symbol of the currency applying to the whole system
    //     //----------------------------------------------------------------------
    //     'currency'           => ['type' => 'Text', 'default' => 'SEK'],
    //     //----------------------------------------------------------------------
    //     // Globally enable shopping cart
    //     //----------------------------------------------------------------------
    //     'enable_cart'        => ['type' => 'Radio', 'default' => false],
    //     //----------------------------------------------------------------------
    //     //  The commission rate that takes from businesses
    //     //  Default is 30%
    //     //----------------------------------------------------------------------
    //     'commission_rate'    => ['type' => 'Text', 'default' => 0.3],
    // ]
];
