<?php

$head_script = "<script>
  (function (i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function () {
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-59545311-1', 'auto');
  ga('send', 'pageview');
  </script>";

return [
    'name'             => 'EnklareBokning',
    'languages'        => ['sv', 'en'],
    'default_language' => 'sv',
    'default_coords'   => [59.32893, 18.06491], // Stockholm
    'meta'             => [
        'title'       => 'EnklareBokning | Boka tid för allt',
        'description' => 'Boka tid för massage, klippning, däckbyte, m.m. Sök bland anslutna företag på din ort. Tillhandahålls av ClearSense.',
        'keywords'    => 'EnklareBokning, ClearSense, Tidsbokning, Bokningssystem',
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
        'site_name'        => ['type' => 'Text', 'default' => 'EnklareBokning'],
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
        'meta_title'       => ['type' => 'Text', 'default' => 'EnklareBokning | Boka tid för allt'],
        'meta_description' => ['type' => 'Text', 'default' => 'Boka tid för massage, klippning, däckbyte, m.m. Sök bland anslutna företag på din ort. Tillhandahålls av ClearSense.'],
        'meta_keywords'    => ['type' => 'Text', 'default' => 'EnklareBokning, ClearSense, Tidsbokning, Bokningssystem'],
        //----------------------------------------------------------------------
        //  Social configuration
        //----------------------------------------------------------------------
        'social_facebook'    => ['type' => 'Text', 'default' => 'https://facebook.com/ClearSenseSE'],
        'social_linkedin'    => ['type' => 'Text', 'default' => 'https://www.linkedin.com/company/clearsense-sverige'],
        'social_google-plus' => ['type' => 'Text', 'default' => 'https://plus.google.com/+lokaldelen/posts'],
        //----------------------------------------------------------------------
        //  Footer copyright info
        //----------------------------------------------------------------------
        'copyright_name'   => ['type' => 'Text', 'default' => 'Clearsense'],
        'copyright_url'    => ['type' => 'Text', 'default' => 'http://www.clearsense.se'],
        //----------------------------------------------------------------------
        // Symbol of the currency applying to the whole system
        //----------------------------------------------------------------------
        'currency'           => ['type' => 'Text', 'default' => 'SEK'],
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
