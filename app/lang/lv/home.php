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

$homeContactEmail = <<< HTML
<p>Hello,</p>

<p>Visitor from :email sent you a message:</p>

<p>------------------</p>
<p>:content</p>
<p>------------------</p>
HTML;

$footerAbout = <<< HTML
<p>Tiešsaistes skaistumkopšanas salonu pakalpojumu rezervējumu servisu nodrošina AS DELFI, kas ir pakļauta Latvijas likumdošanai. Uzņēmuma birojs atrodas Mūkusalas 41B-8 Rīgā, Latvijā. Uzņēmuma reģistrācijas numurs ir 40003504352.</p>
<p>Tālrunis kontaktiem: +371 27548440</p>
<p>e-pasts: <a href="mailto:info@tobook.lv">info@tobook.lv</a></p>
HTML;

return [
    'customer_websites'     => 'Klientu lapas',
    'description'           => 'Izveidojiet efektīvu un skaistu lapu!',
    'copyright_policy'      => 'Autortiesību politika',
    'copyright'             => 'Autortiesības',
    'newsletter'            => 'Newsletter',
    'enter_your_email'      => 'Jūsu e-pasta adrese',
    'submit'                => 'Iesniegt',
    'location'              => 'Atrašanās vieta',
    'telephone'             => 'Tālrunis',
    'homepages'             => 'Mājas lapa',
    'loyaltycard'           => 'Klienta karte',
    'customer_register'     => 'Klienta reģistrācija',
    'cashier'               => 'Kase',
    'description_1'         => 'Mūsu mērķis - kvalitatīvas mājas lapas, ar kurām mūsu klienti var lepoties',
    'description_2'         => 'Aplūkojiet biznesa lapas izkārtojumu, jūs redzēsiet efektīvu un ērtu veikalu un biroju vienuviet!',
    'start_now'             => 'Izmeģiniet bez maksas jau tagad',
    'tagline'               => 'Viss, kas nepieciešams <br>vienkāršam un pelnošam biznesam',
    'next_timeslot'         => 'Nākamie rezervācijai pieejamie laiki',
    'time'                  => 'Laiks',

    'search_tagline'        => 'Rezervē it visu!',
    'search_query'          => 'Meklē pakalpojumu',
    'search_place'          => 'Rīga',
    'video_tutorial_text'   => 'Kā tas strādā?',
    'video_tutorial_link'   => 'https://www.youtube.com/watch?v=FrMUPi7Yo7U',
    'search'        => [
        'tagline'         => 'Rezervē, ko vēlies',
        'query'           => 'Meklē uzņēmumu',
        'location'        => 'Atrašanās vieta',
        'about'           => 'Darba laiks',
        'locations_hours' => 'Atrašanās vieta &amp; darba laiks',
        'business_hours'  => 'Darba laiks',
        'buy'             => 'Pirkt',
        'book'            => 'Rezervēt',
        'button'          => 'Meklēšana',
        'date'            => 'Izvēlēties datumu',
        'time'            => 'Izvēlēties laiku',
        'current_location' => 'Atrašanās vieta',
        'force_selection' => 'Lūdzu, izvēlieties no ieteikumu saraksta.',
        'validation'      => [
            'q'        => 'Lūdzu ievadiet uzņēmumu, vai pakalpojumu',
            'location' => 'Lūdzu izvēlieties vietu',
        ],
        'geo'             => [
            'info' => 'Lūdzu, norādiet savu atrašanās vietu, lai varam piemeklēt atbilstošus piedāvājumus Jūsu apkaimē.'
        ]
    ],
    'cart' => [
        'add'             => 'Pievienot grozam',
        'items'           => '|pakalpojumi|pakalpojums', // Must begin with | or will cause errors
        'empty'           => 'Tukšs',
        'empty_long'      => 'Jūsu rezervāciju saraksts ir tukšs.',
        'checkout'        => 'Apmaksas veidi',
        'total'           => 'Kopā',
        'heading'         => 'Izvēlētie produkti',
        'why_heading'     => 'Kādēļ pirms izrakstīšanās vajadzētu reģistrēties?',
        'why_content'     => 'Reģistrētie lietotāji var ērti pārlūkot un organizēt savas rezervācijas, kā arī izmantot citas ToBook.lv piedāvātās iespējas.',
        'process'         => 'Pāriet uz maksājumu lapu',
        'total_deposit'   => 'Depozīta summa',
        'pay_deposit'     => 'Maksāt depozītu',
        'pay_full'        => 'Maksāt pilnu summu',
        'pay_venue'       => 'Maksāt uz vietas',
        'terms'           => 'Lūdzu ņemiet vērā, neierodoties uz sev rezervēto laiku,  Jūs radiet zaudējumus salonam. <br/>Ja šāda rīcība atkārtojas, salons var iekļaut Jūs “melnajā sarakstā”, un zaudēsiet iespēju tur veikt rezervācijas.',
        'checkout_message'=> 'Izvēlēties maksājuma veidu',
        'err'         => [
            'business' => 'Šobrīd nepiedāvājam iespēju izmantot nereģistrētu biznesa kontu. Lūdzu, ienāciet sistēmā ar savu reģistrēto lietotājvārdu.',
            'zero_amount' => 'Maksājums nav iespējams, jo Jūsu rezervāciju sarakstā nav pasūtījumu.',
        ]
    ],
    'choose_category'     => 'Izvēlēties kategoriju',
    'how_does_it_work'    => 'Kā tas darbojas?',
    'businesses'          => 'Visiem uzņēmumiem',
    'businesses_category' => 'Komersanti <strong>:Kategorijas</strong>',
    'more'                => 'Vairāk',
    'less'                => 'Mazāk',
    'companies_offers'    => 'Uzņēmumi ar piedāvājumiem',
    'categories'          => 'Kategorijas',
    'best_offers'         => 'Labākie piedāvājumi',
    'no_offers'           => 'Uz dotu brīdi speciālo piedāvāju nav.',
    'map'                 => 'Karte',
    'show_more'           => 'Parādīt vairāk',
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
        'about'          => 'Par uzņēmumu', // @todo
        'openning_hours' => 'Darba laiks', // @todo
        'map'            => 'Karte', // @todo
        'phone'          => 'Telefons', // @todo
        'email'          => 'E-pasts', // @todo
        'online_booking' => 'Online booking', // @todo
        'request'        => [
            'link'    => 'Jautāt tiešsaistes rezervācijas', // @todo
            'info'    => 'Veikala īpašnieks tiks lūgts, lai izmantotu mūsu tiešsaistes rezervācijas sistēmu.', // @todo
            'subject' => 'Pieprasījums izmantot tiešsaistes rezervēšanas sistēmu', // @todo
            'mail'    => $requestMail, // @todo
        ],
        'contact'        => [
            'index'   => 'Kontakts', // @todo
            'heading' => 'Sazinieties ar mums', // @todo
            'name'    => 'Vārds*', // @todo
            'email'   => 'E-pasts*', // @todo
            'phone'   => 'Telefons', // @todo
            'captcha' => 'Lūdzu zemāk ievadiet simbolus*', // @todo
            'message' => 'Ziņa*', // @todo
            'sent'    => 'Jūsu ziņojums ir nosūtīts', // @todo
            'subject' => 'Tev Kontakta ziņu', // @todo
            'mail'    => $contactEmail, // @todo
        ]
    ],
    // Contact form
    'contact' => [
        'subject' => 'Visitor contact message received',
        'body'    => $homeContactEmail,
        'sent'    => 'Thank you, we have received your message.',
    ],
    // Static pages
    'pages' => [
        'terms_conditions' => 'Nosacījumi un noteikumi',
        'privacy_cookies'  => 'Noteikumi un konfidenciāla politika',
    ],
    'footer' => [
        'subscribe' => 'Pieteikties jaunumiem',
        'email' => 'Jūsu e-pasta adrese',
        'btn_subscribe' => 'PASŪTĪT',
        'about' => 'Par :site',
        'about_content' => $footerAbout,
        'info' => 'Informācija pircējiem',
        'terms' => 'Noteikumi un nosacījumi',
        'policy' => 'Sīkdatņu izmantošana',
        'contact' => 'Kontaktu forma',
        'message' => 'Ierakstiet, lūdzu, savu jautājumu vai ierosinājumu šeit',
        'send' => 'NOSŪTĪT',
    ],
];
