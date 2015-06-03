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
        'date'            => 'Alla dagar',
        'time'            => 'Alla tider',
        'current_location' => 'Min nuvarande position',
        'validation'      => [
            'q'        => 'Sök efter en tjänst',
            'location' => 'Ange plats eller område',
        ],
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
    'how_does_it_work'    => 'Hur gör jag?',
    'businesses'          => 'Alla företag',
    'businesses_category' => 'Resultat: <strong>:category</strong>',
    'more'                => 'Mer',
    'less'                => 'Mindre',
    'companies_offers'    => 'Företag med erbjudanden',
    'categories'          => 'Kategorier',
    'best_offers'         => 'Erbjudanden',
    'no_offers'           => 'Det finns inga erbjudanden',
    'map'                 => 'Karta',
    'show_more'           => 'Visa mer',
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
        'about'          => 'Om oss',
        'openning_hours' => 'Öppettider',
        'map'            => 'Här finns vi',
        'phone'          => 'Tel',
        'email'          => 'E-post',
        'online_booking' => 'Boka online',
        'request'        => [
            'link'    => 'Begäran om online bokning',
            'info'    => 'The shop owner will be asked to use our online booking system.', // @todo
            'subject' => 'Begäran om att använda online-bokningssystem',
            'mail'    => $requestMail, // @todo
        ],
        'contact'        => [
            'index'   => 'Kontakt',
            'heading' => 'Kontakt oss',
            'name'    => 'Namn*',
            'email'   => 'Email*',
            'phone'   => 'Tel',
            'captcha' => 'Please enter the characters below*', // @todo
            'message' => 'Meddelande*',
            'sent'    => 'Ditt meddelande har skickats!',
            'subject' => 'Du har ett meddelande',
            'mail'    => $contactEmail, // @todo
        ]
    ],
];
