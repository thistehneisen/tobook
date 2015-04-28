<?php

$head_script = "";

return [
    'name'             => 'Zlaté stránky',
    'languages'        => ['sv', 'en', 'fi'],
    'default_language' => 'sv',
    'default_coords'   => [59.32893, 18.06491], // Stockholm
    'meta'             => [
        'title'       => 'Zlaté stránky | Boka tid för allt',
        'description' => 'Boka tid för massage, klippning, däckbyte, m.m. Sök bland anslutna företag på din ort. Tillhandahålls av ClearSense.',
        'keywords'    => 'Zlaté stránky, ClearSense, Tidsbokning, Bokningssystem',
    ],
    'footer' => [
        'copyright' => [
            'name' => 'Clearsense',
            'url'  => 'http://www.clearsense.se',
        ],
        'social' => [
            'facebook'      => 'https://facebook.com/ClearSenseSE',
            'linkedin'      => 'https://www.linkedin.com/company/clearsense-sverige',
            'google-plus'   => 'https://plus.google.com/+lokaldelen/posts',
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
    'head_script' => $head_script,

    //--------------------------------------------------------------------------
    // Admin Setting Form
    //--------------------------------------------------------------------------
    'settings' => [
        'site_name'        => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        'head_script'      => ['type' => 'Textarea', 'default' => $head_script],
        'bottom_script'    => ['type' => 'Textarea', 'default' => ''],
        'allow_robots'     => ['type' => 'Radio', 'default' => true],
        'default_location'   => ['type' => 'Text', 'default' => 'Stockholm, SV'],
        //----------------------------------------------------------------------
        //  Country code
        //----------------------------------------------------------------------
        'phone_country_code' => ['type' => 'Text', 'default' => '46'],
        //----------------------------------------------------------------------
        //  Meta data
        //----------------------------------------------------------------------
        'meta_title'       => ['type' => 'Text', 'default' => 'Zlaté stránky | Boka tid för allt'],
        'meta_description' => ['type' => 'Text', 'default' => 'Boka tid för massage, klippning, däckbyte, m.m. Sök bland anslutna företag på din ort. Tillhandahålls av ClearSense.'],
        'meta_keywords'    => ['type' => 'Text', 'default' => 'Zlaté stránky, ClearSense, Tidsbokning, Bokningssystem'],
        //----------------------------------------------------------------------
        //  Social configuration
        //----------------------------------------------------------------------
        'social_facebook'    => ['type' => 'Text', 'default' => 'https://facebook.com/ClearSenseSE'],
        'social_linkedin'    => ['type' => 'Text', 'default' => 'https://www.linkedin.com/company/clearsense-sverige'],
        'social_google-plus' => ['type' => 'Text', 'default' => 'https://plus.google.com/+lokaldelen/posts'],
        //----------------------------------------------------------------------
        //  Footer copyright info
        //----------------------------------------------------------------------
        'copyright_name'   => ['type' => 'Text', 'default' => 'Zlaté stránky'],
        'copyright_url'    => ['type' => 'Text', 'default' => 'http://www.clearsense.se'],
        //----------------------------------------------------------------------
        // Symbol of the currency applying to the whole system
        //----------------------------------------------------------------------
        'currency'           => ['type' => 'Text', 'default' => 'SEK'],
        //----------------------------------------------------------------------
        // Globally enable shopping cart
        //----------------------------------------------------------------------
        'enable_cart'        => ['type' => 'Radio', 'default' => false],
        //----------------------------------------------------------------------
        //  The commission rate that takes from businesses
        //  Default is 30%
        //----------------------------------------------------------------------
        'commission_rate'    => ['type' => 'Text', 'default' => 0.3],
    ]
];
