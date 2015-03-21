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
    'customer_websites'     => 'Klientu lapas',
    'description'           => 'Izveidojiet efektîvu un skaistu lapu!',
    'copyright_policy'      => 'Autortiesîbu politika',
    'copyright'             => 'Autortiesîbas',
    'newsletter'            => 'Newsletter',
    'enter_your_email'      => 'Jûsu e-pasta adrese',
    'submit'                => 'Iesniegt',
    'location'              => 'Atraðanâs vieta',
    'telephone'             => 'Tâlrunis',
    'homepages'             => 'Mâjas lapa',
    'loyaltycard'           => 'Klienta karte',
    'customer_register'     => 'Klienta reìistrâcija',
    'cashier'               => 'Kase',
    'description_1'         => 'Mûsu mçríis - kvalitatîvas mâjas lapas, ar kurâm mûsu klienti var lepoties',
    'description_2'         => 'Aplûkojiet biznesa lapas izkârtojumu, jûs redzçsiet efektîvu un çrtu veikalu un biroju vienuviet!',
    'start_now'             => 'Izmeìiniet bez maksas jau tagad',
    'tagline'               => 'Viss, kas nepiecieðams <br>vienkârðam un pelnoðam biznesam',
    'next_timeslot'         => 'Nâkamie rezervâcijai pieejamie laiki',
    'time'                  => 'Laiks',
    'search_tagline'        => 'Ko jūs vēlaties rezervēt?', // @todo
    'search_query'          => 'Evadiet uzņēmuma nosaukumu vai pakalpojums', // @todo
    'search_place'          => 'Riga, LV', // @todo
    'search'        => [
        'tagline'         => 'Ko jūs vēlaties rezervēt?', // @todo
        'query'           => 'Evadiet uzņēmuma nosaukumu vai pakalpojums', // @todo
        'location'        => Settings::get('default_location'), // @todo
        'about'           => 'Ap',
        'locations_hours' => 'Atraðanâs vieta &amp; darba laiks',
        'business_hours'  => 'Darba laiks',
        'buy'             => 'Pirkt',
        'book'            => 'Rezervçt',
        'button'          => 'Meklēšana', // @todo
        'date'            => 'Izvēlēties datumu', // @todo
        'time'            => 'Izvēlēties laiku', // @todo
        'geo'             => [
            'info' => 'Lûdzu, norâdiet savu atraðanâs vietu, lai varam piemeklçt atbilstoðus piedâvâjumus Jûsu apkaimç.'
        ]
    ],
    'cart' => [
        'add'         => 'Pievienot manam sarakstam',
        'items'       => 'pakalpojums|pakalpojumi',
        'empty'       => 'Tukðs',
        'empty_long'  => 'Jûsu rezervâciju saraksts ir tukðs.',
        'checkout'    => 'Izrakstîties',
        'total'       => 'Kopâ',
        'heading'     => 'Izvçlçtie produkti',
        'why_heading' => 'Kâdçï pirms izrakstîðanâs vajadzçtu reìistrçties?',
        'why_content' => 'Reìistrçtie lietotâji var çrti pârlûkot un organizçt savas rezervâcijas, kâ arî izmantot citas ToBook.lv piedâvâtâs iespçjas.',
        'process'     => 'Pâriet uz maksâjumu lapu',
        'err'         => [
            'business' => 'Ðobrîd nepiedâvâjam iespçju izmantot nereìistrçtu biznesa kontu. Lûdzu, ienâciet sistçmâ ar savu reìistrçto lietotâjvârdu.',
            'zero_amount' => 'Maksâjums nav iespçjams, jo Jûsu rezervâciju sarakstâ nav pasûtîjumu.',
        ]
    ],
    'choose_category'     => 'Izvēlēties kategoriju', // @todo
    'how_does_it_work'    => 'Kā tas darbojas?', // @todo
    'businesses'          => 'Visiem uzņēmumiem', // @todo
    'businesses_category' => 'Komersanti <strong>:Kategorijas</strong>', // @todo
    'more'                => 'Vairāk', // @todo
    'less'                => 'Mazāk', // @todo
    'companies_offers'    => 'Uzņēmumi ar piedāvājumiem', // @todo
    'categories'          => 'Kategorijas', // @todo
    'best_offers'         => 'Labākie piedāvājumi', // @todo
    'no_offers'           => 'Nav piedāvājumu pieejama', // @todo
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
