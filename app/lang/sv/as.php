<?php
$termBody = <<< HTML
Villkor för bokning

En bokning träder i kraft när kunden har gjort en reservation och fått en bekräftelse via telefon eller e-post. Leverantören förväntas tillhandahålla den tjänst kunden bokat och, i annat fall, kommunicera detta till kunden.

Villkor för avbokning

Avbokning ska ske senast 48 timmar innan den reserverade tiden. I annat fall förbehåller vi oss rätten att ta ut full betalning för tjänsten enligt aktuell prislista.
HTML;

$reminderBody = <<< HTML
Hej, {Name},
Detta är en påminnelse om din bokning:

Boknings ID: {BookingID}

Tjänster

{Services}

Hälsningar,
HTML;

$confirmBody = <<< HTML
Hej, {Name},
Detta är en påminnelse om din bokning:

Boknings ID: {BookingID}

Tjänster

{Services}

Hälsningar,
HTML;

$reminderSmsMessage = <<< HTML
Hej,

Tack för att du bokat:

{Services}

Hälsningar,
HTML;

$confirmConsumerMessage = <<< HTML
Hej,

Tack för att du bokat:

{Services}

Hälsningar,
HTML;

$confirmEmployeeMessage = <<< HTML
Hej,

Du har en ny bokning med {Consumer}. Tjänst: {Services}

Hälsningar,
HTML;

$confirmTokensClient = <<< HTML
Hej!

Tack för din bokning!

Valda tjänster:
{Services}

**Avbokning måste ske senast 48 timmar innan den reserverade tiden.

Välkommen!



Tjänsten tillhandahålls av ClearSense
HTML;


$paymentTokensClient = <<< HTML
Vi har mottagit betalning och bekräftat din bokning.

ID: {BookingID}

Hälsningar,
HTML;

$confirmTokensAdmin = <<< HTML
Hej!

Du har fått en ny bokning:

ID: {BookingID}

Tjänster:
{Services}

Kundinformation:
Namn: {Name}
Telefon: {Phone}
E-post: {Email}

Ytterligare information:
{Notes}
HTML;


$paymentTokensAdmin = <<< HTML
Depositionen för bokningen är betald.

ID: {BookingID}
HTML;

$confirmTokensEmployee = <<< HTML
Hej!

Du har fått en ny bokning:

ID: {BookingID}

Tjänster:
{Services}

Kundinformation
Namn: {Name}
Telefon: {Phone}
E-post: {Email}

Ytterligare information:
{Notes}
HTML;


$paymentTokensEmployee = <<< HTML
Depositionen för bokningen är betald.

ID: {BookingID}
HTML;


