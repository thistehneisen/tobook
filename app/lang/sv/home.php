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
    'customer_websites'     => 'Kundhemsidor',
    'description'           => 'Create a stunning looking responsive websites!',
    'copyright_policy'      => 'Copyright Policy',
    'copyright'             => 'Copyright',
    'newsletter'            => 'Nyhetsbrev',
    'enter_your_email'      => 'Fyll i din e-postadress',
    'submit'                => 'Skicka',
    'location'              => 'Ort',
    'telephone'             => 'Telefon',
    'homepages'             => 'Hemsidor',
    'loyaltycard'           => 'Loyalty card',
    'customer_register'     => 'Kundbas',
    'cashier'               => 'Cashier',
    'description_1'         => 'Our passion is to create high quality websites which our customers can be proud of',
    'description_2'         => 'See the company\'s home page layout, and you see the shop and office space with elegance and comfort',
    'start_now'             => 'Testa gratis!',
    'tagline'               => 'Everything you need for<br>easy and profitable business',
    'next_timeslot'         => 'Nästa lediga tider',
    'time'                  => 'Tid',
    'search_tagline'        => 'Vad vill du boka?',
    'search_query'          => 'Ange företagsnamn eller tjänst',
    'search_place'          => 'Stockholm, SE',
    'search'        => [
        'tagline'         => 'Vad vill du boka?',
        'query'           => 'Ange företagsnamn eller tjänst',
        'location'        => Settings::get('default_location'), // @todo
        'about'           => 'Om tjänsten',
        'locations_hours' => 'Adress &amp; Öppettider',
        'business_hours'  => 'Öppettider',
        'buy'             => 'Köp',
        'book'            => 'Boka',
        'button'          => 'Sök',
        'date'            => 'Any date', // @todo
        'time'            => 'Any time', // @todo
        'geo'             => [
            'info' => 'Genom att ange din position kan vi presentera företag som är verksamma i ditt närområde.'
        ]
    ],
    'cart' => [
        'add'             => 'Lägg i varukorgen',
        'items'           => 'produkt|produkter',
        'empty'           => 'Tom',
        'empty_long'      => 'Varukorgen är tom.',
        'checkout'        => 'Kassa',
        'total'           => 'Totalt',
        'heading'         => 'Valda produkter',
        'why_heading'     => 'Varför ska jag registrera mig innan jag går till kassan?',
        'why_content'     => 'Som registrerad användare kan du enkelt se och hantera dina bokningar.',
        'process'         => 'Gå till kassan',
        'total_deposit'   => 'Total deposit',//@todo
        'pay_deposit'     => 'Process to deposit',//@todo
        'pay_whole'       => 'Process to payment',//@todo
        'deposit_message' => 'You can choose to pay the deposit, or the total sum of the booking in order to continue.',//@todo
        'err'         => [
            'business' => 'Den här tjänsten är tyvärr inte tillåten för företag. Vänligen logga in med ditt personkonto.',
            'zero_amount' => 'Betalning misslyckades, då det totala värdet är 0 kr.',
        ]
    ],
    'choose_category'     => 'Välj kategori',
    'how_does_it_work'    => 'How does it work?', // @todo
    'businesses'          => 'Alla företag',
    'businesses_category' => 'Resultat: <strong>:category</strong>',
    'more'                => 'Mer',
    'less'                => 'Mindre',
    'companies_offers'    => 'Companies with offers', // @todo
    'categories'          => 'Categories', // @todo
    'best_offers'         => 'Erbjudanden',
    'no_offers'           => 'Det finns inga erbjudanden',
    'map'                 => 'Map', // @todo
    'show_more'           => 'Show more', // @todo
    // How it works?
    'hiw' => [
        'heading' => 'Hur gör jag?',
        'steps' => [
            '1'      => 'Steg 1',
            '2'      => 'Steg 2',
            '3'      => 'Steg 3',
            '1_text' => 'Välj tjänst',
            '2_text' => 'Välj företag',
            '3_text' => 'Boka tid',
        ],
    ],
    // Business
    'business' => [
        'about'          => 'About', // @todo
        'openning_hours' => 'Openning hours', // @todo
        'map'            => 'Map', // @todo
        'phone'          => 'Phone', // @todo
        'email'          => 'Email', // @todo
        'online_booking' => 'Online booking', // @todo
        'request'        => [
            'link'    => 'Ask for online booking', // @todo
            'info'    => 'The shop owner will be asked to use our online booking system.', // @todo
            'subject' => 'Request to use online booking system', // @todo
            'mail'    => $requestMail, // @todo
        ],
        'contact'        => [
            'index'   => 'Contact', // @todo
            'heading' => 'Contact us', // @todo
            'name'    => 'Name*', // @todo
            'email'   => 'Email*', // @todo
            'phone'   => 'Phone', // @todo
            'captcha' => 'Please enter the characters below*', // @todo
            'message' => 'Message*', // @todo
            'sent'    => 'Your message has been sent', // @todo
            'subject' => 'You got a contact message', // @todo
            'mail'    => $contactEmail, // @todo
        ]
    ],
];
