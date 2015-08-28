<?php
return [
    //--------------------------------------------------------------------------
    //  All supported lanaguges
    //--------------------------------------------------------------------------
    'supported_languages' =>  ['fi', 'sv', 'sk', 'en', 'lv', 'ru'],
    //--------------------------------------------------------------------------
    //  Hide inactive module in dashboard
    //--------------------------------------------------------------------------
    'dashboard_hide_inactive' => true,
    //--------------------------------------------------------------------------
    //  Shopping cart
    //--------------------------------------------------------------------------
    'cart' => [
        //----------------------------------------------------------------------
        //  The maximum minutes to hold items in cart
        //----------------------------------------------------------------------
        'hold_time' => 15
    ],
    //--------------------------------------------------------------------------
    //  Name of folder used for upload items
    //--------------------------------------------------------------------------
    'upload_folder' => 'uploads',
    //--------------------------------------------------------------------------
    //  Default languages of the site, in this order
    //--------------------------------------------------------------------------
    'languages'        => [],
    'default_language' => 'fi',
    //--------------------------------------------------------------------------
    // Latitude and longitude of the default location to be shown in map
    //--------------------------------------------------------------------------
    'default_coords' => [60.1733244, 24.9410248], // Helsinki
    //--------------------------------------------------------------------------
    // Search
    //--------------------------------------------------------------------------
    'search'         => [
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
        'limit' => 8,
        // Show in frontpage
        'show_front_page' => true
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
            'route_name' => 'lc.offers.index',
            'enable' => true,
        ],
        'consumers' => [
            'route_name' => 'consumer-hub.index',
            'enable' => true,
        ]
    ],

    'settings' => [
        'site_name'        => ['type' => 'Text', 'default' => 'Varaa.com'],
        'head_script'      => ['type' => 'Textarea'],
        'bottom_script'    => ['type' => 'Textarea'],
        'allow_robots'     => ['type' => 'Radio', 'default' => false],
        //----------------------------------------------------------------------
        //  Country code
        //----------------------------------------------------------------------
        'phone_country_code' => ['type' => 'Text', 'default' => '358'],
        //----------------------------------------------------------------------
        //  SMS length limiter
        //----------------------------------------------------------------------
        'sms_length_limiter' => ['type' => 'Dropdown', 'values' => ['off', '70 chars', '160 chars'], 'default' => '160 chars'],
        //----------------------------------------------------------------------
        //  Meta data
        //----------------------------------------------------------------------
        'meta_title'       => ['type' => 'Text', 'default' => 'Varaa.com'],
        'meta_description' => ['type' => 'Text'],
        'meta_keywords'    => ['type' => 'Text'],
        //----------------------------------------------------------------------
        //  Social configuration
        //----------------------------------------------------------------------
        'social_facebook'    => ['type' => 'Text', 'default' => 'https://www.facebook.com/varaacom'],
        'social_linkedin'    => ['type' => 'Text', 'default' => 'https://www.linkedin.com/company/3280872'],
        'social_youtube'     => ['type' => 'Text', 'default' => 'https://www.youtube.com/user/Varaacom'],
        'social_google-plus' => ['type' => 'Text', 'default' => ''],
        //----------------------------------------------------------------------
        //  Footer copyright info
        //----------------------------------------------------------------------
        'copyright_name'   => ['type' => 'Text', 'default' => 'Varaa.com Digital Oy'],
        'copyright_url'    => ['type' => 'Text', 'default' => 'http://varaa.com'],
        //----------------------------------------------------------------------
        // Symbol of the currency applying to the whole system
        //----------------------------------------------------------------------
        'currency'           => ['type' => 'Text', 'default' => '&euro;'],
        //----------------------------------------------------------------------
        // Default location to be displayed in front page, also shown in the map
        // of business search
        //----------------------------------------------------------------------
        'default_location'   => ['type' => 'Text', 'default' => 'Helsinki, FI'],
        //----------------------------------------------------------------------
        //  The commission rate that takes from businesses
        //  Default is 30%
        //----------------------------------------------------------------------
        'commission_rate'    => ['type' => 'Text', 'default' => 0.3],
        //----------------------------------------------------------------------
        //  Footer text to be included in every contact message in business page
        //----------------------------------------------------------------------
        'footer_contact_message' => ['type' => 'Text', 'default' => 'Start using our free online booking tool now'],
        //----------------------------------------------------------------------
        //  Automatically select pay at venue option for consumer
        //----------------------------------------------------------------------
        'force_pay_at_venue' => ['type' => 'Radio', 'default' => false],
        //----------------------------------------------------------------------
        // Default paygate for the system
        //----------------------------------------------------------------------
        'default_paygate' => ['type' => 'Dropdown', 'values' => ['Disabled', 'Paysera', 'Skrill', 'Checkout'], 'default' => 'Skrill'],
        //----------------------------------------------------------------------
        // Big cities that appear in the search form in front page
        //----------------------------------------------------------------------
        'big_cities' => ['type' => 'Textarea',  'default' => ''],
        //----------------------------------------------------------------------
        // Districts
        //----------------------------------------------------------------------
        'districts' => ['type' => 'Textarea',  'default' => ''],
        //----------------------------------------------------------------------
        // Email receving messages from contact form
        //----------------------------------------------------------------------
        'contact_email' => ['type' => 'Text',  'default' => ''],
        'booking_terms' => ['type' => 'Textarea',  'default' => '', 'options' => ['rows'=> 30, 'class' => 'form-control ckeditor'] ],
    ],
];
