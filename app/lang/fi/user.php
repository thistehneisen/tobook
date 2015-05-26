<?php
return [
    'all'                    => 'Kaikki käyttäjät',
    'add'                    => 'Lisää käyttäjiä',
    'deleted'                => 'Deleted users',
    'restore'                => 'Restore',
    'edit'                   => 'Muokkaa',
    'activate'               => 'Activate',
    'deactivate'             => 'Deactivate',
    'activated'              => 'Activated',
    'types'                  => 'Tyypit',
    'change_password'        => 'Muokkaa salasanaa',
    'old_password'           => 'Vanha salasana',
    'password'               => 'Salasana',
    'password_confirmation'  => 'Vahvista salasana',
    'forced_change_password' => 'Olemme äskettäin muuttaneet järjestelmää ja kaikkia käyttäjiä pyydetään vaihtamaan salasanasi välittömästi.',
    'change_profile_success' => 'Sinun profiili on päivittänyt',
    'change_profile_failed'  => 'Tietojen päivittäminen epäonnistui, ole hyvä ja yritä uudelleen.',
    'incorrect_old_password' => 'Vanha salasanasi on väärä. Yritä uudelleen.',
    'create_account'         => 'Luo Tilin',
    'fill_fields'            => 'Täytä seuraavat tiedot',
    'username'               => 'Käyttäjänimi',
    'email'                  => 'Sähköposti',
    'first_name'             => 'Etunimi',
    'last_name'              => 'Sukunimi',
    'name'                   => 'Nimi',
    'phone'                  => 'Puhelin',
    'address'                => 'Osoite',
    'city'                   => 'Kaupunki',
    'postcode'               => 'Postinumero',
    'country'                => 'Maa',
    'accept_terms'           => 'Painamalla &quot;Rekisteröidy&quot; hyväksyt',
    'terms'                  => 'Ehdot',
    'register_already'       => 'Oletko jo rekisteröitynyt?',
    'new_customers'          => 'Uusi käyttäjä',
    'register_here'          => 'Rekisteröidy tästä',
    'forgot_password'        => 'Unohditko salasanasi?',
    'forgot_password_title'  => 'Unohdin salasana',
    'click_here'             => 'Klikkaa tästä',
    'fill_reset_password'    => 'Ole hyvä ja kirjoita sähköpostisi nollaamaan salasana',
    'reset_password'         => 'Salasanan nollaaminen',
    'enter_new_password'     => 'Kirjoita uusi salasana',
    'business_name'          => 'Yrityksen nimi',
    'password_reminder' => [
        'reset' => [
            'heading' => 'Salasanasi on resetoitu',
            'body'    => 'Uusi salasanasi on :password',
        ],
        'created' => [
            'heading' => 'Uusi tunnus on luotu',
            'body' => 'Salasanasi on :password',
        ]
    ],
    'business' => [
        'auto_confirm'     => 'Auto confirm this business?', // @todo
        'is_activated'     => 'Aktivoitu',
        'name'             => 'Yrityksen nimi',
        'description'      => 'Yritysesittely',
        'size'             => 'Henkilömäärä',
        'phone'            => 'Puhelin',
        'address'          => 'Osoite',
        'city'             => 'Kaupunki',
        'postcode'         => 'Postinumero',
        'country'          => 'Maa',
        'note'             => 'Huomautus',
        'meta_title'       => 'Meta title', // @todo
        'meta_description' => 'Meta description', // @todo
        'meta_keywords'    => 'Meta keywords', // @todo
        'bank_account'     => 'Bank account', // @todo
        'is_hidden'        => 'Is hidden business?', // @todo
        'preview'          => 'Preview your page', // @todo
        'is_booking_disabled' => 'Disable booking widget?', // @todo
        'sizes'            => [
            '1',
            '2-3',
            '3-5',
            '5-10',
            '20-50',
            '50+',
        ],
    ],
    'profile' => [
        'index'                => 'Tietoni',
        'general'              => 'Yleiset tiedot',
        'images'               => 'Kuvat',
        'no_images'            => 'Kuvia ei löytynyt, ole ystävällinen ja lataa uusia kuvia.',
        'select_files'         => 'Valitse tiedostot',
        'upload'               => 'Lataa',
        'upload_images'        => 'Lataa uusia kuvia',
        'description'          => 'Yritysesittely',
        'business_size'        => 'Henkilömäärä',
        'business'             => 'Yritys',
        'working_hours'        => 'Työajat',
        'days_of_week'         => 'Viikonpäivä',
        'start_time'           => 'Avaamme',
        'end_time'             => 'Suljemme',
        'extra'                => 'Lisätiedot',
        'business_size_values' => [
            '1',
            '2-3',
            '3-5',
            '5-10',
            '20-50',
            '50+',
        ],
        'business_categories' => [
            'index'                  => 'Kategoria',
            'beauty_hair'            => 'Kauneus &amp; Hiukset',
            'restaurant'             => 'Ravintola',
            'car'                    => 'Auto',
            'wellness'               => 'Hyvinvointi',
            'activities'             => 'Toiminta',
            'home'                   => 'Koti',
            'beautysalon'            => 'Kauneushoitola',
            'nails'                  => 'Kynnet',
            'hairdresser'            => 'Parturi- Kampaamo',
            'fine_dining'            => 'Fine Dining',
            'nepalese'               => 'Nepalialainen',
            'traditional'            => 'Perinteinen',
            'sushi'                  => 'Sushi',
            'thai'                   => 'Thai',
            'italian'                => 'Italialainen',
            'grill'                  => 'Grilli',
            'chinese'                => 'Kiinalainen',
            'car_wash'               => 'Autopesu',
            'car_service'            => 'Autohuolto',
            'bike_service'           => 'Pyörähuolto',
            'physical_theraphy'      => 'Fysioterapia',
            'massage'                => 'Hieronta',
            'dentist'                => 'Hammaslääkäri',
            'acupuncture'            => 'Akupunktio',
            'chiropractic_treatment' => 'Kiropraktikko',
            'teeth_whitening'        => 'Hampaiden valkaisu',
            'bowling'                => 'Keilailu',
            'karting'                => 'Karting',
            'gym'                    => 'Kuntosali',
            'dance'                  => 'Tanssi',
            'badminton'              => 'Sulkapallo',
            'tennis'                 => 'Tennis',
            'personal_training'      => 'Personal Training',
            'yoga'                   => 'Jooga',
            'house_cleaning'         => 'Kotisiivous',
            'handyman'               => 'Rakennuspalvelu',
            'photography'            => 'Valokuvaus',
            'babysitting'            => 'Lapsenvahti',
            'snow_removal'           => 'Lumityöt',
        ]
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