$cancelMessage = <<< HTML
Du har avbokat bokning {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Välkommen',
        'description'   => 'Du kan se kalender och bokningar för alla i personalen. Grönt indikerar lediga tider och grått indikerar att tiden inte går att boka.',
        'today'         => 'Idag',
        'tomorrow'      => 'Imorgon',
        'print'         => 'Skriv ut',
        'calendar'      => 'Kalender',
    ],
    'services' => [
        'heading'       => 'Tjänster',
        'edit'          => 'Redigera tjänst',
        'custom_time'   => 'Anpassad tid',
        'categories' => [
            'all'           => 'Alla kategorier',
            'add'           => 'Lägg till ny kategori',
            'edit'          => 'Redigera kategori',
            'name'          => 'Namn',
            'description'   => 'Beskrivning',
            'is_show_front' => 'Visa kategori?',
            'no_services'   => 'Det finns inga tjänster i den här kategorin',
            'availability'  => 'Tillgänglighet',
            'category_name' => 'Namn på kategori',
            'error'         => [
                'category_current_in_use' => 'Den här kategorin används. Ta bort alla relaterade tjänster och ta sedan bort kategorin.'
            ]
        ],
        'resources' => [
            'all'         => 'Alla resurser',
            'add'         => 'Lägg till ny resurs',
            'edit'        => 'Redigera resurs',
            'name'        => 'Namn',
            'description' => 'Beskrivning',
            'quantity'    => 'Antal',
        ],
        'extras' => [
            'all'         => 'Alla tilläggstjänster',
            'add'         => 'Lägg till ny tilläggstjänst',
            'edit'        => 'Redigera tilläggstjänst',
            'name'        => 'Namn',
            'description' => 'Beskrivning',
            'price'       => 'Pris',
            'length'      => 'Längd (tid)',
            'msg_extra'   => 'Vill du reservera?',
        ],
        'all'          => 'Alla tjänster',
        'index'        => 'Tjänster',
        'desc'         => 'Här kan du redigera eller lägga till nya tjänster.',
        'add'          => 'Lägg till ny tjänst',
        'add_desc'     => 'Lägg till ny personal genom att ange namn och beskrivning. Du kan också koppla tjänster till din personal.',
        'name'         => 'Namn',
        'description'  => 'Beskrivning',
        'price'        => 'Pris',
        'duration'     => 'Längd (tid)',
        'length'       => 'Längd (tid)',
        'during'       => 'Samtidigt',
        'before'       => 'Före',
        'after'        => 'Efter',
        'total'        => 'Total längd',
        'category'     => 'Kategori',
        'is_active'    => 'Aktiv',
        'resource'     => 'Resurs',
        'extra'        => 'Tilläggstjänst',
        'employees'    => 'Personal',
        'no_employees' => 'Det finns ingen personal att välja',
        'no_name'      => 'Untitled',
        'error'        => [
            'service_current_in_use' => 'Den här tjänsten används. Radera alla relaterade bokningar och ta sedan bort tjänsten.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Bekräftad',
        'pending'           => 'Ej bekräftad',
        'cancelled'         => 'Avbokad',
        'arrived'           => 'Ankommit',
        'paid'              => 'Betald',
        'not_show_up'       => 'Kund dök inte upp',
        'change_status'     => 'Byt status',
        'all'               => 'Bokningar',
        'add'               => 'Lägg till ny bokning',
        'invoices'          => 'Fakturor',
        'consumer'          => 'Kund',
        'statistics'        => 'Statistik',
        'date'              => 'Datum',
        'total'             => 'Längd (tid)',
        'start_at'          => 'Börja',
        'end_at'            => 'Sluta',
        'status'            => 'Status',
        'total_price'       => 'Pris',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Lägg till ny tjänst',
        'booking_info'      => 'Bokningsinformation',
        'booking_id'        => 'Boknings ID',
        'categories'        => 'Kategorier',
        'services'          => 'Tjänster',
        'service_time'      => 'Service time',
        'modify_time'       => 'Modify time',
        'plustime'          => 'Plustime',
        'modify_duration'   => 'Modify duration',
        'employee'          => 'Employee',
        'notes'             => 'Anteckingar',
        'first_name'        => 'Förnamn',
        'last_name'         => 'Efternamn',
        'email'             => 'E-post',
        'phone'             => 'Telefon',
        'address'           => 'Adress',
        'city'              => 'Ort',
        'postcode'          => 'Postnummer',
        'country'           => 'Land',
        'confirm_booking'   => 'Bekräfta bokning',
        'service_employee'  => 'Personal',
        'date_time'         => 'Datum',
        'price'             => 'Pris',
        'extra_service'     => 'Tilläggstjänst',
        'keyword'           => 'Nyckelord',
        'edit'              => 'Redigera bokning',
        'terms'             => 'Villkor',
        'terms_agree'       => 'Jag har läst och godkänner villkoren',
        'cancel_message'    => $cancelMessage,
         'cancel_confirm'    => 'Är du säker på att du vill avboka %s?',
        'modify_booking'    => 'Ändra bokning',
        'reschedule'        => 'Boka om',
        'confirm_reschedule'=> 'Bekräfta ombokning',
        'cancel_reschedule' => 'Avbryt ombokning',
        'own_customer'      => 'Egen kund',
        'request_employee'  => 'Önskat personal',
        'error'             => [
            'add_overlapped_booking'   => 'Tiden för bokningen överlappar!',
            'insufficient_slots'       => 'Det finns inte tillräckligt med ledig tid för den här bokningen!',
            'invalid_consumer_info'    => 'Kunde inte spara kundinformation',
            'terms'                    => 'Du måste godkänna villkoren för att komma vidare.',
            'service_empty'            => 'Välj tjänst och tid!',
            'unknown'                  => 'Oj! Något gick snett. Vänligen försök igen.',
            'exceed_current_day'       => 'Tiden för bokningen kan inte sträcka sig utanför dagens öppettider',
            'overllapped_with_freetime'=> 'Bokningen krockar med ej bokningsbar tid.',
            'empty_total_time'         => 'Bokningen måste vara i minst 1 minut.',
            'uuid_notfound'            => 'Kunde inte hitta boknings ID',
            'not_enough_slots'         => 'Bokningen krockar med annan bokning, eller så finns inte tillräckligt med bokningsbar tid.',
            'employee_not_servable'    => 'Den här personen kan ej bokas för den här tjänsten.',
            'id_not_found'             => 'Kunde inte hitta bokningen',
            'start_time'               => 'Fel starttid',
            'service_time_invalid'     => 'Tid för den här tjänsten kunde inte hittas.',
            'overlapped_with_freetime' => 'Bokningen sträcker sig över ej bokningsbar tid',
            'reschedule_single_only'   => 'Bokning med flera tjänster kan inte bokas om',
            'reschedule_unbooked_extra'=> 'Ombokning är ej möjlig',
            'not_enough_resources'     => 'En resurs som krävs för den här bokningen är inte tillgänglig!',
            'empty_start_time'         => 'Vänligen ange starttid för bokningen',
            'booking_not_found'        => 'Kunde inte hitta bokningen!',
            'past_booking'             => 'Tiden eller datumet för bokningen har passerat!'
        ],
        'warning'      => [
            'existing_user'   => 'Det finns redan en användare med den här e-postadressen. Vill du använda den istället?',
        ],
        'stat' => [
            'monthly'      => 'Månadssammanställning',
            'bookings'     => 'Bokningar',
            'revenue'      => 'Intäkter',
            'working_time' => 'Arbetstid',
            'booked_time'  => 'Bokad tid',
            'occupation'   => 'Beläggning %'
        ]
    ],
    'employees' => [
        'all'                 => 'Personal',
        'add'                 => 'Lägg till personal',
        'edit'                => 'Redigera personal',
        'name'                => 'Namn',
        'phone'               => 'Telefon',
        'email'               => 'E-post',
        'description'         => 'Beskrivning',
        'is_subscribed_email' => 'E-post aktiverad?',
        'is_subscribed_sms'   => 'SMS aktiverat?',
        'services'            => 'Tjänster',
        'status'              => 'Status',
        'is_active'           => 'Status',
        'avatar'              => 'Bild',
        'default_time'        => 'Standardtid',
        'custom_time'         => 'Anpassad tid',
        'days_of_week'        => 'Veckodagar',
        'start_time'          => 'Starttid',
        'end_time'            => 'Sluttid',
        'day_off'             => 'Ledig?',
        'confirm'             => [
            'delete_freetime' => 'Vill du ta bort ej bokningsbara tider från kalendern?'
        ],
        'free_time'           => 'Ej bokningsbar tid',
        'free_times'          => 'Ej bokningsbara tider',
        'working_times'       => 'Arbetstider',
        'add_free_time'       => 'Lägg till ej bokningsbar tid',
        'start_at'            => 'Börjar',
        'end_at'              => 'Slutar',
        'date'                => 'Datum',
        'is_day_off'          => 'Ej bokningsbar dag',
        'workshifts'          => 'Arbetsskift',
        'workshift_planning'  => 'Planera arbetsskift',
        'workshift_summary'   => 'Arbetsskift sammanfattning',
        'from_date'           => 'Från datum',
        'to_date'             => 'Till datum',
        'weekday'             => 'Veckodag',
        'employee'            => 'Personal',
        'saturday_hours'      => 'Timmar lördag',
        'sunday_hours'        => 'Timmar söndag',
        'monthly_hours'       => 'Timmar månad',
        'error'               => [
            'freetime_overlapped_with_booking' => 'Ej bokningsbar tid krockar med existerande bokning'
        ],
    ],
    'embed' => [
        'heading'          => 'Rubrik',
        'description'      => 'Innehåll',
        'embed'            => 'Bädda in',
        'preview'          => 'Förhandsgranska',
        'back_to_services' => 'Tillbaka till tjänster',
        'select_date'      => 'Välj datum',
        'select_service'   => 'Välj tjänster',
        'guide_text'       => 'Klicka på lediga tider',
        'make_appointment' => 'Boka',
        'cancel'           => 'Avbryt',
        'empty_cart'       => 'Kundvagnen är tom.',
        'start_time'       => 'Starttid',
        'end_time'         => 'Sluttid',
        'booking_form'     => 'Bokningsformulär',
        'name'             => 'Namn',
        'email'            => 'E-post',
        'phone'            => 'Telefon',
        'checkout'         => 'Gå vidare',
        'fi_version'       => 'Finska',
        'en_version'       => 'Engelska',
        'book'             => 'Boka',
        'loading'          => 'Laddar;',
        'success'          => 'Din bokning är genomförd. Tack!.',
        'confirm'          => 'Bekräfta bokning',
        'layout_2'         => [
            'select_service'      => 'Välj tjänst och datum',
            'select_service_type' => 'Välj kategori',
            'services'            => 'Tjänster',
            'selected'            => 'Valda tjänster',
            'extra_services'      => 'tilläggstjänster',
            'employees'           => 'Personal',
            'choose'              => 'Välj datum',
            'unavailable'         => 'Inga tillgängliga tider',
            'form'                => 'Kontaktinformation',
            'date'                => 'Datum',
            'price'               => 'Pris',
            'name'                => 'Namn',
            'phone'               => 'Telefon',
            'email'               => 'E-post',
            'thanks'              => 'Tack för din bokning!',
        ],
        'layout_3'         => [
            'select_service'  => 'Välj tjänster',
            'select_employee' => 'Välj personal',
            'select_datetime' => 'Välj datum &amp; tid',
            'contact'         => 'Kontaktinformation',
            'service'         => 'Tjänst',
            'employee'        => 'Personal',
            'name'            => 'Ditt namn',
            'notes'           => 'Anteckningar',
            'postcode'        => 'Postnummer',
            'empty'           => 'Det finns inga tillgängliga tider den valda dagen.',
            'payment_note'    => 'När bokningen är gjord kommer du att skickas vidare till kassan.',
        ]
    ],
     'options' => [
        'heading' => 'Inställningar',
        'updated' => 'Dina ändringar är sparade!',
        'general' => [
            'index'           => 'Grundinställningar',
            'heading'         => 'Grundinställningar',
            'info'            => 'Inställningar för din verksamhet',
            'currency'        => 'Valuta',
            'custom_status'   => 'Anpassad status',
            'datetime_format' => 'Format datum och tid',
            'date_format'     => 'Datumformat',
            'time_format'     => 'Tidsformat',
            'layout'          => 'Utseende',
            'seo_url'         => 'SEO URL',
            'timezone'        => 'Tidszon',
            'week_numbers'    => 'Veckonummer',
            'week_start'      => 'Visa veckonummer?',
            'phone_number'    => 'Telefonnummer SMS',
            'business_name'   => 'Företagsnamn',
        ],
        'booking'   => [
            'heading'                                        => '',
            'info'                                           => '',
            'index'                                          => 'Bokningar',
            'booking_form'                                   => 'Bokningsformulär',
            'reminders'                                      => 'Påminnelse',
            'confirmations'                                  => 'Bekräftelse',
            'terms'                                          => 'Villkor',
            'confirmed'                                      => 'Bekräftad',
            'pending'                                        => 'Ej bekräftad',
            'accept_bookings'                                => 'Godkänn bokningar',
            'hide_prices'                                    => 'Dölj priser',
            'step'                                           => 'Steg',
            'bookable_date'                                  => 'Bokningsbart datum',
            'status_if_paid'                                 => 'Default mode for paid bookings',
            'status_if_not_paid'                             => 'Default mode for unpaid bookings',
            'notes'                                          => 'Anteckingar',
            'address'                                        => 'Adress',
            'city'                                           => 'Ort',
            'postcode'                                       => 'Postnummer',
            'country'                                        => 'Land',
            'email'                                          => 'E-post',
            'reminder_enable'                                => 'Aktivera notiser/påminnelser',
            'reminder_email_before'                          => 'Skicka påminnelse via e-post',
            'reminder_subject'                               => 'Ämne för påminnelse',
            'reminder_subject_default'                       => 'Påminnelse om din bokning',
            'reminder_body'                                  => 'Meddelande för påminnelse',
            'reminder_body_default'                          => $reminderBody,
            'reminder_sms_hours'                             => 'Skicka påminnelse via SMS',
            'reminder_sms_country_code'                      => 'SMS landskod (t.ex. +46)',
            'reminder_sms_message'                           => 'SMS Meddelande',
            'reminder_sms_message_default'                   => $reminderSmsMessage,
            'terms_url'                                      => 'Bokningsvillkor URL',
            'terms_body'                                     => 'Bokningsvillkor innehåll',
            'terms_body_default'                             => $termBody,
            'confirm_subject_client'                         => 'Bekräftelse till kund - Rubrik',
            'confirm_subject_client_default'                 => 'Tack för din bokning',
            'confirm_tokens_client'                          => 'Innehåll e-post',
            'confirm_tokens_client_default'                  => $confirmTokensClient,
            'confirm_email_enable'                           => 'Aktivera e-post',
            'confirm_sms_enable'                             => 'Aktivera SMS',
            'confirm_sms_country_code'                       => 'Kod',
            'confirm_consumer_sms_message'                   => 'Kund SMS',
            'confirm_employee_sms_message'                   => 'Personal SMS',
            'confirm_consumer_body_sms_message_default'      => $confirmConsumerMessage,
            'confirm_employee_body_sms_message_default'      => $confirmEmployeeMessage,
            'payment_subject_client'                         => 'Rubrik betalningsmeddelande',
            'payment_subject_client_default'                 => 'Betalning mottagen',
            'payment_tokens_client'                          => 'E-post meddelande',
            'payment_tokens_client_default'                  => $paymentTokensClient,
            'confirm_subject_admin'                          => 'Bekräftelse till admin - Rubrik',
            'confirm_subject_admin_default'                  => 'Ny bokning',
            'confirm_tokens_admin'                           => 'E-post meddelande',
            'confirm_tokens_admin_default'                   => $confirmTokensAdmin,
            'payment_subject_admin'                          => 'Betalning admin - Rubrik',
            'payment_subject_admin_default'                  => 'Ny betalning mottagen',
            'payment_tokens_admin'                           => 'Innehåll e-post',
            'payment_tokens_admin_default'                   => $paymentTokensAdmin,
            'confirm_subject_employee'                       => 'Bekräftelse personal - Rubrik',
            'confirm_subject_employee_default'               => 'Ny bokning',
            'confirm_tokens_employee'                        => 'Innehåll e-post',
            'confirm_tokens_employee_default'                => $confirmTokensEmployee,
            'payment_subject_employee'                       => 'Rubrik betalning personal',
            'payment_subject_employee_default'               => 'Ny betalning mottagen',
            'payment_tokens_employee'                        => 'E-post meddelande',
            'payment_tokens_employee_default'                => $paymentTokensEmployee,
            'terms_enabled'                                  => 'Aktivera villkor',
            'default_nat_service'                            => 'Nästa tillgängliga tjänst',
        ],
        'style' => [
            'heading'                           => '',
            'info'                              => '',
            'index'                             => 'Utseende',
            'style_logo'                        => 'Logotyp URL',
            'style_banner'                      => 'Banner',
            'style_heading_color'               => 'Textfärg Rubrik',
            'style_text_color'                  => 'Textfärg',
            'style_background'                  => 'Bakgrundsfärg',
            'style_custom_css'                  => 'Anpassad CSS',
            'style_main_color'                  => 'Huvudsaklig färg',
            'style_heading_background'          => 'Rubrik bakgrund',
        ],
        'working_time' => [
            'index' => 'Arbetstid',
        ]
    ],
    'reports' => [
        'index'     => 'Rapporter',
        'employees' => 'Personal',
        'services'  => 'Tjänster',
        'generate'  => 'Skapa',
        'start'     => 'Startdatum',
        'end'       => 'Slutdatum',
        'booking'   => [
            'total'       => 'Summa antal bokningar',
            'confirmed'   => 'Bekräftade bokningar',
            'unconfirmed' => 'Ej bekräftade bokningar',
            'cancelled'   => 'Avbokningar',
        ]
    ],
    'items_per_page' => 'Reslutat per sida',
    'with_selected'  => 'Markerade',
    'crud' => [
        'bulk_confirm'   => 'Är du säker att du vill gå vidare?',
        'success_add'    => 'Klart!',
        'success_edit'   => 'Uppladdningen lyckades!',
        'success_delete' => 'Raderat!.',
        'success_bulk'   => 'Raderat!',
        'sortable'       => 'Drag för att ändra ordning',
    ],
    'delete_reason'         => 'Varför ska den här bokningen tas bort?',
    'delete_reason_default' => 'På kundens begäran',
];
