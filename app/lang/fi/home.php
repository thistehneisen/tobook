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
    'customer_websites'     => 'Asiakkaidemme kotisivut',
    'description'           => 'Tyylikkäät ensiostokseen kannustavat kotisivut!',
    'copyright_policy'      => 'Yksityisyydensuoja',
    'copyright'             => 'Tekijänoikeus',
    'newsletter'            => 'Uutiskirje',
    'enter_your_email'      => 'Kirjoita sähköpostisi',
    'submit'                => 'TILAA',
    'location'              => 'Sijaitsemme',
    'telephone'             => 'Puhelin',
    'homepages'             => 'Kotisivut',
    'loyaltycard'           => 'Kantiskortti',
    'customer_register'     => 'Asiakasrekisteri',
    'cashier'               => 'Kassa',
    'description_1'         => 'Meidän intohimomme on luoda laadukkaita kotisivuja joista asiakkaamme voivat olla ylpeitä',
    'description_2'         => 'Panosta yrityksen kotisivun ulkoasuun, niin kuin panostat myymälän ja liiketilan tyylikkyyteen ja viihtyvyyteen',
    'start_now'             => 'Aloita ilmainen kokeilu',
    'tagline'               => 'Kaikki mitä tarvitset helppoon<br>ja kannattavaan liiketoimintaan',
    'next_timeslot'         => 'Seuraavat vapaat ajat',
    'time'                  => 'Aika',
    'search_tagline'        => 'Mitä haluat varata?',
    'search_query'          => 'Syötä yrityksen tai palvelun nimi',
    'search_place'          => 'Helsinki, FI',
    'search'        => [
        'tagline'         => 'Mitä haluat varata?',
        'query'           => 'Syötä yrityksen tai palvelun nimi',
        'location'        => Settings::get('default_location'),
        'about'           => 'Esittely',
        'locations_hours' => 'Sijainti &amp; Aukioloajat',
        'business_hours'  => 'Aukioloajat',
        'buy'             => 'Osta',
        'book'            => 'Varaa',
        'button'          => 'Hae',
        'date'            => 'Valitse päivä',
        'time'            => 'Valitse aika',
        'geo'             => [
            'info' => 'We will ask for your current location to display results that are close to you.'
        ]
    ],
    'cart' => [
        'add'         => 'Varaa',
        'items'       => 'tuote|tuotteet',
        'empty'       => 'Tyhjä',
        'empty_long'  => 'Sinulla ei ole varauksia.',
        'checkout'    => 'Maksa',
        'total'       => 'Yhteensä',
        'heading'     => 'Valitut tuotteet',
        'why_heading' => 'Miksi rekisteröityisin?',
        'why_content' => 'Rekisteröityessäsi palvelu käyttäjäksi voit helposti seurata varauksiasi ja hyödyntää muita varaa.com:in tarjoamia etuja.',
        'process'     => 'Maksua prosessoidaan',
        'err'         => [
            'business' => 'Valitettavasti emme tue maksuja yrityskäyttäjätunnuksen kautta. Ole ystävällinen ja rekisteröidy palveluun kuluttajana.',
            'zero_amount' => 'Maksua ei voida suorittaa',
        ]
    ],
    'choose_category'     => 'Valitse kategoria',
    'how_does_it_work'    => 'Kuinka se toimii?',
    'businesses'          => 'Kaikki yritykset',
    'businesses_category' => 'Yrityksen joiden <strong>:kategoria</strong>',
    'more'                => 'Lisää',
    'less'                => 'Vähemmän',
    'companies_offers'    => 'Vain yritykset joilla on tarjouksia',
    'categories'          => 'Kategoriat',
    'best_offers'         => 'Parhaat tarjoukset',
    'no_offers'           => 'Tarjouksia ei ole saatavilla.',
    // Business
    'business' => [
        'openning_hours' => 'Openning hours', // @todo
        'map'            => 'Map', // @todo
        'phone'          => 'Phone', // @todo
        'email'          => 'Email', // @todo
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
