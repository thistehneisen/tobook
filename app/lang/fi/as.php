<?php

$cancelMessage = <<< HTML
Varaus ID:{BookingID} peruutettu onnistuneesti.

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Etusivu (asiakastukemme: yritys@varaa.com / puh. 045 146 3755)',
        'description'   => 'Näkymässä näet kaikkien työntekijöiden kalenterin. Kuluttajille varattavat ajat vihreällä. Voit tehdä halutessasi varauksia myös harmaalle alueelle joka näkyy kuluttajille suljettuna.',
        'today'         => 'Tänään',
        'tomorrow'      => 'Huomenna',
        'print'         => 'Tulosta',
        'refresh'       => 'Päivitä',
        'calendar'      => 'Kalenteri',
    ],
    'services' => [
        'heading'            => 'Palvelut',
        'edit'               => 'Muokkaa palvelua',
        'custom_time'        => 'Palvelukestot',
        'master_categories'  => 'Kategoriatyyppi',
        'treatment_types'    => 'Palvelutyyppi',
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
            'is_hidden'   => 'Piilossa asiakkailta',
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
        'is_discount_included' => 'Mukana tarjouksissa',
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
        'total_length'      => 'Kokonaiskesto',
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
        'deposit'           => 'Varausmaksu',
        'search_placeholder'=> 'Asiakashaku',
        'cancel_email_title'=> 'Varaus on peruttu onnistuneesti.',
        'cancel_email_body' => 'Asiakas on peruuttanut seuraavan varauksen: <br> %s',
        'confirmation_settings'=> 'Viestiasetukset',
        'confirmation_email'=> 'Sähköpostivahvistus',
        'confirmation_sms'  => 'Tekstiviestivahvistus',
        'reminder_email'    => 'Sähköpostimuistutus',
        'reminder_sms'      => 'Tekstiviestimuistutus',
        'reminder_sms_before'   => 'Lähetä SMS ennen',
        'reminder_email_before' => 'Lähetä sähköposti ennen',
        'reminder_sms_time_unit'   => 'Tuntia/päivää',
        'reminder_email_time_unit' => 'Tuntia/päivää',
        'price_range'              => 'Hintahaarukka',
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
            'delete_last_booking_service' => 'Et voi poistaa varauksen viimeistä palvelua.',
            'before_min_distance'         => 'Valitettavasti et voi tehdä varausta näin lähelle',
            'after_max_distance'          => 'Olet tekemässä varauksen liian kauas tulevaisuuteen',
            'missing_services'            => 'Muista lisätä varaukseen palvelu!<br>(Klikkaa \'Lisää\')',
            'invalid_reminder_time'       => 'Muistutusviestin lähetysajankohta ei voi olla menneisyydessä!',
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
        'is_received_calendar_invitation'  => 'Kalenterikutsu käytössä',
        'services'                         => 'Palvelut',
        'status'                           => 'Tila',
        'is_active'                        => 'Aktiivinen',
        'avatar'                           => 'Kuva',
        'default_time'                     => 'Oletustyöajat',
        'custom_time'                      => 'Räätälöidyt ajat',
        'days_of_week'                     => 'Viikonpäivä',
        'start_time'                       => 'Aloitusaika',
        'end_time'                         => 'Lopetusaika',
        'date_range'                       => 'Päivämäärät',
        'day_off'                          => 'Vapaapäivä',
        'confirm'                          => [
        'delete_freetime'                  => 'Oletko varma, että haluat poistaa valitun vapaa-ajan kalenterista?'
        ],
        'free_time'                        => 'Vapaa',
        'free_times'                       => 'Vapaat',
        'free_time_type'                   => 'Vapaan tyyppi',
        'working_free_time'                => 'Työvapaa',
        'personal_free_time'               => 'Henk. koht. vapaa',
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
        'freelancer'                       => 'Yrittäjä',
        'business_id'                      => 'Y-tunnus',
        'account'                          => 'Tilinumero',
        'activation'                       => 'Aktivointi',
        'saturday_hours'                   => 'Lauantaitunnit',
        'sunday_hours'                     => 'Sununtaitunnit',
        'monthly_hours'                    => 'Kuukauden tunnit',
        'weekly_hours'                     => 'Viikkotunnit',
        'error'                            => [
            'freetime_overlapped_with_booking' => 'Vapaa-aika menossa varauksen päälle!',
            'freetime_overlapped_with_others'  => 'Freetime is overlapped with other freetime(s)',//@todo
            'empty_employee_ids'               => 'Please select at least one employee!',
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
        'loading'          => 'Ladataan&hellip;',
        'success'          => 'Kiitos varauksestasi! Sinut ohjataan takaisin alkuun <span id="as-counter">10</span> -sekunnin kuluttua.',
        'success_line1'    => '<h2>Kiitos!</h2>',
        'success_line2'    => '<h3>Varauksesi luotiin onnistuneesti.</h3>',
        'success_line3'    => '<h3>Sinut ohjataan takaisin alkuun <span id="as-counter">10</span> -sekunnin kuluttua.</h3>',
        'confirm'          => 'Vahvista varaus',
        'layout_2'         => [
            'select_service'      => 'Valitse palvelu ja päivämäärä',
            'select_service_type' => 'Valitse varauksen tyyppi',
            'services'            => 'Palvelut',
            'selected'            => 'Valitut palvelut',
            'extra_services'      => 'Lisäpalvelut',
            'employees'           => 'Työntekijä',
            'choose'              => 'Valitse päivämäärä',
            'unavailable'         => 'Aikoja ei saatavilla',
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
            'heading_line'    => 'Varaa aika',
        ],
        'cp' => [
            'heading' => 'Varaa aika',
            'select' => 'Valitse',
            'sg_service' => 'palvelu',
            'pl_service' => 'palvelua',
            'employee' => 'Työntekijä',
            'time' => 'Ajankohta',
            'salon' => 'Yritys',
            'price' => 'Hinta',
            'service' => 'Palvelu',
            'details' => 'Varauksen tiedot',
            'go_back' => 'Takaisin',
            'how_to_pay' => 'Valitse alta varauksen maksutapa',
            'almost_done' => 'Varauksesi on melkein valmis..',
            'first_employee' => 'Kuka tahansa tekijä',
        ],
        'receipt' => [
            'thanks' => 'Kiitos kun varasit Varaa.comin kautta!',
            'paid' => 'Maksettu',
            'invoice' => 'Lasku nro #',
            'vat' => 'ALV 24%',
            'total' => 'Yht.',
            'questions' => 'Kysymyksiä? Laita viestiä ',
            'company' => 'Y: 2536946-1 - yritys@varaa.com - 045 146 3755 <br>Varaa.com Digital Oy - Pasilan Puistotie 10B, 00240 Helsinki',
        ],
    ],
    'options' => [
        'heading'                    => 'Asetukset',
        'updated'                    => 'Asetukset päivitetty onnistuneesti!',
        'invalid_data'               => 'Virhe - kentissä vääränlaista / väärää tietoa',
        'invalid_style_external_css' => 'Invalid external css file!',
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
            'index'                                          => 'Yleiset',
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
            'reminder_body'                                  => 'Sähköpostimuistutuksen runko',
            'reminder_sms_hours'                             => 'Lähetä muistutus tekstiviestillä',
            'reminder_sms_country_code'                      => 'SMS Maatunnus',
            'reminder_sms_message'                           => 'Tekstiviesti',
            'terms_url'                                      => 'URL osoite ehdoille',
            'terms_body'                                     => 'Varauksen ehtojen sisältö',
            'confirm_subject_client'                         => 'Asiakkaan vahvistuksen otsikko',
            'confirm_tokens_client'                          => 'Viestin sisältö',
            'confirm_email_enable'                           => 'Sähköposti käytössä',
            'confirm_sms_enable'                             => 'SMS käytössä',
            'confirm_sms_country_code'                       => 'Koodi',
            'confirm_consumer_sms_message'                   => 'Asiakkaan tekstiviesti',
            'confirm_employee_sms_message'                   => 'Työntekijän tekstiviesti',
            'confirm_subject_employee'                       => 'Työntekijän varauksen otsikko',
            'confirm_tokens_employee'                        => 'Viestin sisältö',
            'terms_enabled'                                  => 'Ehdot käytössä',
            'default_nat_service'                            => 'Default next available service',
            'show_quick_workshift_selection'                 => 'Työvuorojen pikavalitsin kalenterinäkymässä',
            'min_distance'                                   => 'Varauksien varoaika (/h)',
            'max_distance'                                   => 'Maksimietäisyys varauksille (/vrk)',
            'auto_select_employee'                           => 'Työntekijän automaattivalitsin',
            'auto_expand_all_categories'                     => 'Avaa varausnäkymän kategoriat automaattisesti',
            'show_employee_request'                          => '\'Oma asiakas\' painike käytössä',
            'factor'                                         => 'Varausnäkymän työntekijämuuttuja',
            'hide_empty_workshift_employees'                 => 'Piilota työntekijän sarake jos ei työvuoroa',
            'announcements'                                  => 'Asiakastiedote',
            'announcement_enable'                            => 'Asiakastiedote käytössä',
            'announcement_content'                           => 'Tiedotteen sisältö',
            'cancel_before_limit'                            => 'Peruutus viimeistään ennen (tunteina)',
            'confirmation_sms'                               => 'Tekstiviestivahvistus',
            'confirmation_email'                             => 'Sähköpostivahvistus',
            'confirmation_reminder'                          => 'Muistutusviestit',
            'reminder_sms'                                   => 'Tekstiviestimuistutus',
            'reminder_email'                                 => 'Sähköpostimuistutus',
            'reminder_sms_before'                            => 'Lähetä SMS muistutus',
            'reminder_email_before'                          => 'Lähetä email muistutus',
            'reminder_sms_unit'                              => 'SMS muistutuksen aikayksikkö',
            'reminder_email_unit'                            => 'Email muistutuksen aikayksikkö',
            'reminder_sms_time_unit'                         => 'Tuntia / päivää ennen',
            'reminder_email_time_unit'                       => 'Tuntia / päivää ennen',
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
            'style_external_css'                => 'External CSS Link',
            'style_main_color'                  => 'Pääväri',
            'style_heading_background'          => 'Otsikon tausta',
        ],
        'working_time' => [
            'index' => 'Kalenterinäkymä',
        ],
        'discount' => [
            'discount'            => 'Tarjoukset',
            'last-minute'         => 'Äkkilähdöt',
            'business-hour'       => 'tunti ennen',
            'business-hours'      => 'tuntia ennen',
            'full-price'          => 'Täysi hinta',
            'afternoon_starts_at' => 'Päivä alkaa klo',
            'evening_starts_at'   => 'Ilta alkaa klo',
            'is_active'           => 'Käytössä',
            'before'              => 'Varoaika',
            'error' => [
                'evening_starts_before_afternoon' => 'Ilta ei voi alkaa ennen päivää.'
            ],
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
            'inhouse'     => 'Varaa.comin kautta tulleet',
            'front-end'   => 'Netistä varanneet',
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
    'review' => [
        'review-form'  => 'Arvostele yritys',
        'review-sent'  => 'Arostelu jätetty onnistuneesti!',
        'all'          => 'Kaikki arvostelut',
        'avg_rating'   => 'Keskiarvo',
        'comment'      => 'Palaute',
        'environment'  => 'Viihtyvyys',
        'edit'         => 'Muokkaa',
        'name'         => 'Nimi',
        'leave_review' => 'Arvostele yritys',
        'price_ratio'  => 'Hinta-laatu',
        'service'      => 'Palvelu',
        'status'       => 'Status',
        'venue_rating' => 'Arvosana',
        'init'         => 'Init',
        'approved'     => 'Hyväksytty',
        'rejected'     => 'Hylätty',
        'approve'      => 'Hyväksy',
        'reject'       => 'Hylkää',
    ],
    'nothing_selected' => 'Valitse',
    'reminder' => [
        'sms_reminder_content' => 'Muistathan varauksesi: {Services}',
        'email_reminder_content' => 'Muistathan varauksesi: {Services}, {Address}',
    ]
];
