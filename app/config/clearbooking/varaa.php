<?php
return [
    'name' => 'EnklareBokning',
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
    'allow_robots' => true,
    'currency' => 'SEK',
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
    'head_script' => "<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-59545311-1', 'auto');
  ga('send', 'pageview');
  </script>",
];
