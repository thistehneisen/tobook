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

// @todo
$cancelMessage = <<< HTML
You have cancelled the booking {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Welcome',
        'description'   => 'You can view the calendar of all created employees. Green time is bookable for consumers and grey is not bookable.', // @todo
        'today'         => 'Today',
        'tomorrow'      => 'Tomorrow',
        'print'         => 'Print',
        'calendar'      => 'Calendar',
    ],
    'services' => [
        'heading'       => 'Services',
        'edit'          => 'Edit service',//@todo
        'custom_time'   => 'Custom time',//@todo
        'categories' => [
            'all'           => 'All categories',
            'add'           => 'Add new category',
            'edit'          => 'Edit category',
            'name'          => 'Name',
            'description'   => 'Description',
            'is_show_front' => 'Is shown in frontpage?',
            'no_services'   => 'There are no services for this category',
            'availability'  => 'Availability',
            'category_name' => 'Category name',
            'error'         => [
                'category_current_in_use' => 'Category is currently in use. Please delete all related services before deleting this category.'
            ]
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
            'msg_extra'   => 'Do you also want to reserve?',
        ],
        'all'          => 'All services',
        'index'        => 'Services', // @todo
        'desc'         => 'Here you can edit or add new services', // @todo
        'add'          => 'Add new service',
        'add_desc'     => 'Add a new employee by adding service name and description, you can also connect services to employees',
        'name'         => 'Name',
        'description'  => 'Description',
        'price'        => 'Price',
        'duration'     => 'Duration',
        'length'       => 'Duration',
        'during'       => 'During',
        'before'       => 'Before',
        'after'        => 'After',
        'total'        => 'Total',
        'category'     => 'Category',
        'is_active'    => 'Status',
        'resource'     => 'Resource',
        'extra'        => 'Extra Service',
        'employees'    => 'Employees',
        'no_employees' => 'There is no employee to be selected',
        'no_name'      => 'Untitled',//@todo
        'error'        => [
            'service_current_in_use' => 'Services is currently in use. Please delete all related bookings before deleting this service.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Confirmed',
        'pending'           => 'Pending',
        'cancelled'         => 'Cancelled',
        'arrived'           => 'Arrived',
        'paid'              => 'Paid',
        'not_show_up'       => 'Customer does not show up',
        'change_status'     => 'Change booking status',
        'all'               => 'Bookings',
        'add'               => 'Add new booking',
        'invoices'          => 'Invoices',
        'customers'         => 'Customers',
        'statistics'        => 'Statistics',
        'date'              => 'Date',
        'total'             => 'Duration',
        'start_at'          => 'Start at',
        'end_at'            => 'End at',
        'status'            => 'Status',
        'total_price'       => 'Price',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Add service',
        'booking_info'      => 'Booking Info',
        'booking_id'        => 'Booking ID',
        'categories'        => 'Categories',
        'services'          => 'Services',
        'service_time'      => 'Service time',
        'modify_time'       => 'Modify time',
        'plustime'          => 'Plustime', // @todo
        'modify_duration'   => 'Modify duration',
        'employee'          => 'Employee',
        'notes'             => 'Notes',
        'first_name'        => 'First name',
        'last_name'         => 'Last name',
        'email'             => 'Email',
        'phone'             => 'Phone',
        'address'           => 'Address',
        'city'              => 'City',
        'postcode'          => 'Postcode',
        'country'           => 'Country',
        'confirm_booking'   => 'Confirm booking',
        'service_employee'  => 'Employee',
        'date_time'         => 'Date',
        'price'             => 'Price',
        'extra_service'     => 'Extra service',
        'keyword'           => 'Keyword',
        'edit'              => 'Edit bookings',
        'terms'             => 'Terms',
        'terms_agree'       => 'I\'ve read and agreed to the booking terms',
        'cancel_message'    => $cancelMessage,
        'cancel_confirm'    => 'Are you sure to cancel this booking %s?',
        'modify_booking'    => 'Modify booking',
        'reschedule'        => 'Reschedule',
        'confirm_reschedule'=> 'Confirm reschedule',
        'cancel_reschedule' => 'Cancel reschedule',
        'own_customer'      => 'Own customer',
        'request_employee'  => 'Requesting for a specific employee',
        'error'             => [
            'add_overlapped_booking'   => 'Overlapped booking time!',// @todo
            'insufficient_slots'       => 'There is no enough time slots for this booking!',// @todo
            'invalid_consumer_info'    => 'Could not save consumer info',// @todo
            'terms'                    => 'You have to agree with our term.',//@todo
            'service_empty'            => 'Please select service and service time!',//@todo
            'unknown'                  => 'Something went wrong!',//@todo
            'exceed_current_day'       => 'Booking end time cannot exceed current day',//@todo
            'overllapped_with_freetime'=> 'Booking is overllapped with employee freetime',//@todo
            'empty_total_time'         => 'Booking total minutes must be greater or equal 1',//@todo
            'uuid_notfound'            => 'Booking ID not found', //@todo
            'not_enough_slots'         => 'Not enough booking slots or overllaped with other booking.', //@todo
            'employee_not_servable'    => 'This employee does not serve the booking service.', //@todo
            'id_not_found'             => 'Booking not found',
            'start_time'               => 'Booking start time is invalid',
            'service_time_invalid'     => 'Service time for booking not found',
            'overlapped_with_freetime' => 'Booking is overlapped with employee freetime',
            'reschedule_single_only'   => 'Booking with multiple services cannot be rescheduled',
            'reschedule_unbooked_extra'=> 'Booking cannot be rescheduled',
            'not_enough_resources'     => 'Required resources are not available!',
        ],
        'warning'      => [
            'existing_user'   => 'There is an user associate with this email in our system. Do you want to use these information instead?',// @todo
        ],
        'stat' => [
            'monthly'      => 'Monthly review', // @todo
            'bookings'     => 'Bookings',
            'revenue'      => 'Revenue',
            'working_time' => 'Working time',
            'booked_time'  => 'Booked time',
            'occupation'   => 'Occupation %'
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
        'day_off'             => 'Is day off?',
        'confirm'             => [
            'delete_freetime' => 'Are you sure to delete selected free time from the calendar?'
        ],
        'free_time'           => 'Free time',
        'free_times'          => 'Free times',
        'working_times'       => 'Working times',
        'add_free_time'       => 'Add free time',
        'start_at'            => 'Start at',
        'end_at'              => 'End at',
        'date'                => 'Date',
        'is_day_off'          => 'Day off',
        'workshifts'          => 'Workshifts',
        'workshift_planning'  => 'Workshift planning',
        'workshift_summary'   => 'Workshift summary',
        'from_date'           => 'From date',
        'to_date'             => 'To date',
        'weekday'             => 'Weekday',
        'employee'            => 'Employee',
        'saturday_hours'      => 'Saturdays hours',
        'sunday_hours'        => 'Sunday hours',
        'monthly_hours'       => 'Monthly hours',
        'error'               => [
            'freetime_overlapped_with_booking' => 'Freetime is overlapped with a booking'
        ],
    ],
    'embed' => [
        'heading'          => 'Title',
        'description'      => 'Body',
        'embed'            => 'Embed',
        'preview'          => 'Preview',
        'back_to_services' => 'Back to services',
        'select_date'      => 'Select date',
        'select_service'   => 'Select services',
        'guide_text'       => 'Click on available time',
        'make_appointment' => 'Make appointment',
        'cancel'           => 'Cancel',
        'empty_cart'       => 'Your cart is empty',
        'start_time'       => 'Start time',
        'end_time'         => 'End time',
        'booking_form'     => 'Booking form',
        'name'             => 'Name',
        'email'            => 'Email',
        'phone'            => 'Phone number',
        'checkout'         => 'Checkout',
        'fi_version'       => 'Finnish',
        'en_version'       => 'English',
        'book'             => 'Book',
        'loading'          => 'Now loading&hellip;',
        'success'          => 'Booking was placed. Thank you.',
        'confirm'          => 'Confirm booking',
        'layout_2'         => [
            'select_service'      => 'Choose service and date',
            'select_service_type' => 'Choose service category',
            'services'            => 'Services',
            'selected'            => 'Selected services',
            'extra_services'      => 'Extra services',
            'employees'           => 'Employee',
            'choose'              => 'Choose date',
            'unavailable'         => 'No times available',
            'form'                => 'Contact information',
            'date'                => 'Date',
            'price'               => 'Price',
            'name'                => 'Name',
            'phone'               => 'Phone',
            'email'               => 'Email',
            'thanks'              => 'Thank you for your booking!',
        ],
        'layout_3'         => [
            'select_service'  => 'Select service',
            'select_employee' => 'Select employee',
            'select_datetime' => 'Select date &amp; time',
            'contact'         => 'Contact information',
            'service'         => 'Service',
            'employee'        => 'Employee',
            'name'            => 'Your name',
            'notes'           => 'Notes', //@todo
            'postcode'        => 'Postcode', //@todo
            'empty'           => 'There is no available time on selected day.',
            'payment_note'    => 'After a booking is placed, you will be redirected to payment.', // @todo
        ]
    ],
     'options' => [
        'heading' => 'Options',
        'updated' => 'Options updated',
        'general' => [
            'index'           => 'General',
            'heading'         => 'General options',
            'info'            => 'Apply your settings',
            'currency'        => 'Currency',
            'custom_status'   => 'Custom Status',
            'datetime_format' => 'Datetime format',
            'date_format'     => 'Date format',
            'time_format'     => 'Time format',
            'layout'          => 'Layout',
            'seo_url'         => 'SEO URLs',
            'timezone'        => 'Timezone',
            'week_numbers'    => 'Näytä viikkonumerot',
            'week_start'      => 'Show week numbers?',
            'phone_number'    => 'SMS phone number', // @todo
            'business_name'   => 'Business name', // @todo
        ],
        'booking'   => [
            'heading'                                        => '', // @todo
            'info'                                           => '', // @todo
            'index'                                          => 'Bookings',// @todo
            'booking_form'                                   => 'Booking Form',
            'reminders'                                      => 'Reminder',
            'confirmations'                                  => 'Confirmation',// @todo
            'terms'                                          => 'Terms',// @todo
            'confirmed'                                      => 'Confirmed',// @todo
            'pending'                                        => 'Pending',// @todo
            'accept_bookings'                                => 'Accept bookings',// @todo
            'hide_prices'                                    => 'Hide prices',// @todo
            'step'                                           => 'Step',// @todo
            'bookable_date'                                  => 'Bookable date',  // @todo
            'status_if_paid'                                 => 'Default mode for paid bookings',// @todo
            'status_if_not_paid'                             => 'Default mode for unpaid bookings',// @todo
            'bf_address_1'                                   => 'Address 1',
            'bf_address_2'                                   => 'Address 2',
            'bf_captcha'                                     => 'Captcha',
            'bf_city'                                        => 'City',
            'bf_country'                                     => 'Country',
            'bf_email'                                       => 'Email',
            'bf_name'                                        => 'Name',
            'bf_notes'                                       => 'Notes',
            'bf_phone'                                       => 'Phone',
            'bf_state'                                       => 'State',
            'bf_terms'                                       => 'Terms',
            'bf_zip'                                         => 'Zip',
            'notes'                                          => 'Notes', // @todo
            'address'                                        => 'Address', // @todo
            'city'                                           => 'City', // @todo
            'postcode'                                       => 'Postcode', // @todo
            'country'                                        => 'Country', // @todo
            'reminder_enable'                                => 'Enable notifications',
            'reminder_email_before'                          => 'Send email reminder',
            'reminder_subject'                               => 'Email Reminder subject',
            'reminder_subject_default'                       => 'Muistutus varauksestasi',// @todo
            'reminder_body'                                  => 'Email Reminder body',
            'reminder_body_default'                          => $reminderBody,// @todo
            'reminder_sms_hours'                             => 'Send SMS reminder',
            'reminder_sms_country_code'                      => 'SMS country code',
            'reminder_sms_message'                           => 'SMS message',
            'reminder_sms_message_default'                   => $reminderSmsMessage,
            'terms_url'                                      => 'Booking terms URL',// @todo
            'terms_body'                                     => 'Booking terms content', // @todo
            'terms_body_default'                             => $termBody,// @todo
            'confirm_subject_client'                         => 'Client confirmation title',// @todo
            'confirm_subject_client_default'                 => 'Kiitos varauksestasi',// @todo
            'confirm_tokens_client'                          => 'Email body',// @todo
            'confirm_tokens_client_default'                  => $confirmTokensClient,// @todo
            'confirm_email_enable'                           => 'Enable email',// @todo
            'confirm_sms_enable'                             => 'Enable sms',// @todo
            'confirm_sms_country_code'                       => 'Code',// @todo
            'confirm_consumer_sms_message'                   => 'Consumer sms',// @todo
            'confirm_employee_sms_message'                   => 'Employee sms',// @todo
            'confirm_consumer_body_sms_message_default'      => $confirmConsumerMessage,
            'confirm_employee_body_sms_message_default'      => $confirmEmployeeMessage,
            'payment_subject_client'                         => 'Client payment title',
            'payment_subject_client_default'                 => 'Payment received', // @todo
            'payment_tokens_client'                          => 'Email body',
            'payment_tokens_client_default'                  => $paymentTokensClient,
            'confirm_subject_admin'                          => 'Admin confirmation title',// @todo
            'confirm_subject_admin_default'                  => 'Uusi varaus on saapunut',// @todo
            'confirm_tokens_admin'                           => 'Email body',// @todo
            'confirm_tokens_admin_default'                   => $confirmTokensAdmin,// @todo
            'payment_subject_admin'                          => 'Admin payment title',
            'payment_subject_admin_default'                  => 'New payment received', // @todo
            'payment_tokens_admin'                           => 'Email body',
            'payment_tokens_admin_default'                   => $paymentTokensAdmin,// @todo
            'confirm_subject_employee'                       => 'Employee confirmation title',// @todo
            'confirm_subject_employee_default'               => 'Uusi varaus on saapunut',// @todo
            'confirm_tokens_employee'                        => 'Email body',// @todo
            'confirm_tokens_employee_default'                => $confirmTokensEmployee,// @todo
            'payment_subject_employee'                       => 'Employee payment title',
            'payment_subject_employee_default'               => 'New payment received', // @todo
            'payment_tokens_employee'                        => 'Email body',
            'payment_tokens_employee_default'                => $paymentTokensEmployee,// @todo
            'terms_enabled'                                  => 'Enable terms',
            'default_nat_service'                            => 'Default next available service',
        ],
        'style' => [
            'heading'                           => '', // @todo
            'info'                              => '', // @todo
            'index'                             => 'Front-end style',
            'style_logo'                        => 'Logo URL',
            'style_banner'                      => 'Banner',
            'style_heading_color'               => 'Heading color', // @todo
            'style_text_color'                  => 'Text color',
            'style_background'                  => 'Background',
            'style_custom_css'                  => 'Custom CSS', // @todo
            'style_main_color'                  => 'Main color', // @todo
            'style_heading_background'          => 'Heading background', // @todo
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
    ],
    'delete_reason'         => 'Why is this booking deleted?',
    'delete_reason_default' => 'On customer request',
];
