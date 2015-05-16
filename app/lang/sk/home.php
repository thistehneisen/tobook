<?php
$contactEmail = <<< HTML
<p>Dobrý deň,</p>

<p>Užívateľ <strong>:name</strong> (:phone, :email) Vám poslal správu:</p>

<p>------------------</p>
<p>:message</p>
<p>------------------</p>

<p>:footer</p>
HTML;

$requestMail = <<< HTML
<p>Dobrý deň,</p>

<p>Užívateľ <strong>:name</strong> (:email) Vám odporúča na vyskúšanie náš objednávkový/rezervačný systém.</p>

<p>Vyskúšajte ho už dnes! JE TO ZADARMO!</p>
HTML;

return [
    'customer_websites'     => 'Customer homepages',
    'description'           => 'Vytvorte si úžasné responzívne webstránky!',
    'copyright_policy'      => 'Copyright Policy',
    'copyright'             => 'Copyright',
    'newsletter'            => 'Newsletter',
    'enter_your_email'      => 'Vlože svoj email',
    'submit'                => 'ODOSLAŤ',
    'location'              => 'Poloha',
    'telephone'             => 'Telefón',
    'homepages'             => 'Homepages',
    'loyaltycard'           => 'Loyalty card',
    'customer_register'     => 'Customer Register',
    'cashier'               => 'Cashier',
    'description_1'         => 'Our passion is to create high quality websites which our customers can be proud of',
    'description_2'         => 'See the company\'s home page layout, and you see the shop and office space with elegance and comfort',
    'start_now'             => 'Začnite už dnes úplne ZADARMO',
    'tagline'               => 'Everything you need for<br>easy and profitable business',
    'next_timeslot'         => 'Ďalšie dostupné časy',
    'time'                  => 'Čas',
    'search_tagline'        => 'Čo si želáte rezervovať?',
    'search_query'          => 'Vlože názov Vášej prevádzky alebo služby',
    'search_place'          => 'Bratislava',
    'on_media'              => 'Featured in ',
    'search'        => [
        'tagline'         => 'Čo si želáte rezervovať?',
        'query'           => 'Vlože názov prevádzky alebo služby',
        'location'        => Settings::get('default_location'),
        'about'           => 'O nás',
        'locations_hours' => 'Locations &amp; Hours',
        'business_hours'  => 'Otváracie hodiny',
        'buy'             => 'Kupiť',
        'book'            => 'Rezervovať',
        'button'          => 'Hladať',
        'date'            => 'Ľubovolný čas',
        'time'            => 'ĽUbovolný dátum',
        'current_location' => 'Your current location', // @todo
        'results'         => '<span class="keyword">&ldquo;:keyword&rdquo;</span>, <span class="location">:location</span>, :date, :time, <span class="results">:total results</span>',
        'geo'             => [
            'info' => 'Spýtame sa Vás na Vašu polohu, aby sme Vám zobrazili výsledky najbližšie k Vám.'
        ]
    ],
    'cart' => [
        'add'         => 'Pridať do košíka',
        'items'       => 'item|items',
        'empty'       => 'Prázdny',
        'empty_long'  => 'Váš košík je prázdny.',
        'checkout'    => 'Pokladňa',
        'total'       => 'Spolu',
        'heading'     => 'Vaše vybrané produkty',
        'why_heading' => 'Prečo by ste sa mali zaregitrovať pred zaplatením?',
        'why_content' => 'Ako registrovaný užívateľ, si bude môcť jednoducho riadiť svoje objednávky a rezervácie, ako aj ďaľšie služby.',
        'process'     => 'Pokračovať k platbe',
        'err'         => [
            'business' => 'Currently we are not support for business account to checkout. Please login with your consumer account.',
            'zero_amount' => 'Platba neprebehla úspešne z dôvodu nedostatku peňazí na karte.',
        ]
    ],
    'choose_category'     => 'Vybrať kategóriu',
    'how_does_it_work'    => 'Ako to funguje?',
    'businesses'          => 'Všetky prevádzky',
    'businesses_category' => 'Prevádzky z <strong>:kategórie</strong>',
    'more'                => 'Viac',
    'less'                => 'Menej',
    'companies_offers'    => 'Spoločnosti s ponukami',
    'categories'          => 'Kategórie',
    'best_offers'         => 'Najlepšie ponuky',
    'map'                 => 'Mapa',
    'no_offers'           => 'K dispozícií nie sú žiadné ponuky.',
    'show_more'           => 'Zobraziť viac',
    // How it works?
    'hiw' => [
        'heading' => 'Ako to funguje?',
        'steps' => [
            '1'      => 'Krok 1',
            '2'      => 'Krok 2',
            '3'      => 'Krok 3',
            '1_text' => 'Vyberte službu',
            '2_text' => 'Vyberte si prevádzku',
            '3_text' => 'Rezervujte si čas',
        ],
    ],
    // Business
    'business' => [
        'about'          => 'O nás',
        'openning_hours' => 'Otváracie hodiny',
        'map'            => 'Mapa',
        'phone'          => 'Telefón',
        'email'          => 'Email',
        'online_booking' => 'Online rezervácia',
        'request'        => [
            'link'    => 'Ask for online booking',
            'info'    => 'The shop owner will be asked to use our online booking system.',
            'subject' => 'Request to use online booking system',
            'mail'    => $requestMail,
        ],
        'contact'        => [
            'index'   => 'Kontakt',
            'heading' => 'Kontaktujte nás',
            'name'    => 'Meno*',
            'email'   => 'Email*',
            'phone'   => 'Telefón',
            'captcha' => 'Prosím, vlože znaky, ktoré sú nižšie*',
            'message' => 'Správa*',
            'sent'    => 'Vaša správa bola odoslaná.',
            'subject' => 'Máte správu.',
            'mail'    => $contactEmail,
        ]
    ],
];
