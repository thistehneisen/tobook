<?php
$contactEmail = <<< HTML
<p>Hei,</p>

<p>Vierailija <strong>:name</strong> (:phone) sähköposti: :email lähetti sinulle viestin:</p>

<p>------------------</p>
<p>:message</p>
<p>------------------</p>

<p>:footer</p>
HTML;

$requestMail = <<< HTML
<p>Hei,</p>

<p>Vieraijila <strong>:name</strong> sähköposti: :email toivoi että ottaisit sähköisen ajanvarauskalenterin käyttöön.</p>

<p>Ota nyt sähköinen kalenteri käyttöön MAKSUTTA!</p>
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
    'on_media'              => 'Mediassa',
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
        'current_location' => 'Sijaintisi',
        'validation'      => [
            'q'        => 'Etsi palveluita',
            'location' => 'Valitse sijainti',
        ],
        'geo'             => [
            'info' => 'Kysymme sijaintiasi jotta voimme järjestää hakutulokset sijaintisi perusteella.'
        ]
    ],
    'cart' => [
        'add'             => 'Varaa',
        'items'           => 'tuote|tuotteet',
        'empty'           => 'Tyhjä',
        'empty_long'      => 'Sinulla ei ole varauksia.',
        'checkout'        => 'Valitse maksutapa',
        'total'           => 'Yhteensä',
        'heading'         => 'Valitut tuotteet',
        'why_heading'     => 'Miksi rekisteröityisin?',
        'why_content'     => 'Rekisteröityessäsi palvelu käyttäjäksi voit helposti seurata varauksiasi ja hyödyntää muita varaa.com:in tarjoamia etuja.',
        'process'         => 'Maksua prosessoidaan',
        'total_deposit'   => 'Varausmaksu',
        'pay_deposit'     => 'Maksa varausmaksu',
        'pay_full'        => 'Maksa etukäteen',
        'pay_venue'       => 'Maksa käynnin yhteydessä',
        'terms'           => 'Jos valitset maksavasi varauksen käyntisi yhteydessä, otathan huomioon että mahdollinen varauksen peruuttaminen täytyy tehdä hyvissä ajoin yrittäjän itsensä määrittämien peruutusehtojen mukaisesti.<br>Voit menettää oikeutesi (tilapäisesti tai pysyvästi) tehdä varauksia Varaa.comin kautta, mikäli jätät ilmaantumatta paikalle sovituun aikaan.<br>Kiitos ymmärryksestä!',
        'checkout_message'=> 'Voit maksaa varauksesi etukäteen netissä, tai vasta käyntisi yhteydessä.',
        'err'         => [
            'business' => 'Valitettavasti emme tue maksuja yrityskäyttäjätunnuksen kautta. Kirjaudu ulos yrityskäyttäjätililtäsi.',
            'zero_amount' => 'Maksua ei voida suorittaa',
        ]
    ],
    'choose_category'     => 'Valitse kategoria',
    'how_does_it_work'    => 'Kuinka se toimii?',
    'businesses'          => 'Kaikki yritykset',
    'businesses_category' => 'Yrityksen joiden <strong>:category</strong>',
    'more'                => 'Lisää',
    'less'                => 'Vähemmän',
    'companies_offers'    => 'Vain yritykset joilla on tarjouksia',
    'categories'          => 'Kategoriat',
    'best_offers'         => 'Parhaat tarjoukset',
    'no_offers'           => 'Tarjouksia ei ole saatavilla.',
    'map'                 => 'Kartta',
    'show_more'           => 'Näytä lisää',
    // How it works?
    'hiw' => [
        'heading' => 'Kuinka Varaa.com toimii?',
        'steps' => [
            '1'      => 'Askel 1',
            '2'      => 'Askel 2',
            '3'      => 'Askel 3',
            '1_text' => 'Valitse palvelu',
            '2_text' => 'Valitse yritys',
            '3_text' => 'Varaa aika',
        ],
    ],
    // Business
    'business' => [
        'about'          => 'Yrityksestä',
        'openning_hours' => 'Aukioloajat',
        'map'            => 'Osoite',
        'phone'          => 'Puh.',
        'email'          => 'Sähköposti',
        'online_booking' => 'Varaa aika',
        'request'        => [
            'link'    => 'Toivo sähköistä kalenteria',
            'info'    => 'Yrittäjälle lähtee pyyntö ottaa ajanvarausjärjestelmä käyttöön',
            'subject' => 'Pyyntö avata sähköinen kalenteri',
            'mail'    => $requestMail, // @todo
        ],
        'contact'        => [
            'index'   => 'Yhteystiedot',
            'heading' => 'Ota yhteyttä',
            'name'    => 'Nimi*',
            'email'   => 'Sähköposti*',
            'phone'   => 'Puh.',
            'captcha' => 'Syötä seuraavat kirjaimet*',
            'message' => 'Viesti*',
            'sent'    => 'Viestisi on lähetetty.',
            'subject' => 'Olet saanut viestin Varaa.comin kautta!',
            'mail'    => $contactEmail, // @todo
        ]
    ],
];
