<?php
return [
    'languages' => ['en', 'ru'],
    'default_language' => 'fi',
    'commission_style' => '',
    'tutorial_video'   => 'https://www.youtube.com/watch?v=YNVCHyxY-kg',
    //--------------------------------------------------------------------------
    // Admin Setting Form
    //--------------------------------------------------------------------------
    'settings' => [
        'site_name'        => ['type' => 'Text', 'default' => 'Varaa AWS'],
        'head_script'      => ['type' => 'Textarea', 'default' => ''],
        'bottom_script'    => ['type' => 'Textarea', 'default' => ''],
        'allow_robots'     => ['type' => 'Radio', 'default' => true],
        'default_location'   => ['type' => 'Text', 'default' => 'Helsinki, FI'],
        //----------------------------------------------------------------------
        //  Country code
        //----------------------------------------------------------------------
        'phone_country_code' => ['type' => 'Text', 'default' => '371'],
        //----------------------------------------------------------------------
        //  Meta data
        //----------------------------------------------------------------------
        'meta_title'       => ['type' => 'Text', 'default' => 'Varaa AWS'],
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
        'copyright_name'   => ['type' => 'Text', 'default' => 'Varaa AWS'],
        'copyright_url'    => ['type' => 'Text', 'default' => 'http://www.varaa.com'],
        //----------------------------------------------------------------------
        //  The commission rate that takes from businesses
        //  Default is 30%
        //----------------------------------------------------------------------
        'commission_rate'    => ['type' => 'Text',  'default' => 0.3],
        //----------------------------------------------------------------------
        //  Deposit payment feature for booking from CP
        //  Default reate is 30%
        //----------------------------------------------------------------------
        'deposit_payment'                => ['type' => 'Radio', 'default' => false],
        'deposit_rate'                   => ['type' => 'Text',  'default' => 0.3],
    ]
];
