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
    'search'        => [
        'tagline'         => 'Rezervē, ko vēlies',
        'query'           => 'Meklē uzņēmumu',
        'location'        => 'Atrašanās vieta',
        'about'           => 'Darba laiks',
        'locations_hours' => 'Atrašanās vieta &amp; darba laiks',
        'business_hours'  => 'Darba laiks',
        'buy'             => 'Pirkt',
        'book'            => 'Rezervēt',
        'button'          => 'Meklēšana', // @todo
        'date'            => 'Izvēlēties datumu', // @todo
        'time'            => 'Izvēlēties laiku', // @todo
        'current_location' => 'Your current location', // @todo
        'validation'      => [
            'q'        => 'Please enter or select a service', // @todo
            'location' => 'Please enter or select a location', // @todo
        ],
        'geo'             => [
            'info' => 'Lūdzu, norādiet savu atrašanās vietu, lai varam piemeklēt atbilstošus piedāvājumus Jūsu apkaimē.'
        ]
    ],
    'cart' => [
        'add'             => 'Pievienot grozam',
        'items'           => '|pakalpojumi|pakalpojums',
        'empty'           => 'Tukšs',
        'empty_long'      => 'Jūsu rezervāciju saraksts ir tukšs.',
        'checkout'        => 'Pirkt',
        'total'           => 'Kopā',
        'heading'         => 'Izvēlētie produkti',
        'why_heading'     => 'Kādēļ pirms izrakstīšanās vajadzētu reģistrēties?',
        'why_content'     => 'Reģistrētie lietotāji var ērti pārlūkot un organizēt savas rezervācijas, kā arī izmantot citas ToBook.lv piedāvātās iespējas.',
        'process'         => 'Pāriet uz maksājumu lapu',
        'total_deposit'   => 'Total deposit',//@todo
        'pay_deposit'     => 'Process to deposit',//@todo
        'pay_whole'       => 'Process to payment',//@todo
        'deposit_message' => 'You can choose to pay the deposit, or the total sum of the booking in order to continue.',//@todo
        'err'         => [
            'business' => 'Šobrīd nepiedāvājam iespēju izmantot nereģistrētu biznesa kontu. Lūdzu, ienāciet sistēmā ar savu reģistrēto lietotājvārdu.',
            'zero_amount' => 'Maksājums nav iespējams, jo Jūsu rezervāciju sarakstā nav pasūtījumu.',
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
    'map'                 => 'Karte', // @todo
    'show_more'           => 'Parādīt vairāk', // @todo
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
];
