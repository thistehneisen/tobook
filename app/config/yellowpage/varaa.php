<?php

$head_script = "";

return [
    'name'             => 'Zlaté stránky',
    'languages'        => ['sk', 'en'],
    'default_language' => 'sv',
    'default_coords'   => [48.13578, 17.11591], // Bratislava
    'meta'             => [
        'title'       => 'Zlaté stránky ',
        'description' => '',
        'keywords'    => 'Zlaté stránky',
    ],
    'footer' => [
        'copyright' => [
            'name' => '',
            'url'  => '',
        ],
        'social' => [
            'facebook'      => '',
            'linkedin'      => '',
            'google-plus'   => '',
            'youtube'       => '',
        ]
    ],
    'allow_robots' => true,
    'currency' => 'SEK',
    'enable_cart' => false,
    'phone_country_code' => '46',
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
        'consumers' => [
            'route_name' => 'consumer-hub.index',
            'enable' => true,
        ]
    ],
    'head_script' => $head_script,

    //--------------------------------------------------------------------------
    // Admin Setting Form
    //--------------------------------------------------------------------------
    'settings' => [
        'site_name'        => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        'head_script'      => ['type' => 'Textarea', 'default' => $head_script],
        'bottom_script'    => ['type' => 'Textarea', 'default' => ''],
        'allow_robots'     => ['type' => 'Radio', 'default' => true],
        'default_location' => ['type' => 'Text', 'default' => 'Bratislava, SK'],
        //----------------------------------------------------------------------
        //  Country code
        //----------------------------------------------------------------------
        'phone_country_code' => ['type' => 'Text', 'default' => '421'],
        //----------------------------------------------------------------------
        //  Meta data
        //----------------------------------------------------------------------
        'meta_title'       => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        'meta_description' => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        'meta_keywords'    => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        //----------------------------------------------------------------------
        //  Social configuration
        //----------------------------------------------------------------------
        'social_facebook'    => ['type' => 'Text', 'default' => ''],
        'social_linkedin'    => ['type' => 'Text', 'default' => ''],
        'social_google-plus' => ['type' => 'Text', 'default' => ''],
        //----------------------------------------------------------------------
        //  Footer copyright info
        //----------------------------------------------------------------------
        'copyright_name'   => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        'copyright_url'    => ['type' => 'Text', 'default' => ''],
        //----------------------------------------------------------------------
        // Symbol of the currency applying to the whole system
        //----------------------------------------------------------------------
        'currency'           => ['type' => 'Text', 'default' => 'EUR'],
        //----------------------------------------------------------------------
        //  The commission rate that takes from businesses
        //  Default is 30%
        //----------------------------------------------------------------------
        'commission_rate'    => ['type' => 'Text', 'default' => 0.3],
        //----------------------------------------------------------------------
        //  Deposit payment feature for booking from CP
        //  Default reate is 30%
        //----------------------------------------------------------------------
        'deposit_payment'    => ['type' => 'Radio', 'default' => false],
        'deposit_rate'       => ['type' => 'Text',  'default' => 0.3],
    ]
];
