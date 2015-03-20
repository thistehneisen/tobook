<?php
$contactEmail = <<< HTML
Hello,

Visitor <strong>:name</strong> (:phone) from :email has sent you a message:
------------------
:message
------------------
:footer
HTML;

return [
    'customer_websites'     => 'Customer homepages',
    'description'           => 'Create a stunning looking responsive websites!',
    'copyright_policy'      => 'Copyright Policy',
    'copyright'             => 'Copyright',
    'newsletter'            => 'Newsletter',
    'enter_your_email'      => 'Enter Your Email',
    'submit'                => 'SUBMIT',
    'location'              => 'Location',
    'telephone'             => 'Telephone',
    'homepages'             => 'Homepages',
    'loyaltycard'           => 'Loyalty card',
    'customer_register'     => 'Customer Register',
    'cashier'               => 'Cashier',
    'description_1'         => 'Our passion is to create high quality websites which our customers can be proud of',
    'description_2'         => 'See the company\'s home page layout, and you see the shop and office space with elegance and comfort',
    'start_now'             => 'Start your free trial',
    'tagline'               => 'Everything you need for<br>easy and profitable business',
    'next_timeslot'         => 'Next available timeslots',
    'time'                  => 'Time',
    'search_tagline'        => 'What do you want to book?', // @todo
    'search_query'          => 'Enter business name or service', // @todo
    'search_place'          => 'Helsinki, FI', // @todo
    'search'        => [
        'tagline'         => 'What do you want to book?', // @todo
        'query'           => 'Enter business name or service', // @todo
        'location'        => Settings::get('default_location'), // @todo
        'about'           => 'About',
        'locations_hours' => 'Locations &amp; Hours',
        'business_hours'  => 'Business Hours',
        'buy'             => 'Buy',
        'book'            => 'Book',
        'button'          => 'Search', // @todo
        'date'            => 'Any date', // @todo
        'time'            => 'Any time', // @todo
        'results'         => '<span class="keyword">&ldquo;:keyword&rdquo;</span>, <span class="location">:location</span>, :date, :time, <span class="results">:total results</span>', // @todo
        'geo'             => [
            'info' => 'We will ask for your current location to display results that are close to you.'
        ]
    ],
    'cart' => [
        'add'         => 'Add to cart',
        'items'       => 'item|items',
        'empty'       => 'Empty',
        'empty_long'  => 'Your cart is empty.',
        'checkout'    => 'Checkout',
        'total'       => 'Total',
        'heading'     => 'Your selected products',
        'why_heading' => 'Why you should register before checking out?',
        'why_content' => 'As a registered user, you could easily manage your bookings, as well as other functionalities providede by Varaa.',
        'process'     => 'Process to payment',
        'err'         => [
            'business' => 'Currently we are not support for business account to checkout. Please login with your consumer account.',
            'zero_amount' => 'Unable to make payment since the amount of your cart is zero',
        ]
    ],
    'choose_category'     => 'Choose category', // @todo
    'how_does_it_work'    => 'How does it work?', // @todo
    'businesses'          => 'All businesses', // @todo
    'businesses_category' => 'Businesses of <strong>:category</strong>', // @todo
    'more'                => 'More', // @todo
    'less'                => 'Less', // @todo
    'companies_offers'    => 'Companies with offers', // @todo
    'categories'          => 'Categories', // @todo
    'best_offers'         => 'Best offers', // @todo
    'no_offers'           => 'There is no offer available.', // @todo
    // Business
    'business' => [
        'openning_hours' => 'Openning hours', // @todo
        'map'            => 'Map', // @todo
        'phone'          => 'Phone', // @todo
        'email'          => 'Email', // @todo
        'contact'        => [
            'index'   => 'Contact', // @todo
            'heading' => 'Contact us', // @todo
            'name'    => 'Name*', // @todo
            'email'   => 'Email*', // @todo
            'phone'   => 'Phone', // @todo
            'captcha' => 'Please enter the characters below*', // @todo
            'message' => 'Message*', // @todo
            'sent'    => 'Your message has been sent',
            'subject' => 'You got a contact message',
            'mail'    => $contactEmail,
        ]
    ]
];
