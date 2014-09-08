<?php
$termBody = <<< HTML
Varausehdot

Varaus tulee sitovasti voimaan, kun asiakas on tehnyt varauksen ja saanut siitä vahvistuksen joko puhelimitse tai kirjallisesti sähköpostitse. Palveluntarjoaja kantaa kaiken vastuun palvelun tuottamisesta ja hoitaa tarvittaessa kaiken yhteydenpidon asiakkaisiin.

Peruutusehdot

Varaajalla on oikeus peruutus- ja varausehtojen puitteissa peruuttaa varauksensa ilmoittamalla siitä puhelimitse vähintään 48h ennen palveluajan alkamista. Muutoin paikalle saapumatta jättämisestä voi palveluntarjoaja halutessaan periä voimassaolevan hinnastonsa mukaisen palvelukorvauksen.
HTML;

$reminderBody = <<< HTML
Hei {Name},
Tämä on muistutusviesti varauksestasi!

Varaus id: {BookingID}

Palvelut

{Services}

Terveisin,
HTML;

$reminderSmsMessage = <<< HTML
Hei,

Kiitos varauksestasi palveluun:

{Services}

Terveisin,
HTML;

$confirmTokensClient = <<< HTML
Hei!

Kiitos varauksestasi!

Valitut palvelut:
{Services}

**Mikäli peruutat varauksen se tulee tehdä 48 tuntia ennen varattua aikaa.

Tervetuloa!



Palvelun tarjoaa varaa.com
HTML;

// @todo
$paymentTokensClient = <<< HTML
We've received the payment for your booking and it is now confirmed.

ID: {BookingID}

Thank you,
The Management
HTML;

$confirmTokensAdmin = <<< HTML
Hei!

Olet saanut uuden varauksen

ID: {BookingID}

Palvelut
{Services}

Asiakkaan tiedot
Nimi: {Name}
Puhelin: {Phone}
Email: {Email}

Lisätiedot:
{Notes}
HTML;

// @todo
$paymentTokensAdmin = <<< HTML
Booking deposit has been paid.

ID: {BookingID}
HTML;

$confirmTokensEmployee = <<< HTML
Hei!

Olet saanut uuden varauksen

ID: {BookingID}

Palvelut
{Services}

Asiakkaan tiedot
Nimi: {Name}
Puhelin: {Phone}
Email: {Email}

Lisätiedot:
{Notes}
HTML;

// @todo
$paymentTokensEmployee = <<< HTML
Booking deposit has been paid.

ID: {BookingID}
HTML;

