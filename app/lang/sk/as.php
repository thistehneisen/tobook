<?php

$cancelMessage = <<< HTML
You have cancelled the booking {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Vitajte',
        'description'   => 'Môžete si zobraziť kalendár všetkých vytvorených zamestnancov. Zelený čas je možné rezervovať, šedý čas nie je možné rezervovať.',
        'today'         => 'Dnes',
        'tomorrow'      => 'Zajtra',
        'print'         => 'Tlačiť',
        'calendar'      => 'Kalendar',
    ],
    'services' => [
        'heading'            => 'Služby',
        'edit'               => 'Upraviť služby',
        'custom_time'        => 'Užívateľský čas',
        'master_categories'  => 'Hlavné kategórie',
        'treatment_types'    => 'Treatment types',
        'categories' => [
            'all'           => 'Všetky kategórie',
            'add'           => 'Pridať novú kategóriu',
            'edit'          => 'Upraviť kategóriu',
            'name'          => 'Meno',
            'description'   => 'Popis',
            'is_show_front' => 'Je zobrazená na úvodnej stránke?',
            'no_services'   => 'Pre túto kategóriu nie sú žiadné služby',
            'availability'  => 'Dostupnosť',
            'category_name' => 'Meno kategórie',
            'error'         => [
                'category_current_in_use' => 'Kategória sa aktuálne používa. Prosím, vymažte najprv všetky súvisiace služby pre vymazanie kategórie.'
            ]
        ],
        'resources' => [
            'all'         => 'Všetky zdroje',
            'add'         => 'Pridať nový zdroj',
            'edit'        => 'Upraviť zdroj',
            'name'        => 'Meno',
            'description' => 'Popis',
            'quantity'    => 'Množstvo',
        ],
        'rooms' => [
            'all'         => 'Všetky miestnosti',
            'add'         => 'Pridať miestnosť',
            'edit'        => 'Upraviť miestnosť',
            'name'        => 'Meno',
            'description' => 'Popis',
        ],
        'extras' => [
            'all'         => 'Všetky extra služby',
            'add'         => 'Pridať novú extra službu',
            'edit'        => 'Upraviť extra službu',
            'name'        => 'Meno',
            'description' => 'Popis',
            'price'       => 'Cena',
            'length'      => 'Dĺžka',
            'msg_extra'   => 'Tiež si to chcete rezervovať?',
        ],
        'all'          => 'Všetky služby',
        'index'        => 'Služby',
        'desc'         => 'Tu môžete pridať alebo upravovať služby',
        'add'          => 'Pridať novú službu',
        'add_desc'     => 'Pridať nového zamestnanca, tak že pridáte službu a jej popis, taktiež môžete spojiť služby s zamestnancom',
        'name'         => 'Meno',
        'description'  => 'Popis',
        'price'        => 'Cena',
        'duration'     => 'Trvanie',
        'length'       => 'Spolu',
        'during'       => 'Trvanie',
        'before'       => 'Predtým',
        'after'        => 'Potom',
        'total'        => 'Spolu',
        'category'     => 'Kategória',
        'is_active'    => 'Aktívna',
        'resource'     => 'Zdroj',
        'room'         => 'Miestnosť',
        'extra'        => 'Extra služby',
        'employees'    => 'Zamestnanci',
        'no_employees' => 'Nemáte k dispozícií žiadnych zamestnancov',
        'no_name'      => 'Bez mena',
        'error'        => [
            'service_current_in_use' => 'Služba je aktuálne používaná. Prosím, pred vymazaním služby vymažte všetky súvisiace rezervácie.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Potvrdené',
        'pending'           => 'Čakajúce',
        'cancelled'         => 'Zrušené',
        'arrived'           => 'Arrived',
        'paid'              => 'Zaplatené',
        'not_show_up'       => 'Zákazník neprišiel',
        'change_status'     => 'Zmeniť status rezervácie',
        'all'               => 'Rezervácie',
        'add'               => 'Pridať rezerváciu',
        'invoices'          => 'Faktúry',
        'consumer'          => 'Zákaznik',
        'date'              => 'Dátum',
        'total'             => 'Spolu',
        'start_at'          => 'Začal',
        'end_at'            => 'Skončil',
        'status'            => 'Status',
        'total_price'       => 'Cena',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Pridať službu',
        'booking_info'      => 'Info o rezervácií',
        'booking_id'        => 'ID rezervácie',
        'categories'        => 'Kategórie',
        'services'          => 'Služby',
        'service_time'      => 'Čas služby',
        'modify_time'       => 'Upraviť čas',
        'plustime'          => 'Plustime',
        'total_length'      => 'Celková dĺžka',
        'modify_duration'   => 'Upraviť dĺžku',
        'employee'          => 'Zamestnanec',
        'notes'             => 'Poznámky',
        'first_name'        => 'Meno',
        'last_name'         => 'Priezvisko',
        'email'             => 'Email',
        'phone'             => 'Telefón',
        'address'           => 'Adresa',
        'city'              => 'Mesto',
        'postcode'          => 'PSČ',
        'country'           => 'Krajina',
        'confirm_booking'   => 'Potvrdiť rezerváciu',
        'service_employee'  => 'Zamestnanec',
        'date_time'         => 'Dátum',
        'price'             => 'Cena',
        'extra_service'     => 'Extra service',
        'keyword'           => 'Kľúčové slovo',
        'edit'              => 'Upraviť rezervácie',
        'terms'             => 'Podmienky',
        'terms_agree'       => 'Prečítal som si a súhlasím s podmienkami rezervácie.',
        'cancel_message'    => $cancelMessage,
        'cancel_confirm'    => 'Ste si istý, že chcete zrušiť túto rezerváciu: %s?',
        'modify_booking'    => 'Upraviť rezerváciu',
        'reschedule'        => 'Zmeniť dátum',
        'confirm_reschedule'=> 'Potvdiť zmeny',
        'cancel_reschedule' => 'Zrušiť zmeny',
        'own_customer'      => 'Own customer',
        'request_employee'  => 'Žiadosť o konkrétneho zamestnanca',
        'search_placeholder'=> 'Search for a consumer',//@todo
        'error'             => [
            'add_overlapped_booking'      => 'Čas rezervácie sa prekrýva s inou!',
            'insufficient_slots'          => 'Pre túto rezerváciu už nie je miesto!',
            'invalid_consumer_info'       => 'Údaje o zákazníkovi sa nepodarilo uložiť',
            'terms'                       => 'Musíte súhlasiť s podmienkami služby.',
            'service_empty'               => 'Prosím vyberte službu a čas!',
            'unknown'                     => 'Ups, niečo sa pokazilo!',
            'exceed_current_day'          => 'Čas rezervácie nemôže prekročiť dnešný deň',
            'overllapped_with_freetime'   => 'Rezervácia je v konflikte so zamestnancovým voľnom',
            'empty_total_time'            => 'Čas rezervácie musí byť väčší ako 1 minúta',
            'uuid_notfound'               => 'ID číslo rezervácie nebolo nájdené',
            'not_enough_slots'            => 'Na rezerváciu už nie je miesto alebo sa prekrýva s inou rezerváciou.',
            'employee_not_servable'       => 'Tento zamestnanec nevykonáva predmetnú službu.',
            'id_not_found'                => 'Rezervácie nebola nájdená.',
            'start_time'                  => 'Začiatok rezervácie je neplatný',
            'service_time_invalid'        => 'Service time for booking not found',
            'overlapped_with_freetime'    => 'Booking is overlapped with employee freetime',
            'reschedule_single_only'      => 'Booking with multiple services cannot be rescheduled',
            'reschedule_unbooked_extra'   => 'Booking cannot be rescheduled',
            'not_enough_resources'        => 'Required resources are not available!',
            'not_enough_rooms'            => 'There are not enough room!',
            'empty_start_time'            => 'Booking start time cannot be empty',
            'booking_not_found'           => 'Rezervácia nebola nájdená!',
            'past_booking'                => 'Nemôžete spraviť rezerváciu v minulosti!',
            'delete_last_booking_service' => 'Nemôžete vymazať poslenú rezervovanú službu',
            'before_min_distance'         => 'Nemôžete spraviť rezerváciu pred časom nastavným na rezerváciu',
            'after_max_distance'          => 'Nemôžete spraviť rezerváviu po čase nastavenom na rezerváciu',
            'missing_services'            => 'Add a service to continue!',//@todo
        ],
        'warning'      => [
            'existing_user'   => 'V systéme už je registrovaný užívateľ s týmto emailom. Chcete radšej použiť tieto údaje?',
        ],
    ],
    'employees' => [
        'all'                              => 'Zamestnanci',
        'add'                              => 'Pridať nového zamestnanca',
        'edit'                             => 'Upraviť zamestnanca',
        'name'                             => 'Meno',
        'phone'                            => 'Telefón',
        'email'                            => 'Email',
        'description'                      => 'Popis',
        'is_subscribed_email'              => 'Is subscribed email?',
        'is_subscribed_sms'                => 'Is subscribed SMS?',
        'is_received_calendar_invitation'  => 'Is received calendar invitation?',
        'services'                         => 'Služby',
        'status'                           => 'Status',
        'is_active'                        => 'Activation',
        'avatar'                           => 'Fotka',
        'default_time'                     => 'Default time',
        'custom_time'                      => 'Custom time',
        'days_of_week'                     => 'Dni v týždni',
        'start_time'                       => 'Začiatok',
        'end_time'                         => 'Koniec',
        'day_off'                          => 'Má voľno?',
        'confirm'                          => [
            'delete_freetime' => 'Ste si istý, že chcete vymazať vybraný voľný čas z kalendára?'
        ],
        'free_time'                        => 'Voľný čas',
        'free_times'                       => 'Voľný čas',
        'working_times'                    => 'Pracovný čas',
        'add_free_time'                    => 'Pridať voľný čas',
        'start_at'                         => 'Začiatok',
        'end_at'                           => 'Koniec',
        'date'                             => 'Dátum',
        'is_day_off'                       => 'Voľno',
        'workshifts'                       => 'Pracovné zmeny',
        'workshift_planning'               => 'Plánovanie pracovných zmien',
        'workshift_summary'                => 'Sumár pracovných zmien',
        'from_date'                        => 'Od',
        'to_date'                          => 'Do',
        'weekday'                          => 'Weekday',
        'employee'                         => 'Zamestnanec',
        'freelancer'                       => 'Freelancer',//@todo
        'business_id'                      => 'Business ID',//@todo
        'account'                          => 'Account',//@todo
        'activation'                       => 'Activation',//@todo
        'saturday_hours'                   => 'Saturdays hours',
        'sunday_hours'                     => 'Sunday hours',
        'monthly_hours'                    => 'Monthly hours',
        'error'                            => [
        'freetime_overlapped_with_booking' => 'Voľno je v konflikte s rezerváciou',
        ],
    ],
    'embed' => [
        'heading'          => 'Názov',
        'description'      => 'Obsah',
        'embed'            => 'Vložiť',
        'preview'          => 'Náhľad',
        'back_to_services' => 'Naspäť na službu',
        'select_date'      => 'Vyber dátum',
        'select_service'   => 'Vyber služby',
        'guide_text'       => 'Klikni na dostupný čas',
        'make_appointment' => 'Vytvor stretnutie',
        'cancel'           => 'Zrušiť',
        'back'             => 'Naspäť',
        'book'             => 'Rezervovať',
        'empty_cart'       => 'Váš košík je prázdny',
        'start_time'       => 'Začiatok',
        'end_time'         => 'Koniec',
        'booking_form'     => 'Rezervácia od',
        'name'             => 'Meno',
        'email'            => 'Email',
        'phone'            => 'Telefón',
        'checkout'         => 'Pokladňa',
        'fi_version'       => 'Finnish',
        'en_version'       => 'English',
        'sv_version'       => 'Swedish',
        'sk_version'       => 'Slovensky',
        'book'             => 'Rezervovať',
        'loading'          => 'Nahrávam&hellip;',
        'success'          => 'Rezervácia bola akceptovaná. Ďakujeme.',
        'confirm'          => 'Potvrdiť rezerváciu',
        'layout_2'         => [
            'select_service'      => 'Vyberte službu a čas',
            'select_service_type' => 'Vyberte kategóriu služby',
            'services'            => 'Služby',
            'selected'            => 'Vybrané služby',
            'extra_services'      => 'Extra services',
            'employees'           => 'Zamestnanci',
            'choose'              => 'Vyberte dátum',
            'unavailable'         => 'Žiadný čas nie je dostupný',
            'form'                => 'Kontaktné informácie',
            'date'                => 'Dátum',
            'price'               => 'Cena',
            'name'                => 'Meno',
            'phone'               => 'Telefón',
            'email'               => 'Email',
            'thanks'              => 'Ďakujeme za rezerváciu!',
        ],
        'layout_3'         => [
            'select_service'  => 'Vyberte službu',
            'select_employee' => 'Vyberte zamestnanca',
            'select_datetime' => 'Vyberte dátum a čas',
            'contact'         => 'Kontaktné informácie',
            'service'         => 'Služba',
            'employee'        => 'Zamestnanec',
            'name'            => 'Vaše meno',
            'notes'           => 'Poznámky',
            'postcode'        => 'PSČ',
            'empty'           => 'Vo vybranom dni, už nie je voľný žiadný čas.',
            'payment_note'    => 'Po vykonaní rezervácie budete presmerovaný na platbu.',
            'confirm_service' => 'Potvrdte rezerváciu',
        ]
    ],
     'options' => [
        'heading' => 'Voľby',
        'updated' => 'Voľby boli aktualizované',
        'general' => [
            'index'           => 'General',
            'heading'         => 'General options',
            'info'            => 'Apply your settings',
            'currency'        => 'Mena',
            'custom_status'   => 'Vlastný Status',
            'datetime_format' => 'Formát dátumu a čas',
            'date_format'     => 'Formát dátumu',
            'time_format'     => 'Formát času',
            'layout'          => 'Layout',
            'seo_url'         => 'SEO URLs',
            'timezone'        => 'Časová zóna',
            'week_numbers'    => 'Näytä viikkonumerot',
            'week_start'      => 'Zobraziť čísla týždňov?',
            'phone_number'    => 'Telefónne čislo pre SMS',
            'business_name'   => 'Business name',
        ],
        'booking'   => [
            'heading'                                        => '',
            'info'                                           => '',
            'disable_booking'                                => 'Deaktivoať rezervačný widget', // @todo
            'index'                                          => 'Rezervácie',
            'booking_form'                                   => 'Rezervačný formulár',
            'reminders'                                      => 'Pripomienkovač',
            'confirmations'                                  => 'Potvrdenie',
            'terms'                                          => 'Podmienky',
            'confirmed'                                      => 'Potvrdené',
            'pending'                                        => 'Čakajúce',
            'accept_bookings'                                => 'Potvrdiť rezervácie',
            'hide_prices'                                    => 'Skryť ceny',
            'step'                                           => 'Krok',
            'bookable_date'                                  => 'Bookable date',
            'status_if_paid'                                 => 'Default mode for paid bookings',
            'status_if_not_paid'                             => 'Default mode for unpaid bookings',
            'notes'                                          => 'Poznámky',
            'address'                                        => 'Adresa',
            'city'                                           => 'Mesto',
            'postcode'                                       => 'PSČ',
            'country'                                        => 'Krajina',
            'email'                                          => 'Email',
            'reminder_enable'                                => 'Povoliť notifikácie',
            'reminder_email_before'                          => 'Poslať pripomienku emailom',
            'reminder_subject'                               => 'Predmet emailovej pripomienky',
            'reminder_subject_default'                       => '',
            'reminder_body'                                  => 'Obsah emailovej pripomienky',
            'reminder_sms_hours'                             => 'Poslať pripomienku cez SMS',
            'reminder_sms_country_code'                      => 'Kód krajiny',
            'reminder_sms_message'                           => 'Správa SMS',
            'terms_url'                                      => 'URL podmienok rezervácie',
            'terms_body'                                     => 'Obsah podmienok rezervácie',
            'confirm_subject_client'                         => 'Client confirmation title',
            'confirm_tokens_client'                          => 'Email body',
            'confirm_email_enable'                           => 'Povoliť emaily',
            'confirm_sms_enable'                             => 'Povoiliť sms',
            'confirm_sms_country_code'                       => 'Kód',
            'confirm_consumer_sms_message'                   => 'Zákaznícke sms',
            'confirm_employee_sms_message'                   => 'Zamestnanecké sms',
            'confirm_subject_employee'                       => 'Employee confirmation title',
            'confirm_tokens_employee'                        => 'Obsah emailu',
            'terms_enabled'                                  => 'Povoliť podmienky',
            'default_nat_service'                            => 'Default next available service',
            'min_distance'                                   => 'Min vzdialenosť',
            'max_distance'                                   => 'Max vzdialenosť',
            'auto_select_employee'                           => 'Automaticky vyber zamestnanca',
            'auto_expand_all_categories'                     => 'Automaticky rozbaľ všetky kategórie',
            'show_employee_request'                          => 'Zobraziť voľbu pre výber zamestnanca',
            'factor'                                         => 'Faktor'
        ],
        'style' => [
            'heading'                           => '',
            'info'                              => '',
            'index'                             => 'Front-end style',
            'style_logo'                        => 'Logo URL',
            'style_banner'                      => 'Banner',
            'style_heading_color'               => 'Heading color',
            'style_text_color'                  => 'Farba textu',
            'style_background'                  => 'Pozadie',
            'style_custom_css'                  => 'Vlastné CSS',
            'style_external_css'                => 'External CSS Link',
            'style_main_color'                  => 'Hlavná farba',
            'style_heading_background'          => 'Heading background',
        ],
        'working_time' => [
            'index' => 'Calendar view',
        ]
    ],
    'reports' => [
        'index'     => 'Reporty',
        'employees' => 'Zamestnanci',
        'services'  => 'Služby',
        'generate'  => 'Generate',
        'start'     => 'Začiatok',
        'end'       => 'Koniec',
        'booking'   => [
            'total'       => 'Počet rezervácií',
            'confirmed'   => 'Potvrdené rezervácie',
            'unconfirmed' => 'Nepotvrdené rezervácie',
            'cancelled'   => 'Zrušené rezervácie',
        ],
        'statistics'=> 'Štatistiky',
        'monthly'   => 'Mesačné reporty',
        'stat' => [
            'monthly'      => 'Mesačný prehľad',
            'bookings'     => 'Rezervácie',
            'revenue'      => 'Obrat',
            'working_time' => 'Pracovný čas',
            'booked_time'  => 'Rezervovaný čas',
            'occupation'   => 'Vyťaženosť %'
        ]
    ],
    'crud' => [
        'bulk_confirm'   => 'Ste si istý, že chcete vykonať túto akciu?',
        'success_add'    => 'Položka bola úspešne vytvorená.',
        'success_edit'   => 'Data boli úspešne aktualizované.',
        'success_delete' => 'Položka bola úspešne vymazaná.',
        'success_bulk'   => 'Položky boli úspešne vymazané.',
        'sortable'       => 'Potiahni pre zmenu poradia',
    ],
];
