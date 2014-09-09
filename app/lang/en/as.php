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

$notificationBody = <<< HTML
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

$notificationConsumerMessage = <<< HTML
Hei,

Kiitos varauksestasi palveluun:

{Services}

Terveisin,
HTML;

$notificationEmployeeMessage = <<< HTML
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
        'heading'     => 'Welcome',
        'description' => '', // @todo
        'today'       => 'Today',
        'tomorrow'    => 'Tomorrow',
        'print'       => 'Print',
    ],
    'services' => [
        'categories' => [
            'all'           => 'All categories',
            'add'           => 'Add new category',
            'edit'          => 'Edit category',
            'name'          => 'Name',
            'description'   => 'Description',
            'is_show_front' => 'Is shown in frontpage?',
        ],
        'resources' => [
            'all'         => 'All resources',
            'add'         => 'Add new resource',
            'edit'        => 'Edit resource',
            'name'        => 'Name',
            'description' => 'Description',
            'quantity'    => 'Quantity',
        ],
        'extras' => [
            'all'         => 'All extra services',
            'add'         => 'Add new extra service',
            'edit'        => 'Edit extra service',
            'name'        => 'Name',
            'description' => 'Description',
            'price'       => 'Price',
            'length'      => 'Length',
        ],
        'all'          => 'All services',
        'index'        => 'Palvelut', // @todo
        'desc'         => 'Näkymässä näet kaikki lisäämäsi palvelut. Voit lisätä uusia palveluita tai muokata olemassa olevia palveluita muokkaa napista.', // @todo
        'add'          => 'Add new service',
        'add_desc'     => 'Lisää uusi palvelu lisäämällä palvelun nimi, palvelun kesto ja työntekijät',
        'name'         => 'Name',
        'description'  => 'Description',
        'price'        => 'Price',
        'duration'     => 'Duration',
        'before'       => 'Before',
        'after'        => 'After',
        'total'        => 'Total',
        'category'     => 'Category',
        'is_active'    => 'Status',
        'resource'     => 'Resource',
        'extra'        => 'Extra Service',
        'employees'    => 'Employees',
        'no_employees' => 'There is no employee to be selected',
    ],
    'bookings' => [
        'confirmed'    => 'Tila: vahvistettu',
        'pending'      => 'Tila: auki',
        'cancelled'    => 'Tila: peruutettu',
        'all'          => 'Bookings',
        'add'          => 'Add new booking',
        'invoices'     => 'Invoices',
        'customers'    => 'Customers',
        'statistics'   => 'Statistics',
        'date'         => 'Date',
        'total'        => 'Total',
        'start_time'   => 'Start time',
        'end_time'     => 'End time',
        'status'       => 'Status',
        'ip'           => 'ip', // @todo,
        'add_service'  => 'Add service',
        'booking_info' => 'Booking Info',
        'booking_id'   => 'Booking ID',
        'categories'   => 'Categories',
        'services'     => 'Services',
        'service_time' => 'Service time',
        'modify_time'  => 'Modify time',
        'employee'     => 'Employee',
        'notes'        => 'Notes',
        'firstname'    => 'Firstname',
        'lastname'     => 'Lastname',
        'email'        => 'Email',
        'phone'        => 'Phone',
        'address'      => 'Address',
        'error'        => [
            'add_overlapped_booking' => 'Overlapped booking time!',// @todo
            'insufficient_slots'     => 'There is no enough time slots for this booking!',// @todo
        ],
        'warning'      => [
            'existing_user'   => 'There is an user associate with this email in our system. Do you want to use these information instead?',// @todo
        ]
    ],
    'employees' => [
        'all'                 => 'Employees',
        'add'                 => 'Add new employee',
        'edit'                => 'Edit employee',
        'name'                => 'Name',
        'phone'               => 'Phone number',
        'email'               => 'Email',
        'description'         => 'Description',
        'is_subscribed_email' => 'Is subscribed email?',
        'is_subscribed_sms'   => 'Is subscribed SMS?',
        'services'            => 'Services',
        'status'              => 'Status',
        'is_active'           => 'Status',
        'avatar'              => 'Avatar',
        'default_time'        => 'Default time',
        'custom_time'         => 'Custom time',
        'days_of_week'        => 'Days of week',
        'start_time'          => 'Start time',
        'end_time'            => 'End time',
        'day_off'             => 'Is day off?'

    ],
    'embed' => [
        'heading'          => 'Otsikko', // @todo
        'description'      => 'Sisältö', // @todo
        'embed'            => 'Embed',
        'preview'          => 'Preview',
        'back_to_services' => 'Back to services',
        'select_date'      => 'Select date',
        'select_service'   => 'Select services',
        'guide_text'       => 'Klikkaa avointa aikaa', //@todo
        'make_appointment' => 'Make appointment',
        'cancel'           => 'Cancel',
        'empty_cart'       => 'Your cart is empty',
        'start_time'       => 'Start time',
        'end_time'         => 'End time',
        'booking_form'     => 'Booking form',
        'name'             => 'Name',
        'email'            => 'Email',
        'phone'            => 'Phone number',
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
        'booking'                                        => [
        'index'                                          => 'Varaukset',// @todo
        'booking_form'                                   => 'Varauslomake',// @todo
        'reminders'                                      => 'Muistutus',// @todo
        'confirmations'                                  => 'Vahvistus',// @todo
        'terms'                                          => 'Ehdot',// @todo
        'confirmed'                                      => 'Confirmed',// @todo
        'pending'                                        => 'Pending',// @todo
        'accept_bookings'                                => 'Hyväksy varauksia',// @todo
        'hide_prices'                                    => 'Piilota hinnat',// @todo
        'step'                                           => 'Askel',// @todo
        'status_if_not_paid'                             => 'Oletustila maksetuille varauksille',// @todo
        'status_if_paid'                                 => 'Oletustila maksamattomille varauksille',// @todo
        'bf_address_1'                                   => 'Osoite 1',// @todo
        'bf_address_2'                                   => 'Osoite 2',// @todo
        'bf_captcha'                                     => 'Tunniste',// @todo
        'bf_city'                                        => 'Kaupunki',// @todo
        'bf_country'                                     => 'Maa',// @todo
        'bf_email'                                       => 'Sähköposti',// @todo
        'bf_name'                                        => 'Nimi',// @todo
        'bf_notes'                                       => 'Muistiinpanoja',// @todo
        'bf_phone'                                       => 'Puhelinnumero',// @todo
        'bf_state'                                       => 'Kunta',// @todo
        'bf_terms'                                       => 'Ehdot',// @todo
        'bf_zip'                                         => 'Postinumero',// @todo
        'reminder_enable'                                => 'Muistutusviestit käytössä',// @todo
        'reminder_email_before'                          => 'Lähetä muistutus sähköpostilla',// @todo
        'reminder_subject'                               => 'Muistutussähköpostiviestin otsikko',// @todo
        'reminder_subject_default'                       => 'Muistutus varauksestasi',// @todo
        'reminder_body'                                  => 'Sähköpostimuistutuksen runko',// @todo
        'reminder_body_default'                          => $reminderBody,// @todo
        'reminder_sms_hours'                             => 'Lähetä muistutus tekstiviestillä',// @todo
        'reminder_sms_country_code'                      => 'SMS Maatunnus',// @todo
        'reminder_sms_message'                           => 'Tekstiviesti',// @todo
        'reminder_sms_message_default'                   => $reminderSmsMessage,
        'notification_email_enable'                      => 'Enable email',// @todo
        'notification_sms_enable'                        => 'Enable sms',// @todo
        'notification_subject'                           => 'Subject',// @todo
        'notification_subject_default'                   => 'Subject default',// @todo
        'notification_body'                              => 'Body',// @todo
        'notification_body_default'                      => $notificationBody,// @todo
        'notification_sms_country_code'                  => 'Code',// @todo
        'notification_consumer_sms_message'              => 'Consumer sms',// @todo
        'notification_employee_sms_message'              => 'Employee sms',// @todo
        'notification_consumer_body_sms_message_default' => $notificationConsumerMessage,// @todo
        'notification_employee_body_sms_message_default' => $notificationEmployeeMessage,// @todo
        'terms_url'                                      => 'URL osoite ehdoille',// @todo
        'terms_body'                                     => 'Booking terms content', // @todo
        'terms_body_default'                             => $termBody,// @todo
        'confirm_subject_client'                         => 'Asiakkaan vahvistuksen otsikko',// @todo
        'confirm_subject_client_default'                 => 'Kiitos varauksestasi',// @todo
        'confirm_tokens_client'                          => 'Viestin sisältö',// @todo
        'confirm_tokens_client_default'                  => $confirmTokensClient,// @todo
        'payment_subject_client'                         => 'Asiakkaan maksuvahvistuksen otsikko',// @todo
        'payment_subject_client_default'                 => 'Payment received', // @todo
        'payment_tokens_client'                          => 'Viestin sisältö',// @todo
        'payment_tokens_client_default'                  => $paymentTokensClient,
        'confirm_subject_admin'                          => 'Hallintapaneelin maksuvahvistuksen otsikko',// @todo
        'confirm_subject_admin_default'                  => 'Uusi varaus on saapunut',// @todo
        'confirm_tokens_admin'                           => 'Viestin sisältö',// @todo
        'confirm_tokens_admin_default'                   => $confirmTokensAdmin,// @todo
        'payment_subject_admin'                          => 'Ylläpitäjän maksuvahvistuksen otsikko',// @todo
        'payment_subject_admin_default'                  => 'New payment received', // @todo
        'payment_tokens_admin'                           => 'Viestin sisältö',// @todo
        'payment_tokens_admin_default'                   => $paymentTokensAdmin,// @todo
        'confirm_subject_employee'                       => 'Työntekijän varauksen otsikko',// @todo
        'confirm_subject_employee_default'               => 'Uusi varaus on saapunut',// @todo
        'confirm_tokens_employee'                        => 'Viestin sisältö',// @todo
        'confirm_tokens_employee_default'                => $confirmTokensEmployee,// @todo
        'payment_subject_employee'                       => 'Työntekijän maksun otsikko',// @todo
        'payment_subject_employee_default'               => 'New payment received', // @todo
        'payment_tokens_employee'                        => 'Viestin sisältö',// @todo
        'payment_tokens_employee_default'                => $paymentTokensEmployee,// @todo
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
        'index'     => 'Reports',
        'employees' => 'Employees',
        'services'  => 'Services',
        'generate'  => 'Generate',
        'start'     => 'Start date',
        'end'       => 'End date',
        'booking'   => [
            'total'       => 'Total bookings',
            'confirmed'   => 'Confirmed bookings',
            'unconfirmed' => 'Unconfirmed bookings',
            'cancelled'   => 'Cancelled bookings',
        ]
    ],
    'items_per_page' => 'Items per page',
    'with_selected'  => 'With selected',
    'crud' => [
        'bulk_confirm'   => 'Are you sure to carry out this action?',
        'success_add'    => 'Item was created successfully.',
        'success_edit'   => 'Data was updated successfully.',
        'success_delete' => 'Item was deleted successfully.',
        'success_bulk'   => 'Item was deleted successfully.',
        'sortable'       => 'Drag to reorder',
    ]
];