return [
    'index' => [
        'heading'     => 'Etusivu',
        'description' => 'Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.',
        'today'       => 'Tänään',
        'tomorrow'    => 'Huomenna',
        'print'       => 'Tulosta',
    ],
    'services' => [
        'categories' => [
            'all'           => 'Kaikki kategoriat',
            'add'           => 'Lisää kategoria',
            'edit'          => 'Edit category',
            'name'          => 'Nimi',
            'description'   => 'Kuvaus',
            'is_show_front' => 'Varattavissa kuluttajille',
        ],
        'resources' => [
            'all'         => 'Kaikki resurssit',
            'add'         => 'Lisää resurssi',
            'edit'        => 'Edit resource',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
            'quantity'    => 'Quantity',
        ],
        'extras' => [
            'all'         => 'Kaikki lisäpalvelut',
            'add'         => 'Lisää lisäpalvelu',
            'edit'        => 'Edit extra service',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
            'price'       => 'Hinta',
            'length'      => 'Length',
        ],
        'all'          => 'Kaikki palvelut',
        'index'        => 'Palvelut',
        'desc'         => 'Näkymässä näet kaikki lisäämäsi palvelut. Voit lisätä uusia palveluita tai muokata olemassa olevia palveluita muokkaa napista.',
        'add'          => 'Lisää palvelu',
        'add_desc'     => 'Lisää uusi palvelu lisäämällä palvelun nimi, palvelun kesto ja työntekijät',
        'name'         => 'Nimi',
        'description'  => 'Kuvaus',
        'price'        => 'Hinta',
        'duration'     => 'Kesto',
        'before'       => 'Ennen',
        'after'        => 'Jälkeen',
        'total'        => 'Yhteensä',
        'category'     => 'Kategoria',
        'is_active'    => 'Tila',
        'resource'     => 'Resurssit',
        'extra'        => 'Lisäpalvelut',
        'employees'    => 'Työntekijät',
        'no_employees' => 'There is no employee to be selected',
    ],
    'bookings' => [
        'confirmed'       => 'Tila: vahvistettu',
        'pending'         => 'Tila: auki',
        'cancelled'       => 'Tila: peruutettu',
        'all'             => 'Varaukset',
        'add'             => 'Tee varaus',
        'invoices'        => 'Laskut',
        'customers'       => 'Asiakkaat',
        'statistics'      => 'Statistiikka',
        'date'            => 'Päivämäärä',
        'total'           => 'Kaikki',
        'start_time'      => 'Aloitusaika',
        'end_time'        => 'Päättymisaika',
        'status'          => 'Tila',
        'ip'              => 'ip', // @todo,
        'add_service'     => 'Lisää palvelu',
        'booking_info'    => 'Tiedot',
        'booking_id'      => 'Yksilöllinen ID',
        'categories'      => 'Kategoriat',
        'services'        => 'Palvelut',
        'service_time'    => 'Kesto',
        'modify_time'     => 'Muokkaa aikaa',
        'employee'        => 'Työntekijä',
        'notes'           => 'Muistiinpanoja',
        'firstname'       => 'Etunimi',
        'lastname'        => 'Sukunimi',
        'email'           => 'Sähköposti',
        'phone'           => 'Puhelinnumero',
        'address'         => 'Osoite',
        'confirm_booking' => 'Vahvista varaus',
        'error'           => [
            'add_overlapped_booking' => 'Overlapped booking time!',// @todo
            'insufficient_slots'     => 'There is no enough time slots for this booking!',// @todo
        ],
        'warning'      => [
            'existing_user'   => 'There is an user associate with this email in our system. Do you want to use these information instead?',// @todo
        ]
    ],
    'employees' => [
        'all'                 => 'Työntekijät',
        'add'                 => 'Lisää työntekijä',
        'edit'                => 'Edit employee', // @todo
        'name'                => 'Nimi',
        'phone'               => 'Puhelinnumero',
        'email'               => 'Sähköpostiosoite',
        'description'         => 'Kuvaus',
        'is_subscribed_email' => 'Lähetä sähköposti',
        'is_subscribed_sms'   => 'Lähetä tekstiviesti',
        'services'            => 'Palvelut',
        'status'              => 'Tila',
        'is_active'           => 'Tila',
        'avatar'              => 'Kuva',
        'default_time'        => 'default_time', // @todo
        'custom_time'         => 'custom_time', // @todo
        'days_of_week'        => 'Viikonpäivä',
        'start_time'          => 'Aloitusaika',
        'end_time'            => 'Lopetusaika',
        'day_off'             => 'Vapaapäivä',
        'confirm'             => [
            'delete_freetime' => 'Oletko varma, että haluat poistaa valitun vapaa ajan kalenterista?'
        ]
    ],
    'embed' => [
        'heading'          => 'Otsikko',
        'description'      => 'Sisältö',
        'embed'            => 'Asenna',
        'preview'          => 'Esikatselu',
        'back_to_services' => 'Takaisin Palveluihin',
        'select_date'      => 'Valitse Päivämäärä',
        'select_service'   => 'Valitse Palvelut',
        'guide_text'       => 'Klikkaa avointa aikaa',
        'make_appointment' => 'Tee varaus',
        'cancel'           => 'Peruuta',
        'empty_cart'       => 'Ostoskori on tyhjä',
        'start_time'       => 'Aloitusaika',
        'end_time'         => 'Päättymisaika',
        'booking_form'     => 'Varauslomake',
        'name'             => 'Nimi',
        'email'            => 'Sähköposti',
        'phone'            => 'Puhelinnumero',
        'checkout'         => 'Checkout', // @todo
    ],
    'options' => [
        'updated' => 'Options updated', // @todo
        'general' => [
            'index'           => 'Yleinen',
            'heading'         => 'Yleisasetukset',
            'info'            => 'Säädä asetuksesi kohdalleen',
            'currency'        => 'Valuutta',
            'custom_status'   => 'Custom Status',
            'datetime_format' => 'Päiväyksen muoto',
            'date_format'     => 'Päivämäärä formaatti',
            'time_format'     => 'Aikamuoto',
            'layout'          => 'Näkymä',
            'seo_url'         => 'SEO URLs',
            'timezone'        => 'Aikavyöhyke',
            'week_numbers'    => 'Näytä viikkonumerot',
            'week_start'      => 'Viikon ensimmäinen päivä?',
            'phone_number'    => 'SMS phone number', // @todo
            'business_name'   => 'Business name', // @todo
        ],
        'booking' => [
            'index'                            => 'Varaukset',
            'booking_form'                     => 'Varauslomake',
            'reminders'                        => 'Muistutus',
            'confirmations'                    => 'Vahvistus',
            'terms'                            => 'Ehdot',
            'confirmed'                        => 'Confirmed',
            'pending'                          => 'Pending',
            'accept_bookings'                  => 'Hyväksy varauksia',
            'hide_prices'                      => 'Piilota hinnat',
            'step'                             => 'Askel',
            'status_if_not_paid'               => 'Oletustila maksetuille varauksille',
            'status_if_paid'                   => 'Oletustila maksamattomille varauksille',
            'bf_address_1'                     => 'Osoite 1',
            'bf_address_2'                     => 'Osoite 2',
            'bf_captcha'                       => 'Tunniste',
            'bf_city'                          => 'Kaupunki',
            'bf_country'                       => 'Maa',
            'bf_email'                         => 'Sähköposti',
            'bf_name'                          => 'Nimi',
            'bf_notes'                         => 'Muistiinpanoja',
            'bf_phone'                         => 'Puhelinnumero',
            'bf_state'                         => 'Kunta',
            'bf_terms'                         => 'Ehdot',
            'bf_zip'                           => 'Postinumero',
            'reminder_enable'                  => 'Muistutusviestit käytössä',
            'reminder_email_before'            => 'Lähetä muistutus sähköpostilla',
            'reminder_subject'                 => 'Muistutussähköpostiviestin otsikko',
            'reminder_subject_default'         => 'Muistutus varauksestasi',
            'reminder_body'                    => 'Sähköpostimuistutuksen runko',
            'reminder_body_default'            => $reminderBody,
            'reminder_sms_hours'               => 'Lähetä muistutus tekstiviestillä',
            'reminder_sms_country_code'        => 'SMS Maatunnus',
            'reminder_sms_message'             => 'Tekstiviesti',
            'reminder_sms_message_default'     => $reminderSmsMessage,
            'terms_url'                        => 'URL osoite ehdoille',
            'terms_body'                       => 'Booking terms content', // @todo
            'terms_body_default'               => $termBody,
            'confirm_subject_client'           => 'Asiakkaan vahvistuksen otsikko',
            'confirm_subject_client_default'   => 'Kiitos varauksestasi',
            'confirm_tokens_client'            => 'Viestin sisältö',
            'confirm_tokens_client_default'    => $confirmTokensClient,
            'payment_subject_client'           => 'Asiakkaan maksuvahvistuksen otsikko',
            'payment_subject_client_default'   => 'Payment received', // @todo
            'payment_tokens_client'            => 'Viestin sisältö',
            'payment_tokens_client_default'    => $paymentTokensClient,
            'confirm_subject_admin'            => 'Hallintapaneelin maksuvahvistuksen otsikko',
            'confirm_subject_admin_default'    => 'Uusi varaus on saapunut',
            'confirm_tokens_admin'             => 'Viestin sisältö',
            'confirm_tokens_admin_default'     => $confirmTokensAdmin,
            'payment_subject_admin'            => 'Ylläpitäjän maksuvahvistuksen otsikko',
            'payment_subject_admin_default'    => 'New payment received', // @todo
            'payment_tokens_admin'             => 'Viestin sisältö',
            'payment_tokens_admin_default'     => $paymentTokensAdmin,
            'confirm_subject_employee'         => 'Työntekijän varauksen otsikko',
            'confirm_subject_employee_default' => 'Uusi varaus on saapunut',
            'confirm_tokens_employee'          => 'Viestin sisältö',
            'confirm_tokens_employee_default'  => $confirmTokensEmployee,
            'payment_subject_employee'         => 'Työntekijän maksun otsikko',
            'payment_subject_employee_default' => 'New payment received', // @todo
            'payment_tokens_employee'          => 'Viestin sisältö',
            'payment_tokens_employee_default'  => $paymentTokensEmployee,
        ],
        'style' => [
            'index'               => 'Tyyli',
            'style_logo'          => 'Logo URL',
            'style_banner'        => 'Banneri',
            'style_heading_color' => 'Heading color', // @todo
            'style_color'         => 'Väri',
            'style_background'    => 'Tausta',
            'style_custom_css'    => 'Custom CSS', // @todo
        ],
        'working_time' => [
            'index' => 'Working time',
        ]
    ],
    'reports' => [
        'index'     => 'Raportit',
        'employees' => 'Työntekijävakko',
        'services'  => 'Palveluvalikko',
        'generate'  => 'Generoi',
        'start'     => 'Alkaa',
        'end'       => 'Päättyy',
        'booking'   => [
            'total'       => 'Kokonaisvaraukset',
            'confirmed'   => 'Vahvistetut varaukset',
            'unconfirmed' => 'Vahvistamattomat varaukset',
            'cancelled'   => 'Peruutetut varaukset',
        ]
    ],
    'items_per_page' => 'Yksiköitä yhteensä',
    'with_selected'  => 'Valitse toiminto',
    'crud' => [
        'bulk_confirm' => 'Are you sure to carry out this action?',
        'success_add' => 'Item was created successfully.',
        'success_edit' => 'Data was updated successfully.',
        'success_delete' => 'Item was deleted successfully.',
        'success_bulk' => 'Item was deleted successfully.',
    ]
];
