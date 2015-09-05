<?php
return [
    'all'                    => 'Všetci užívatelia',
    'add'                    => 'Pridať užívateľa',
    'deleted'                => 'Vymazaný užívatelia',
    'restore'                => 'Obnoviť',
    'edit'                   => 'Upraviť užívateľa',
    'activate'               => 'Aktivovať',
    'deactivate'             => 'Deaktivovať',
    'activated'              => 'Aktivované',
    'types'                  => 'Typy',
    'change_password'        => 'Zmeniť heslo',
    'old_password'           => 'Staré heslo',
    'password'               => 'Heslo',
    'password_confirmation'  => 'Potvrdiť heslo',
    'forced_change_password' => 'Aktualizovali sme náš systém a všetci užívatelia sú vyzvaný na okamžitú zmenu hesla.',
    'change_profile'         => 'Aktualizovať profil',
    'change_profile_success' => 'Váš profil bol aktualizovaný',
    'change_profile_failed'  => 'Profil sa nepodarilo aktualizovať. Skúste to znova.',
    'create_account'         => 'Vytvoriť účet',
    'fill_fields'            => 'Vyplnte informácie',
    'username'               => 'Užívateľske meno',
    'first_name'             => 'Meno',
    'last_name'              => 'Priezvisko',
    'email'                  => 'Email',
    'name'                   => 'Meno',
    'phone'                  => 'Telefón',
    'address'                => 'Adresa',
    'city'                   => 'Mesto',
    'postcode'               => 'PSČ',
    'country'                => 'Krajina',
    'accept_terms'           => 'Kliknutím na tlačidlo &quot;Registrovať&quot; akceptujete',
    'terms'                  => 'Podmienky',
    'register_already'       => 'Už ste zaregistrovaný?',
    'new_customers'          => 'Nový zákazník',
    'register_here'          => 'Zaregistrujte sa tu',
    'forgot_password'        => 'Zabudli ste heslo?',
    'forgot_password_title'  => 'Zabudli ste heslo?',
    'click_here'             => 'Kliknite tu',
    'fill_reset_password'    => 'Prosím zadajte email pre zresetovanie hesla',
    'reset_password'         => 'Zresetovať heslo',
    'enter_new_password'     => 'Prosím, vlože Vaše nové heslo',
    'business_name'          => 'Obchodné meno',
    'activation'             => 'Aktivované?',
    'payments'               => 'Platby',
    'business' => [
        'auto_confirm'        => 'Automatický potvrdiť túto prevádzku?',
        'is_activated'        => 'Aktivovaná',
        'name'                => 'Obchodné meno',
        'description'         => 'O prevádzke',
        'size'                => 'Business Size',
        'phone'               => 'Telefón',
        'address'             => 'Adresa',
        'district'         => 'District', // @todo
        'city'                => 'Mesto',
        'postcode'            => 'PSČ',
        'country'             => 'Krajina',
        'note'                => 'Poznámka',
        'meta_title'          => 'Meta title',
        'meta_description'    => 'Meta description',
        'meta_keywords'       => 'Meta keywords',
        'bank_account'        => 'Bankový účet',
        'is_hidden'           => 'Is hidden business?',
        'preview'             => 'Náhľad Vašej stránky',
        'is_booking_disabled' => 'Deaktivovať rezervačný widget?', // @todo
        'sizes'               => [
            '1',
            '2-3',
            '3-5',
            '5-10',
            '20-50',
            '50+',
        ],
    ],
    'password_reminder' => [
        'reset' => [
            'heading' => 'Vaše heslo bolo zresetované',
            'body'    => 'Vaše nové heslo je :password',
        ],
        'created' => [
            'heading' => 'Vás nový účet bol vytvorený',
            'body' => 'A Vaše heslo je :password',
        ]
    ],
    'profile' => [
        'index'                => 'Môj účet',
        'general'              => 'Základné informácie',
        'images'               => 'Obrázky',
        'no_images'            => 'Neboli nájdené žiadné obrázky. Použite formulár vyššie pre pridanie obrázkov',
        'select_files'         => 'Vyberte súbory',
        'upload'               => 'Nahrať',
        'upload_images'        => 'Nahrať nové obrázky',
        'description'          => 'O prevádzke',
        'business_size'        => 'Veľkosť prevádzky',
        'business'             => 'Prevádzka',
        'working_hours'        => 'Pracovná doba',
        'days_of_week'         => 'Dni v týždni',
        'start_time'           => 'Začiatok',
        'end_time'             => 'Koniec',
        'extra'                => 'Doplňujúce informácie',
        'hide_working_hours'   => 'Skryť pracovnú dobu',
        'business_size_values' => [
            '1',
            '2-3',
            '3-5',
            '5-10',
            '20-50',
            '50+',
        ],
        'business_categories' => [
            'index'                  => 'Business areas',
            'beauty_hair'            => 'Beauty &amp; Hair',
            'restaurant'             => 'Reštaurácie',
            'car'                    => 'Auto',
            'wellness'               => 'Wellness',
            'activities'             => 'Activities',
            'home'                   => 'Domov',
            'beautysalon'            => 'Salóny krásy',
            'nails'                  => 'Nechty',
            'hairdresser'            => 'Kaderníctvo',
            'fine_dining'            => 'Fine Dining',
            'nepalese'               => 'Nepalese',
            'traditional'            => 'Traditional',
            'sushi'                  => 'Sushi',
            'thai'                   => 'Thai',
            'italian'                => 'Italian',
            'grill'                  => 'Grill',
            'chinese'                => 'Chinese',
            'car_wash'               => 'Car Wash',
            'car_service'            => 'Auto servis',
            'bike_service'           => 'Servis bicyklov',
            'physical_theraphy'      => 'Physical Theraphy',
            'massage'                => 'Masáže',
            'dentist'                => 'Zubár',
            'acupuncture'            => 'Akupunktúra',
            'chiropractic_treatment' => 'Chiropractic Treatment',
            'teeth_whitening'        => 'Bielenie zubov',
            'bowling'                => 'Bowling',
            'karting'                => 'Karting',
            'gym'                    => 'Posilovňa',
            'dance'                  => 'Tanec',
            'badminton'              => 'Badminton',
            'tennis'                 => 'Tenis',
            'personal_training'      => 'Personal Training',
            'yoga'                   => 'Jóga',
            'house_cleaning'         => 'Upratovanie domácnosti',
            'handyman'               => 'Handyman',
            'photography'            => 'Fotografovanie',
            'babysitting'            => 'Babysitting',
            'snow_removal'           => 'Odstránenie snehu',
        ],
    ],
    'payment_options' => [
        'index'       => 'Payment options',
        'venue'       => 'Pay at the venue',
        'deposit'     => 'Deposit payment',
        'full'        => 'Full payment',
        'rate'        => 'Deposit rate',
        'placeholder' => 'Enter deposit rate in decimal, e.g. 0.3 for 30%',
    ],
];