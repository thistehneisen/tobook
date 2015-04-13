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
    'customer_websites'     => 'Pagina de start a clientului',
    'description'           => 'Creati site-uri uimitoare adaptate oricarui dispozitiv mobil!',
    'copyright_policy'      => 'Politica de confidentialitate',
    'copyright'             => 'Drepturi de autor',
    'newsletter'            => 'Newsletter',
    'enter_your_email'      => 'Introduceti adresa de email',
    'submit'                => 'TRIMITE',
    'location'              => 'Locatie',
    'telephone'             => 'Telefon',
    'homepages'             => 'Pagini de start',
    'loyaltycard'           => 'Card de fidelitate',
    'customer_register'     => 'Inregistrare Client',
    'cashier'               => 'Casier',
    'description_1'         => 'Pasiunea noastra este de a crea site-uri de inalta calitate, cu care clientii nostri se pot mandri',
    'description_2'         => 'Consultati aspectul paginii de start a companie si puteti vizualiza magazinul si biroul cu eleganta si confort',
    'start_now'             => 'Incercati gratuit',
    'tagline'               => 'Tot ce ai nevoie pentru<br>o afacere usoara si profitabila.',
    'next_timeslot'         => 'Urmatoarele intervale de timp disponibile',
    'time'                  => 'Timp',
    'search_tagline'        => 'What do you want to book?', // @todo
    'search_query'          => 'Enter business name or service', // @todo
    'search_place'          => 'Helsinki, FI', // @todo
    'search'        => [
        'tagline'         => 'What do you want to book?', // @todo
        'query'           => 'Enter business name or service', // @todo
        'location'        => Settings::get('default_location'), // @todo
        'about'           => 'Despre',
        'locations_hours' => 'Locatii &amp; Ore',
        'business_hours'  => 'Program',
        'buy'             => 'Cumpara',
        'book'            => 'Rezerva',
        'button'          => 'Search', // @todo
        'date'            => 'Any date', // @todo
        'time'            => 'Any time', // @todo
        'geo'             => [
            'info' => 'Va vom cere locatia curenta pentru a afisa rezultate care sunt aproape de dvs.'
        ]
    ],
    'cart' => [
        'add'         => 'Adauga in cos',
        'items'       => 'articol|articole',
        'empty'       => 'Gol',
        'empty_long'  => 'Cosul dvs. este gol.',
        'checkout'    => 'Validare',
        'total'       => 'Total',
        'heading'     => 'Produsele dvs. selectate',
        'why_heading' => 'De ce ar trebui sa va inregistrati inainte de a valida?',
        'why_content' => 'Ca un utilizator inregistrat, puteti gestiona cu usurinta rezervarile, precum si alte functionalitati oferite de Varaa.',
        'process'     => 'Continuati catre plata',
        'err'         => [
            'business' => 'Momentan nu acceptam validarea cu un cont de afaceri. Va rugam sa va conectati cu contul dvs. de client.',
            'zero_amount' => 'Plata nu poate fi validata deoarece contul dvs. este gol.',
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
    'map'                 => 'Map', // @todo
    'show_more'           => 'Show more', // @todo
    // How it works?
    'hiw' => [
        'heading' => 'How it works?', // @todo
        'steps' => [
            '1'      => 'Step 1', // @todo
            '2'      => 'Step 2', // @todo
            '3'      => 'Step 3', // @todo
            '1_text' => 'Select a service', // @todo
            '2_text' => 'Select a business', // @todo
            '3_text' => 'Book a time', // @todo
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
