<?php

$cancelMessage = <<< HTML
Jūs atcēlāt rezervāciju {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Laipni lūdzam',
        'description'   => 'Jūs varat aplūkot visu sistēmā ievadīto darbinieku kalendārus. Zaļā krāsā atzīmētie laiki ir pieejami klientiem, pelēkā krāsā - nevar rezervēt.',
        'today'         => 'Šodien',
        'tomorrow'      => 'Rīt',
        'print'         => 'Izdrukāt',
        'refresh'       => 'Atsvaidzināt',
        'calendar'      => 'Kalendārs',
    ],
    'services' => [
        'heading'       => 'Pakalpojumi',
        'edit'          => 'Labot pakalpojumu',
        'custom_time'   => 'Brīvi izvēlēts laiks',
        'master_categories'  => 'Galvenā kategorija',
        'treatment_types'    => 'Apstrādes veids',
        'categories' => [
            'all'           => 'Visas kategorijas',
            'add'           => 'Pievienot jaunu kategoriju',
            'edit'          => 'Labot kategoriju',
            'name'          => 'Nosaukums',
            'description'   => 'Apraksts',
            'is_show_front' => 'Vai rādīt pirmajā lapā?',
            'no_services'   => 'Šajā kategorijā nav pakalpojumu',
            'availability'  => 'Pieejamība',
            'category_name' => 'Kategorijas nosaukums',
            'error'         => [
                'category_current_in_use' => 'Kategorija šobrīd tiek izmantota. Lūdzu, izdzēsiet visus saistītos pakalpojumus, pirms dzēst šo kategoriju.'
            ]
        ],
        'resources' => [
            'all'         => 'Visi resursi',
            'add'         => 'Pievienot jaunu resursu',
            'edit'        => 'Labot resursu',
            'name'        => 'Nosaukums',
            'description' => 'Apraksts',
            'quantity'    => 'Skaits',
        ],
        'rooms' => [
            'all'         => 'Visas telpas',
            'add'         => 'Pievienot telpu',
            'edit'        => 'Labot telpu',
            'name'        => 'Nosaukums',
            'description' => 'Apraksts',
        ],
        'extras' => [
            'all'         => 'Visi papildpakalpojumi',
            'add'         => 'Pievienot jaunu papildpakalpojumu',
            'edit'        => 'Labot papildpakalpojumu',
            'name'        => 'Nosaukums',
            'description' => 'Apraksts',
            'price'       => 'Cena',
            'length'      => 'Ilgums',
            'is_hidden'   => 'Hidden from frontend',
            'msg_extra'   => 'Vai jūs vēlaties arī rezervēt?',
        ],
        'all'          => 'Visi pakalpojumi',
        'index'        => 'Pakalpojumi',
        'desc'         => 'Šeit iespējams labot vai pievienot jaunus pakalpojumus',
        'add'          => 'Pievienot jaunus pakalpojumus',
        'add_desc'     => 'Pievienot jaunu darbinieku, pievienojot pakalpojuma nosaukumu un aprakstu. Jūs varat piesaistīt pakalpojumus darbiniekiem',
        'name'         => 'Nosaukums',
        'description'  => 'Apraksts',
        'price'        => 'Cena',
        'duration'     => 'Ilgums',
        'length'       => 'Kopā',
        'during'       => 'Ilgums',
        'before'       => 'Pirms',
        'after'        => 'Pēc',
        'total'        => 'Pakalpojuma laiks',
        'category'     => 'Kategorija',
        'is_active'    => 'Aktīva',
        'resource'     => 'Resurss',
        'room'         => 'Telpa',
        'extra'        => 'Papildpakalpojums',
        'employees'    => 'Darbinieki',
        'no_employees' => 'Nav darbinieka, ko izvēlēties',
        'no_name'      => 'Bez nosaukuma',
        'error'        => [
            'service_current_in_use' => 'Pakalpojumi šobrīd ir aktīvi. Lūdzu, izdzēsiet visas saistītās rezervācijas, pirms dzēst šo pakalpojumu.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Apstiprināta',
        'pending'           => 'Gaida apstiprinājumu',
        'cancelled'         => 'Atcelta',
        'arrived'           => 'Ieradās',
        'paid'              => 'Apmaksāta',
        'not_show_up'       => 'Klients neatnāca',
        'change_status'     => 'Mainīt rezervācijas statusu',
        'all'               => 'Rezervācijas',
        'add'               => 'Pievienot jaunu rezervāciju',
        'invoices'          => 'Rēķini',
        'consumer'          => 'Klients',
        'date'              => 'Datums',
        'total'             => 'Kopā',
        'start_at'          => 'Sākums plkst.',
        'end_at'            => 'Beigas plkst.',
        'status'            => 'Statuss',
        'total_price'       => 'Cena',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Pievienot pakalpojumu',
        'booking_info'      => 'Rezervācijas dati',
        'booking_id'        => 'Rezervācijas ID',
        'categories'        => 'Kategorijas',
        'services'          => 'Pakalpojumi',
        'service_time'      => 'Pakalpojumu laiks',
        'modify_time'       => 'Mainīt laiku',
        'plustime'          => 'Plustime',
        'total_length'      => 'Kopējais laiks',
        'modify_duration'   => 'Mainīt ilgumu',
        'employee'          => 'Darbinieks',
        'notes'             => 'Piezīmes',
        'first_name'        => 'Vārds',
        'last_name'         => 'Uzvārds',
        'email'             => 'E-pasts',
        'phone'             => 'Tālrunis',
        'address'           => 'Adrese',
        'city'              => 'Pilsēta',
        'postcode'          => 'Pasta indekss',
        'country'           => 'Valsts',
        'confirm_booking'   => 'Apstiprināt rezervāciju',
        'service_employee'  => 'Darbinieks',
        'date_time'         => 'Datums',
        'price'             => 'Cena',
        'extra_service'     => 'Papildpakalpojumi',
        'keyword'           => 'Atslēgvārds',
        'edit'              => 'Labot rezervācijas',
        'terms'             => 'Noteikumi',
        'terms_agree'       => 'Izlasīju rezervāciju noteikumus un piekrītu tiem.',
        'terms_of_agreement'=> 'Piekrītu <a {terms_class}>lietošanas noteikumiem</a>',
        'cancel_message'    => $cancelMessage,
        'cancel_confirm'    => 'Vai tiešām vēlaties atcelt šo rezervāciju',
        'modify_booking'    => 'Labot rezervāciju',
        'reschedule'        => 'Pārplānot',
        'confirm_reschedule'=> 'Apstiprināt mainīto rezervācijas laiku',
        'cancel_reschedule' => 'Atcelt mainīto rezervācijas laiku',
        'own_customer'      => 'Mūsu klients',
        'request_employee'  => 'Pieprasīt konkrētu darbinieku',
        'deposit'           => 'Depozīta maksājuma',
        'search_placeholder'=> 'Meklēt patērētājs',
        'error'             => [
            'add_overlapped_booking'      => 'Rezervācijas laiki pārklājas!',
            'insufficient_slots'          => 'Kalendārā nav pieejams izvēlētajai rezervācijai atbilstošs brīvais laiks!',
            'invalid_consumer_info'       => 'Klienta datus nevarēja saglabāt',
            'terms'                       => 'Jums jāpiekrīt rezervāciju noteikumiem.',
            'service_empty'               => 'Lūdzu, norādiet pakalpojumu un rezervācijas laiku!',
            'unknown'                     => 'Kaut kas nogāja greizi!',
            'exceed_current_day'          => 'Rezervācijas beigu laiks nevar būt agrāks par sākumu',
            'overllapped_with_freetime'   => 'Rezervācijas laiks pārklājas ar darbinieka atpūtas laiku',
            'empty_total_time'            => 'Rezervācijas kopējam laiks nedrīkst būt mazāks par 1 minūti',
            'uuid_notfound'               => 'Rezervācijas ID nav atrast',
            'not_enough_slots'            => 'Nepietiekams rezervācijas laiks vai tas pārklājas ar citu rezervāciju.',
            'employee_not_servable'       => 'Darbinieks nepiedāvā pakalpojumu, kuru vēlaties rezervēt.',
            'id_not_found'                => 'Rezervācija nav atrasta',
            'start_time'                  => 'Nepareizs rezervācijas sākuma laiks.',
            'service_time_invalid'        => 'Pakalpojuma laiks rezervācijai nav atrasts.',
            'overlapped_with_freetime'    => 'Rezervācijas laiks pārklājas ar darbinieka atpūtas laiku',
            'reschedule_single_only'      => 'Vairāku pakalpojumu rezervācijai nav iespējams mainīt laiku.',
            'reschedule_unbooked_extra'   => 'Rezervācijas laiku nevar mainīt',
            'not_enough_resources'        => 'Pieprasītie resursi nav pieejami!',
            'not_enough_rooms'            => 'Telpu skaits nav pieejams!',
            'empty_start_time'            => 'Rezervācijas sākuma laiks nedrīkst būt tukšs',
            'booking_not_found'           => 'Rezervācija nav atrasta!',
            'past_booking'                => 'Rezervācija ar atpakaļejošu datumu nav iespējama!',
            'delete_last_booking_service' => 'Pēdējās rezervācijas pakalpojumu nevar izdzēst. ',
            'before_min_distance'         => 'Rezervācija, kas agrāka par min distance dienām, nav iespējama',
            'after_max_distance'          => 'Rezervācija, kas vēlāka par max distance dienām, nav iespējama',
            'missing_services'            => 'Pievienot pakalpojumus, lai turpinātu',
        ],
        'warning'      => [
            'existing_user'   => 'Sistēmā jau ir reģistrēts lietotājs ar šo e-pasta adresi. Vai vēlaties izmantot šos datus?',
        ],
    ],
    'employees' => [
        'all'                              => 'Darbinieki',
        'add'                              => 'Pievienot jaunu darbinieku',
        'edit'                             => 'Labot darbinieka datus',
        'name'                             => 'Vārds',
        'phone'                            => 'Tālrunis',
        'email'                            => 'E-pasts',
        'description'                      => 'Apraksts',
        'is_subscribed_email'              => 'Saņemt informāciju e-pastā?',
        'is_subscribed_sms'                => 'Saņemt informāciju SMS?',
        'is_received_calendar_invitation'  => 'Vai saņem kalendāra uzaicinājumus?',
        'services'                         => 'Pakalpojumi',
        'status'                           => 'Statuss',
        'is_active'                        => 'Aktivizācija',
        'avatar'                           => 'Attēls',
        'default_time'                     => 'Definētais laiks',
        'custom_time'                      => 'Brīvi izvēlēts laiks',
        'days_of_week'                     => 'Nedēļas dienas',
        'start_time'                       => 'Sākums',
        'end_time'                         => 'Beigas',
        'date_range'                       => 'Date range',
        'day_off'                          => 'Brīvdiena?',
        'confirm'                          => [
            'delete_freetime' => 'Vai tiešām vēlaties dzēst izvēlēto brīvo laiku no kalendāra?'
        ],
        'free_time'                        => 'Pieejams laiks',
        'free_times'                       => 'Pieejamie laiki',
        'free_time_type'                   => 'Pieejamie laiks tips',
        'working_free_time'                => 'Darba',
        'personal_free_time'               => 'Personisks',
        'working_times'                    => 'Darba laiks',
        'add_free_time'                    => 'Pievienot savu brīvo laiku',
        'start_at'                         => 'Sākums plkst.',
        'end_at'                           => 'Beigas plkst.',
        'date'                             => 'Datums',
        'is_day_off'                       => 'Brīvdiena',
        'workshifts'                       => 'Darba maiņas',
        'workshift_planning'               => 'Darba maiņu plānošana',
        'workshift_summary'                => 'Darba maiņu apraksts',
        'from_date'                        => 'No datuma',
        'to_date'                          => 'Līdz datumam',
        'weekday'                          => 'Darba diena',
        'employee'                         => 'Darbinieks',
        'freelancer'                       => 'Freelancer',//@todoo
        'business_id'                      => 'Business ID',//@todoo
        'account'                          => 'Account',//@todoo
        'activation'                       => 'Activation',//@todoo
        'saturday_hours'                   => 'Darba laiks sestdienā',
        'sunday_hours'                     => 'Darba laiks svētdienā',
        'monthly_hours'                    => 'Mēneša darba laiks',
        'weekly_hours'                     => 'Nedēļas stundas',
        'error'                            => [
            'freetime_overlapped_with_booking' => 'Brīvdienas pārklājas ar rezervāciju (-ām)',
            'freetime_overlapped_with_others'  => 'Freetime is overlapped with other freetime(s)',//@todo
            'empty_employee_ids'               => 'Please select at least one employee!',
        ],
    ],
    'embed' => [
        'heading'          => 'Virsraksts',
        'description'      => 'Teksts',
        'embed'            => 'Ievietot',
        'preview'          => 'Priekšskats',
        'back_to_services' => 'Atgriezties pie pakalpojumiem',
        'select_date'      => 'Izvēlēties datumu',
        'select_service'   => 'Izvēlēties pakalpojumu',
        'guide_text'       => 'Izvēlieties pieejamo laiku',
        'make_appointment' => 'Pierakstīties',
        'cancel'           => 'Atcelt',
        'empty_cart'       => 'Iepirkumu grozs ir tukšs',
        'start_time'       => 'Sākums',
        'end_time'         => 'Beigas',
        'booking_form'     => 'Rezervācijas forma',
        'name'             => 'Vārds',
        'email'            => 'E-pasts',
        'phone'            => 'Tālruņa numurs',
        'checkout'         => 'Izrakstīties',
        'fi_version'       => 'Finnish',
        'en_version'       => 'English',
        'sv_version'       => 'Swedish',
        'book'             => 'Apstiprināt rezervāciju',
        'loading'          => 'Now loading&hellip;',
        'success'          => 'Paldies par rezervāciju! Rezervācijas apstiprinājums tiks nosūtīts uz Jūsu norādīto e-pastu.',
        'success_line1'    => '<h2>Paldies!</h2>',
        'success_line2'    => '<h3>Jūsu rezervācija ir veiksmīgi pievienota</h3>',
        'success_line3'    => '<h3>Jūs automātiski tiksiet novirzīti uz sākuma lapu pēc <span id="as-counter">10</span> sekundēm.</h3>',
        'thankyou_line1'   => 'Paldies, Jūsu rezervācija ir pieņemta!',
        'thankyou_line2'   => 'Par pakalpojumu būs iespējams norēķināties salonā uz vietas',
        'confirm'          => 'Apstiprināt rezervāciju',
        'layout_2'         => [
            'select_service'      => 'Izvēlieties pakalpojumu un datumu',
            'select_service_type' => 'Izvēlieties pakalpojuma kategoriju',
            'services'            => 'Pakalpojumi',
            'selected'            => 'Izvēlētie pakalpojumi',
            'extra_services'      => 'Papildpakalpojumi',
            'employees'           => 'Darbinieks',
            'choose'              => 'Izvēlieties datumu',
            'unavailable'         => 'Brīvi laiki nav pieejami',
            'form'                => 'Kontaktinformācija',
            'date'                => 'Datums',
            'price'               => 'Cena',
            'name'                => 'Vārds',
            'phone'               => 'Tālrunis',
            'email'               => 'E-pasts',
            'thanks'              => 'Paldies par rezervāciju!',
        ],
        'layout_3'         => [
            'select_service'  => 'Izvēlieties pakalpojumu',
            'select_employee' => 'Izvēlieties darbinieku',
            'select_datetime' => 'Izvēlieties datumu un laiku',
            'contact'         => 'Kontaktinformācija',
            'service'         => 'Pakalpojums',
            'employee'        => 'Darbinieks',
            'name'            => 'Jūsu vārds',
            'notes'           => 'Piezīmes',
            'postcode'        => 'Pasta indekss',
            'empty'           => 'Izvēlētajā dienā brīvs laiks nav pieejams.',
            'payment_note'    => 'Apstiprinot rezervāciju, atvērsies lapa, kurā veikt apmaksu.',
            'confirm_service' => 'Apstiprināt rezervāciju',
            'heading_line'    => 'Rezervēt laiku',
        ],
        'cp' => [
            'heading' => 'Rezervējiet pakalpojumu',
            'select' => 'Izvēlēties',
            'sg_service' => 'Pakalpojums',
            'pl_service' => 'Pakalpojumi',
            'employee' => 'Darbinieks',
            'time' => 'Laiks',
            'salon' => 'Salons',
            'price' => 'Cena',
            'service' => 'Pakalpojums',
            'details' => 'Rezervācijas informācija',
            'go_back' => 'Atgriezties',
            'how_to_pay' => 'Kā jūs vēlaties maksāt?',
            'almost_done' => 'Jūsu rezervācija ir gandrīz pabeigta',
            'first_employee' => 'Pirmais darbinieks pieejams',
            'coupon_code' => 'Kupona kods',
        ]
    ],
     'options' => [
        'heading'                    => 'Iestatījumi',
        'updated'                    => 'Iestatījumi mainīti',
        'invalid_style_external_css' => 'Invalid external css file!',
        'general' => [
            'index'           => 'Vispārīgi',
            'heading'         => 'Vispārīgi iestatījumi',
            'info'            => 'Izveidot savus iestatījumus',
            'currency'        => 'Valūta',
            'custom_status'   => 'Brīvi izvēlēts statuss',
            'datetime_format' => 'Laika formāts',
            'date_format'     => 'Datuma formāts',
            'time_format'     => 'Laika formāts',
            'layout'          => 'Izkārtojums',
            'seo_url'         => 'SEO URLs',
            'timezone'        => 'Laika zona',
            'week_numbers'    => 'Nedēļu numerācija',
            'week_start'      => 'Vai rādīt nedēļu numerāciju?',
            'phone_number'    => 'Mobilais tālrunis',
            'business_name'   => 'Biznesa nosaukums',
        ],
        'booking'   => [
            'heading'                                        => '',
            'info'                                           => '',
            'disable_booking'                                => 'Atslēgt rezervēšanas kalendāru',
            'index'                                          => 'Rezervācijas',
            'booking_form'                                   => 'Rezervāciju forma',
            'reminders'                                      => 'Atgādinājums',
            'confirmations'                                  => 'Apstiprinājums',
            'terms'                                          => 'Noteikumi',
            'confirmed'                                      => 'Apstiprināts',
            'pending'                                        => 'Gaida apstiprinājumu',
            'accept_bookings'                                => 'Apstiprināt rezervācijas',
            'hide_prices'                                    => 'Nerādīt cenas',
            'step'                                           => 'Solis',
            'bookable_date'                                  => 'Pieejams datums',
            'status_if_paid'                                 => 'Noklusējuma režīms apmaksātām rezervācijām',
            'status_if_not_paid'                             => 'Noklusējuma režīms neapmaksātām rezervācijām',
            'notes'                                          => 'Piezīmes',
            'address'                                        => 'Adrese',
            'city'                                           => 'Pilsēta',
            'postcode'                                       => 'Pasta indekss',
            'country'                                        => 'Valsts',
            'email'                                          => 'E-pasts',
            'reminder_enable'                                => 'Iespējot paziņojumus',
            'reminder_email_before'                          => 'Nosūtīt atgādinājuma e-pastu',
            'reminder_subject'                               => 'Atgādinājuma e-pasta tēmas nosaukums',
            'reminder_subject_default'                       => 'Muistutus varauksestasi',
            'reminder_body'                                  => 'Atgādinājuma e-pasta teksts',
            'reminder_sms_hours'                             => 'Nosūtīt atgādinājuma SMS',
            'reminder_sms_country_code'                      => 'SMS valsts kods',
            'reminder_sms_message'                           => 'Īsziņas teksts',
            'terms_url'                                      => 'Rezervācijas noteikumu URL',
            'terms_body'                                     => 'Rezervācijas noteikumu teksts',
            'confirm_subject_client'                         => 'Klienta apstiprinājuma uzruna',
            'confirm_tokens_client'                          => 'E-pasta teksts',
            'confirm_email_enable'                           => 'Saņemt e-pastu',
            'confirm_sms_enable'                             => 'Saņemt SMS',
            'confirm_sms_country_code'                       => 'Kods',
            'confirm_consumer_sms_message'                   => 'Klienta SMS',
            'confirm_employee_sms_message'                   => 'Darbinieka SMS',
            'confirm_subject_employee'                       => 'Darbinieka apstiprinājuma uzruna',
            'confirm_tokens_employee'                        => 'E-pasta teksts',
            'terms_enabled'                                  => 'Iespējot noteikumus',
            'default_nat_service'                            => 'Nākamais pieejamais pakalpojums pēc noklusējuma',
            'show_quick_workshift_selection'                 => 'Show on calendar workshift selection',
            'min_distance'                                   => 'Min attālums (stundas)',
            'max_distance'                                   => 'Max attālums (dienas)',
            'auto_select_employee'                           => 'Automātiski izvēlēties darbinieku',
            'auto_expand_all_categories'                     => 'Automātiksi aizpildīt visas kategorijas',
            'show_employee_request'                          => 'Laut izvēlēties darbinieku',
            'factor'                                         => 'Factor'
        ],
        'style' => [
            'heading'                           => '',
            'info'                              => '',
            'index'                             => 'Front-end style',
            'style_logo'                        => 'Logo URL',
            'style_banner'                      => 'Banner',
            'style_heading_color'               => 'Heading color',
            'style_text_color'                  => 'Text color',
            'style_background'                  => 'Background',
            'style_custom_css'                  => 'Custom CSS',
            'style_external_css'                => 'External CSS Link',
            'style_main_color'                  => 'Main color',
            'style_heading_background'          => 'Heading background',
        ],
        'working_time' => [
            'index' => 'Kalendāra skats',
        ],
        'discount' => [
            'discount'            => 'Discount',
            'last-minute'         => 'Last minute discount',
            'business-hour'       => 'business hour',
            'business-hours'      => 'business hours',
            'full-price'          => 'Full price',
            'afternoon_starts_at' => 'Afternoon starts at',
            'evening_starts_at'   => 'Evening starts at',
            'is_active'           => 'Is Enabled',
            'before'              => 'Before',
            'error' => [
                'evening_starts_before_afternoon' => 'Afternoon must starts before evening starts'// @todo
            ],
        ]
    ],
    'reports' => [
        'index'     => 'Atskaites',
        'employees' => 'Darbinieki',
        'services'  => 'Pakalpojumi',
        'generate'  => 'Ģenerēt',
        'start'     => 'Sākuma datums',
        'end'       => 'Beigu datums',
        'booking'   => [
            'total'       => 'Kopā rezervācijas',
            'confirmed'   => 'Apstiprināts rezervācijas',
            'unconfirmed' => 'Neapstiprinātas rezervācijas',
            'cancelled'   => 'Atceltās rezervācijas',
            'inhouse'     => 'Rezervācijas',
            'front-end'   => 'Rezervācijas no Jūsu mājas lapas',
        ],
        'statistics'=> 'Statistika',
        'monthly'   => 'Mēneša atskaite',
        'stat' => [
            'monthly'      => 'Mēneša pārskats',
            'bookings'     => 'Rezervācijas',
            'revenue'      => 'Ienākumi',
            'working_time' => 'Darba laiks',
            'booked_time'  => 'Rezervētais laiks',
            'occupation'   => 'Nodarbinātība %'
        ]
    ],
    'crud' => [
        'bulk_confirm'   => 'Vai tiešām vēlaties veikt šo darbību?',
        'success_add'    => 'Ieraksts veiksmīgi izveidots',
        'success_edit'   => 'Dati veiksmīgi atjaunināti.',
        'success_delete' => 'Ieraksts veiksmīgi izdzēsts.',
        'success_bulk'   => 'Ieraksts veiksmīgi izdzēsts.',
        'sortable'       => 'Velciet ar peli, lai pārkārtotu secību',
    ],
    'nothing_selected' => 'Izvēlēties',
];
