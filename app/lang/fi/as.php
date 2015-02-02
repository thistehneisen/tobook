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

$confirmBody = <<< HTML
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

$confirmConsumerMessage = <<< HTML
Hei,

Kiitos varauksestasi palveluun:

{Services}

Terveisin,
HTML;

$confirmEmployeeMessage = <<< HTML
Hei,

Sinulle on uusi varaus asiakkaalta {Consumer} palveluun {Services}

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

$paymentTokensEmployee = <<< HTML
Booking deposit has been paid.

ID: {BookingID}
HTML;

$cancelMessage = <<< HTML
You have cancelled the booking {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Etusivu (asiakastukemme: yritys@varaa.com / puh. 045 146 3755)',
        'description'   => 'Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.',
        'today'         => 'Tänään',
        'tomorrow'      => 'Huomenna',
        'print'         => 'Tulosta',
        'calendar'      => 'Kalenteri',
    ],
    'services' => [
        'heading'       => 'Palvelut',
        'edit'          => 'Muokkaa palvelua',
        'categories' => [
            'all'           => 'Kaikki kategoriat',
            'add'           => 'Lisää kategoria',
            'edit'          => 'Muokkaa kategorioita',
            'name'          => 'Nimi',
            'description'   => 'Kuvaus',
            'is_show_front' => 'Varattavissa kuluttajille',
            'no_services'   => 'Ei palveluita valitulle kategorialle',
            'availability'  => 'Saatavuus',
            'category_name' => 'Kategorian nimi',
            'error'         => [
                'category_current_in_use' => 'Kategoria on käytössä. Ole ystävällinen ja poista kategoriaan liitetyt palvelut ennen kuin voit poistaa kategorian.'
            ]
        ],
        'resources' => [
            'all'         => 'Kaikki resurssit',
            'add'         => 'Lisää resurssi',
            'edit'        => 'Muokkaa resurssia',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
            'quantity'    => 'Määrä',
        ],
        'rooms' => [
            'all'         => 'Huoneet',
            'add'         => 'Lisää huone',
            'edit'        => 'Muokkaa huonetta',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
        ],
        'extras' => [
            'all'         => 'Lisäpalvelut',
            'add'         => 'Lisää lisäpalvelu',
            'edit'        => 'Muokkaa lisäpalvelua',
            'name'        => 'Nimi',
            'description' => 'Kuvaus',
            'price'       => 'Hinta',
            'length'      => 'Kesto',
            'msg_extra'   => 'Haluaisitko varata myös?',
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
        'length'       => 'Kokonaiskesto',
        'during'       => 'Kesto',
        'before'       => 'Ennen',
        'after'        => 'Jälkeen',
        'total'        => 'Yhteensä',
        'category'     => 'Kategoria',
        'is_active'    => 'Aktiivinen',
        'resource'     => 'Resurssit',
        'room'         => 'Huoneet',
        'extra'        => 'Lisäpalvelut',
        'employees'    => 'Työntekijät',
        'no_employees' => 'Työntekijää ei ole valittu',
        'no_name'      => 'Untitled',
        'error'         => [
            'service_current_in_use' => 'Valitsemasi palvelu on käytössä. Ole hyvä ja poista kaikki kyseisen palvelun varaukset ennen palvelun poistamista.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Vahvistettu',
        'pending'           => 'Odottaa',
        'cancelled'         => 'Peruttu',
        'arrived'           => 'Asiakas saapui',
        'paid'              => 'Asiakas maksoi',
        'not_show_up'       => 'Asiakas ei ilmestynyt paikalle',
        'change_status'     => 'Muokkaa tilaa',
        'all'               => 'Varaukset',
        'add'               => 'Tee varaus',
        'invoices'          => 'Laskut',
        'consumer'          => 'Asiakas',
        'date'              => 'Päivämäärä',
        'total'             => 'Yhteensä',
        'start_at'          => 'Aloitusaika',
        'end_at'            => 'Päättymisaika',
        'status'            => 'Tila',
        'total_price'       => 'Hinta',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Lisää palvelu',
        'booking_info'      => 'Tiedot',
        'booking_id'        => 'Yksilöllinen ID',
        'categories'        => 'Kategoriat',
        'services'          => 'Palvelut',
        'service_time'      => 'Kesto',
        'modify_time'       => 'Muokkaa aikaa',
        'plustime'          => 'Lisäaika',
        'total_length'      => 'Total length',//@todo
        'modify_duration'   => 'Muokkaa kestoa',
        'employee'          => 'Työntekijä',
        'notes'             => 'Muistiinpanoja',
        'first_name'        => 'Etunimi',
        'last_name'         => 'Sukunimi',
        'email'             => 'Sähköposti',
        'phone'             => 'Puhelinnumero',
        'address'           => 'Osoite',
        'city'              => 'Kaupunki',
        'postcode'          => 'Postinumero',
        'country'           => 'Maa',
        'confirm_booking'   => 'Vahvista varaus',
        'service_employee'  => 'Työntekijä',
        'date_time'         => 'Päivämäärä',
        'price'             => 'Hinta',
        'extra_service'     => 'Lisäpalvelu',
        'keyword'           => 'Haku',
        'edit'              => 'Muokkaa varausta',
        'terms'             => 'Ehdot',
        'terms_agree'       => 'Hyväksyn varausehdot',
        'cancel_message'    => $cancelMessage,
        'cancel_confirm'    => 'Haluatko varmasti poistaa varauksen %s?',
        'modify_booking'    => 'Muokkaa varausta',
        'reschedule'        => 'Siirrä',
        'confirm_reschedule'=> 'Vahvista siirto',
        'cancel_reschedule' => 'Peru siirto',
        'own_customer'      => 'Oma asiakas',
        'request_employee'  => 'Työntekijää ei saa vaihtaa',
        'error'             => [
            'add_overlapped_booking'      => 'Varauksia ei voi asettaa päällekäin!',
            'insufficient_slots'          => 'Varaus ei mahdu valitsemaasi kohtaan.',
            'invalid_consumer_info'       => 'Virhe asiakastietojen tallennuksessa!',
            'terms'                       => 'Hyväksy ehdot jatkaaksesi',
            'service_empty'               => 'Valitse palvelu & palvelun kesto!',
            'unknown'                     => 'Hups! Jotain meni vikaan.. Yritä uudelleen!',
            'exceed_current_day'          => 'Varaus ei voi olla kestoltaan yli vuorokauden mittainen.',
            'overllapped_with_freetime'   => 'Varausta ei voi sijoittaa työntekijän oman vapaan päälle!',
            'empty_total_time'            => 'Määritä varauksellesi kesto jatkaaksesi.',
            'uuid_notfound'               => 'Varauksen yksilöllistä ID:tä ei löytynyt!',
            'not_enough_slots'            => 'Varauksesi ei mahdu toivomaasi sijaintiin, tai se on menossa toisen varauksen päälle!',
            'employee_not_servable'       => 'Työntekijää ei ole yhdistetty valitsemaasi palveluun!',
            'id_not_found'                => 'Varausta ei löytynyt!',
            'start_time'                  => 'Varauksen aloitusaika ei kelpaa!',
            'service_time_invalid'        => 'Ongelma palvelun keston kanssa!',
            'overlapped_with_freetime'    => 'Varaus on menossa työntekijän oman vapaan päälle!',
            'reschedule_single_only'      => 'Useita palveluita sisältävää varausta ei valitettavasti voi siirtää.',
            'reschedule_unbooked_extra'   => 'Varauksen siirto epäonnistui!',
            'not_enough_resources'        => 'Palvelun vaatima resurssi on jo varattu',
            'not_enough_rooms'            => 'Ei tarpeeksi vapaita huoneita',
            'empty_start_time'            => 'Alkamisajankohta -kenttä ei voi olla tyhjä',
            'booking_not_found'           => 'Varausta ei löytynyt!',
            'past_booking'                => 'Et voi tehdä varausta menneisyyteen!',
            'delete_last_booking_service' => 'You cannot delete the last booking service',//@todo
            'before_min_distance'         => 'You cannot make a booking before the min distance day',//@todo
            'after_max_distance'          => 'You cannot make a booking after the max distance day',//@todo
        ],
        'warning'      => [
            'existing_user'   => 'Järjestelmästä löytyy käyttäjäprofiili antamallesi sähköpostiosoitteelle. Ovatko nämä sinun tietosi?',
        ],
    ],
    'employees' => [
        'all'                              => 'Työntekijät',
        'add'                              => 'Lisää työntekijä',
        'edit'                             => 'Muokkaa työntekijää',
        'name'                             => 'Nimi',
        'phone'                            => 'Puhelinnumero',
        'email'                            => 'Sähköpostiosoite',
        'description'                      => 'Kuvaus',
        'is_subscribed_email'              => 'Työntekijän sähköpostivahvistukset käytössä',
        'is_subscribed_sms'                => 'Työntekijän tekstiviestivahvistukset käytössä',
        'is_received_calendar_invitation'  => 'Is received calendar invitation',
        'services'                         => 'Palvelut',
        'status'                           => 'Tila',
        'is_active'                        => 'Tila',
        'avatar'                           => 'Kuva',
        'default_time'                     => 'Oletustyöajat',
        'custom_time'                      => 'Räätälöidyt ajat',
        'days_of_week'                     => 'Viikonpäivä',
        'start_time'                       => 'Aloitusaika',
        'end_time'                         => 'Lopetusaika',
        'day_off'                          => 'Vapaapäivä',
        'confirm'                          => [
        'delete_freetime'                  => 'Oletko varma, että haluat poistaa valitun vapaa-ajan kalenterista?'
        ],
        'free_time'                        => 'Vapaa',
        'free_times'                       => 'Vapaat',
        'working_times'                    => 'Työvuorosuunnittelu',
        'add_free_time'                    => 'Lisää vapaa',
        'start_at'                         => 'Aloitusaika',
        'end_at'                           => 'Lopetusaika',
        'date'                             => 'Päivä',
        'is_day_off'                       => 'Vapaapäivä',
        'workshifts'                       => 'Työvuoro',
        'workshift_planning'               => 'Työvuorosuunnittelu',
        'workshift_summary'                => 'Työvuorokooste',
        'from_date'                        => 'Alkaen',
        'to_date'                          => 'Päättyen',
        'weekday'                          => 'Viikonpäivä',
        'employee'                         => 'Työntekijä',
        'saturday_hours'                   => 'Lauantaitunnit',
        'sunday_hours'                     => 'Sununtaitunnit',
        'monthly_hours'                    => 'Kuukauden tunnit',
        'error'                            => [
        'freetime_overlapped_with_booking' => 'Vapaa-aika menossa varauksen päälle!'
        ],
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
        'back'             => 'Palaa takaisin',
        'book'             => 'Varaa',
        'empty_cart'       => 'Ostoskori on tyhjä',
        'start_time'       => 'Aloitusaika',
        'end_time'         => 'Päättymisaika',
        'booking_form'     => 'Varauslomake',
        'name'             => 'Nimi',
        'email'            => 'Sähköposti',
        'phone'            => 'Puhelinnumero',
        'checkout'         => 'Viimeistele varaus',
        'fi_version'       => 'Suomeksi',
        'en_version'       => 'Englanniksi',
        'sv_version'       => 'Ruotsiksi',
        'book'             => 'Varaa',
        'success'          => 'Varauksesi on vastaanotettu onnistuneesti! Kiitos varauksestasi.',
        'confirm'          => 'Vahvista varaus',
        'layout_2'         => [
            'select_service'      => 'Valitse palvelu ja päivämäärä',
            'select_service_type' => 'Valitse varauksen tyyppi',
            'services'            => 'Palvelut',
            'selected'            => 'Valitut palvelut',
            'extra_services'      => 'Lisäpalvelut',
            'employees'           => 'Työntekijä',
            'choose'              => 'Valitse päivämäärä',
            'unavailable'         => 'Ei saatavilla',
            'form'                => 'Varauslomake',
            'date'                => 'Päivämäärä',
            'price'               => 'Hinta',
            'name'                => 'Nimesi',
            'phone'               => 'Puhelin',
            'email'               => 'Sähköposti',
            'thanks'              => 'Kiitos varauksestasi!',
        ],
        'layout_3'         => [
            'select_service'  => 'Valitse palvelu',
            'select_employee' => 'Kenelle?',
            'select_datetime' => 'Valitse päivä &amp; aika',
            'contact'         => 'Yhteystietosi',
            'service'         => 'Palvelu',
            'employee'        => 'Henkilö',
            'name'            => 'Nimesi',
            'notes'           => 'Lisätiedot',
            'postcode'        => 'Postinumero',
            'empty'           => 'Valittuna päivänä ei vapaita aikoja',
            'payment_note'    => 'Vahvistuksen jälkeen sinut ohjatamaan maksamaan varaus',
            'confirm_service' => 'Vahvista varauksen palvelu',
        ]
    ],
    'options' => [
        'heading' => 'Asetukset',
        'updated' => 'Asetukset päivitetty onnistuneesti!',
        'general' => [
            'index'           => 'Yleinen',
            'heading'         => 'Yleisasetukset',
            'info'            => 'Täällä säädät asetuksesi kohdalleen.',
            'currency'        => 'Valuutta',
            'custom_status'   => 'Custom Status',
            'datetime_format' => 'Päiväyksen muoto',
            'date_format'     => 'Päivämäärä formaatti',
            'time_format'     => 'Aikamuoto',
            'layout'          => 'Varausnäkymä',
            'seo_url'         => 'SEO URLs',
            'timezone'        => 'Aikavyöhyke',
            'week_numbers'    => 'Näytä viikkonumerot',
            'week_start'      => 'Viikon ensimmäinen päivä?',
            'phone_number'    => 'Puhelinnumero',
            'business_name'   => 'Yrityksen nimi',
        ],
        'booking'                                        => [
            'heading'                                        => '',
            'info'                                           => '',
            'index'                                          => 'Varaukset',
            'booking_form'                                   => 'Varauslomake',
            'reminders'                                      => 'Muistutus',
            'confirmations'                                  => 'Vahvistus',
            'terms'                                          => 'Ehdot',
            'confirmed'                                      => 'Vahvistettu',
            'pending'                                        => 'Odottaa',
            'accept_bookings'                                => 'Hyväksy varauksia',
            'hide_prices'                                    => 'Piilota hinnat',
            'step'                                           => 'Askel',
            'bookable_date'                                  => 'Varattava päivä',
            'status_if_paid'                                 => 'Oletustila maksetuille varauksille',
            'status_if_not_paid'                             => 'Oletustila maksamattomille varauksille',
            'notes'                                          => 'Lisätiedot',
            'address'                                        => 'Osoite',
            'city'                                           => 'Kaupunki',
            'postcode'                                       => 'Postinumero',
            'country'                                        => 'Maa',
            'email'                                          => 'Sähköposti',
            'reminder_enable'                                => 'Muistutusviestit käytössä',
            'reminder_email_before'                          => 'Lähetä muistutus sähköpostilla',
            'reminder_subject'                               => 'Muistutussähköpostiviestin otsikko',
            'reminder_subject_default'                       => 'Muistutus varauksestasi',
            'reminder_body'                                  => 'Sähköpostimuistutuksen runko',
            'reminder_body_default'                          => $reminderBody,
            'reminder_sms_hours'                             => 'Lähetä muistutus tekstiviestillä',
            'reminder_sms_country_code'                      => 'SMS Maatunnus',
            'reminder_sms_message'                           => 'Tekstiviesti',
            'reminder_sms_message_default'                   => $reminderSmsMessage,
            'terms_url'                                      => 'URL osoite ehdoille',
            'terms_body'                                     => 'Varauksen ehtojen sisältö',
            'terms_body_default'                             => $termBody,
            'confirm_subject_client'                         => 'Asiakkaan vahvistuksen otsikko',
            'confirm_subject_client_default'                 => 'Kiitos varauksestasi',
            'confirm_tokens_client'                          => 'Viestin sisältö',
            'confirm_tokens_client_default'                  => $confirmTokensClient,
            'confirm_email_enable'                           => 'Sähköposti käytössä',
            'confirm_sms_enable'                             => 'SMS käytössä',
            'confirm_sms_country_code'                       => 'Koodi',
            'confirm_consumer_sms_message'                   => 'Asiakkaan tekstiviesti',
            'confirm_employee_sms_message'                   => 'Työntekijän tekstiviesti',
            'confirm_consumer_body_sms_message_default'      => $confirmConsumerMessage,
            'confirm_employee_body_sms_message_default'      => $confirmEmployeeMessage,
            'payment_subject_client'                         => 'Asiakkaan maksuvahvistuksen otsikko',
            'payment_subject_client_default'                 => 'Maksu vastaanotettu',
            'payment_tokens_client'                          => 'Viestin sisältö',
            'payment_tokens_client_default'                  => $paymentTokensClient,
            'confirm_subject_admin'                          => 'Hallintapaneelin maksuvahvistuksen otsikko',
            'confirm_subject_admin_default'                  => 'Uusi varaus on saapunut',
            'confirm_tokens_admin'                           => 'Viestin sisältö',
            'confirm_tokens_admin_default'                   => $confirmTokensAdmin,
            'payment_subject_admin'                          => 'Ylläpitäjän maksuvahvistuksen otsikko',
            'payment_subject_admin_default'                  => 'Uusi maksu vastaanotettu',
            'payment_tokens_admin'                           => 'Viestin sisältö',
            'payment_tokens_admin_default'                   => $paymentTokensAdmin,
            'confirm_subject_employee'                       => 'Työntekijän varauksen otsikko',
            'confirm_subject_employee_default'               => 'Uusi varaus on saapunut',
            'confirm_tokens_employee'                        => 'Viestin sisältö',
            'confirm_tokens_employee_default'                => $confirmTokensEmployee,
            'payment_subject_employee'                       => 'Työntekijän maksun otsikko',
            'payment_subject_employee_default'               => 'Uusi maksu vastaanotettu',
            'payment_tokens_employee'                        => 'Viestin sisältö',
            'payment_tokens_employee_default'                => $paymentTokensEmployee,
            'terms_enabled'                                  => 'Ehdot käytössä',
            'default_nat_service'                            => 'Default next available service',
            'min_distance'                                   => 'Min distance',//@todo
            'max_distance'                                   => 'Max distance',//@todo
        ],
        'style' => [
            'heading'                           => '',
            'info'                              => '',
            'index'                             => 'Tyyli',
            'style_logo'                        => 'Logo URL',
            'style_banner'                      => 'Banneri',
            'style_heading_color'               => 'Otsikon väri',
            'style_text_color'                  => 'Tekstin väri',
            'style_background'                  => 'Tausta',
            'style_custom_css'                  => 'Custom CSS',
            'style_main_color'                  => 'Pääväri',
            'style_heading_background'          => 'Otsikon tausta',
        ],
        'working_time' => [
            'index' => 'Kalenterinäkymä',
        ]
    ],
    'reports' => [
        'index'     => 'Raportit',
        'employees' => 'Varausmäärät',
        'services'  => 'Palveluvalikko',
        'generate'  => 'Luo',
        'start'     => 'Alkaa',
        'end'       => 'Päättyy',
        'booking'   => [
            'total'       => 'Kokonaisvaraukset',
            'confirmed'   => 'Vahvistetut varaukset',
            'unconfirmed' => 'Vahvistamattomat varaukset',
            'cancelled'   => 'Peruutetut varaukset',
        ],
        'statistics'=> 'Statistiikka',
        'monthly'   => 'Kuukausiraportti',
        'stat' => [
            'monthly'      => 'Kuukausikooste',
            'bookings'     => 'Varaukset',
            'revenue'      => 'Liikevaihto',
            'working_time' => 'Työaika',
            'booked_time'  => 'Varattu aika',
            'occupation'   => 'Täyttöaste',
        ]
    ],
    'crud' => [
        'bulk_confirm'   => 'Oletko varma että haluat toteuttaa tämän toiminnon?',
        'success_add'    => 'Tuote luotu onnistuneesti!',
        'success_edit'   => 'Tiedot päivitetty onnistuneesti!',
        'success_delete' => 'Tuote poistettu',
        'success_bulk'   => 'Toiminto toteutettu onnistuneesti!',
        'sortable'       => 'Järjestä sarakkeet uudelleen nappaamalla kiinni haluamastasi sarakkeesta, ja vetämällä sitä hiirellä ylös tai alas!',
    ],
];
