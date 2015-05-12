<?php
$contactEmail = <<< HTML
<p>Hello,</p>

<p>Visitor <strong>:name</strong> (:phone) from :email has sent you a message:</p>

<p>------------------</p>
<p>:message</p>
<p>------------------</p>

<p>:footer</p>
HTML;

$requestMail = <<< HTML
<p>Hello,</p>

<p>Visitor <strong>:name</strong> from :email requested you to start using our onling booking solution.</p>

<p>Start using it now! It's FREE!</p>
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
    'search_tagline'        => 'What do you want to book?',
    'search_query'          => 'Enter business name or service',
    'search_place'          => 'Helsinki, FI',
    'on_media'              => 'Featured in ',
    'search'        => [
        'tagline'         => 'What do you want to book?',
        'query'           => 'Enter business name or service',
        'location'        => Settings::get('default_location'),
        'about'           => 'About',
        'locations_hours' => 'Locations &amp; Hours',
        'business_hours'  => 'Business Hours',
        'buy'             => 'Buy',
        'book'            => 'Book',
        'button'          => 'Search',
        'date'            => 'Any date',
        'time'            => 'Any time',
        'results'         => '<span class="keyword">&ldquo;:keyword&rdquo;</span>, <span class="location">:location</span>, :date, :time, <span class="results">:total results</span>',
        'geo'             => [
            'info' => 'We will ask for your current location to display results that are close to you.'
        ]
    ],
    'cart' => [
        'add'             => 'Add to cart',
        'items'           => 'item|items',
        'empty'           => 'Empty',
        'empty_long'      => 'Your cart is empty.',
        'checkout'        => 'Checkout',
        'total'           => 'Total',
        'heading'         => 'Your selected products',
        'why_heading'     => 'Why you should register before checking out?',
        'why_content'     => 'As a registered user, you could easily manage your bookings, as well as other functionalities providede by Varaa.',
        'process'         => 'Process to payment',
        'total_deposit'   => 'Total deposit',//@todo
        'pay_deposit'     => 'Process to deposit',//@todo
        'pay_whole'       => 'Process to payment',//@todo
        'deposit_message' => 'You can choose to pay the deposit, or the total sum of the booking in order to continue.',//@todo
        'err'         => [
            'business' => 'Currently we are not support for business account to checkout. Please login with your consumer account.',
            'zero_amount' => 'Unable to make payment since the amount of your cart is zero',
        ]
    ],
    'choose_category'     => 'Choose category',
    'how_does_it_work'    => 'How does it work?',
    'businesses'          => 'All businesses',
    'businesses_category' => 'Businesses of <strong>:category</strong>',
    'more'                => 'More',
    'less'                => 'Less',
    'companies_offers'    => 'Companies with offers',
    'categories'          => 'Categories',
    'best_offers'         => 'Best offers',
    'map'                 => 'Map',
    'no_offers'           => 'There is no offer available.',
    'show_more'           => 'Show more',
    // How it works?
    'hiw' => [
        'heading' => 'How it works?',
        'steps' => [
            '1'      => 'Step 1',
            '2'      => 'Step 2',
            '3'      => 'Step 3',
            '1_text' => 'Select a service',
            '2_text' => 'Select a business',
            '3_text' => 'Book a time',
        ],
    ],
    // Business
    'business' => [
        'about'          => 'About',
        'openning_hours' => 'Openning hours',
        'map'            => 'Map',
        'phone'          => 'Phone',
        'email'          => 'Email',
        'online_booking' => 'Online booking',
        'request'        => [
            'link'    => 'Ask for online booking',
            'info'    => 'The shop owner will be asked to use our online booking system.',
            'subject' => 'Request to use online booking system',
            'mail'    => $requestMail,
        ],
        'contact'        => [
            'index'   => 'Contact',
            'heading' => 'Contact us',
            'name'    => 'Name*',
            'email'   => 'Email*',
            'phone'   => 'Phone',
            'captcha' => 'Please enter the characters below*',
            'message' => 'Message*',
            'sent'    => 'Your message has been sent',
            'subject' => 'You got a contact message',
            'mail'    => $contactEmail,
        ]
    ],
];
