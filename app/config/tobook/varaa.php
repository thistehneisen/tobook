<?php

$head_script = "<script>
  (function (i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-46007051-2', 'auto');
  ga('send', 'pageview');
  </script>";

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
