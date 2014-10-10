<?php

$confirmationTokens = <<< HTML
You've just made a booking.

Personal details:
Title: {Title}
First Name: {FirstName}
Last Name: {LastName}
Email: {Email}
Phone: {Phone}
Company: {Company}
Address: {Address}
Zip: {Zip}
City: {City}
Country: {Country}
Notes: {Notes}

Booking details:
Date/Time From: {DateTimeFrom}
Table: {Table}
People: {People}
Booking ID: {BookingID}
Unique ID: {UniqueID}
Total: {Total}

If you want to cancel your booking, please click on the following link:
{CancelURL}

We will contact you soon. Thank you.

HTML;

$enquiryTokens = <<< HTML

You've just made a enquiry.

Personal details:
Title: {Title}
First Name: {FirstName}
Last Name: {LastName}
E-Mail: {Email}
Phone: {Phone}
Notes: {Notes}
Country: {Country}
City: {City}
State: {State}
Zip: {Zip}
Address: {Address}
Company: {Company}

Enquiry details:
Date/Time From: {DateTimeFrom}
People: {People}
Unique ID: {UniqueID}

If you want to cancel your enquiry, please click on the following link:
{CancelURL}

Thank you, we will contact you soon.

HTML;

return [
    'tables'    => [
        'index'     => 'Tables',
        'all'       => 'All tables',
        'add'       => 'Add new table',
        'name'      => 'Table name',
        'seats'     => 'Number of seats',
        'minimum'   => 'Minimum',
        'edit'      => 'Edit table',
    ],
    'groups'    => [
        'index'         => 'Groups',
        'all'           => 'All groups',
        'add'           => 'Add new group',
        'name'          => 'Group name',
        'description'   => 'Description',
        'edit'          => 'Edit group',
        'tables'        => 'Tables',
    ],
    'services'  => [
        'index'         => 'Services',
        'all'           => 'All services',
        'add'           => 'Add new service',
        'name'          => 'Service name',
        'start_at'      => 'Start at',
        'end_at'        => 'End at',
        'length'        => 'Length',
        'price'         => 'Price',
        'edit'          => 'Edit service',
    ],
    'menus'     => [
        'index'         => 'Menus',
        'all'           => 'All menus',
        'add'           => 'Add new menu',
        'name'          => 'Menu name',
        'type'          => 'Menu type',
        'edit'          => 'Edit menu',
    ],
    'bookings'  => [
        'index'             => 'Bookings',
        'all'               => 'All bookings',
        'add'               => 'Add new booking',
        'edit'              => 'Edit booking',
        'uuid'              => 'UUID',
        'date'              => 'Date',
        'start_at'          => 'Start at',
        'end_at'            => 'End at',
        'status'            => 'Status',
        'note'              => 'Note',
        'total'             => 'Total',
        'is_group_booking'  => 'Group booking',
        'source'            => 'Source',
    ],
    'options'   => [
        'index'             => 'Options',
        'updated'           => 'Options updated',
        'general'       => [
            'index'             => 'General',
            'currency'          => 'Currency',
            'date_format'       => 'Date format',
            'datetime_format'   => 'Datetime format',
            'time_format'       => 'Time format',
            'timezone'          => 'Timezone',
        ],
        'booking'       => [
            'index'                                     => 'Bookings',
            'confirmation'                              => 'Confirmation',
            'price'                                     => 'Booking price',
            'length'                                    => 'Booking length',
            'hours_before'                              => 'Hours earlier',
            'booking_type'                              => 'Booking type',
            'booking_type_categories'                   => 'Categories',
            'booking_type_time'                         => 'Time',
            'group_minimum'                             => 'Group booking',
            'booking_status'                            => 'Booking status',
            'booking_status_pending'                    => 'Pending',
            'booking_status_confirmed'                  => 'Confirmed',
            'booking_status_cancelled'                  => 'Cancelled',
            'booking_status_after_payment'              => 'Booking status after payment',
            'page_after_paying'                         => 'Page redirected after paying',
            'payment_disable'                           => 'Disable payments',
            'paypal_allowed'                            => 'Allow Paypal payments',
            'paypal_email'                              => 'Paypal email address',
            'authorizedotnet_allowed'                   => 'Allow Authorize.net payments',
            'cash_allowed'                              => 'Allow cash payments',
            'credit_card_allowed'                       => 'Allow credit card payments',
            'email_address'                             => 'Notification email address',
            'confirmation_sent'                         => 'Send confirmation email',
            'confirmation_sent_none'                    => 'None',
            'confirmation_sent_after_booking'           => 'After booking',
            'confirmation_sent_after_payment'           => 'After payment',
            'confirmation_subject'                      => 'Confirmation email subject',
            'confirmation_subject_default'              => 'Confirmation message',
            'confirmation_content'                      => 'Confirmation email content',
            'confirmation_content_default'              => $confirmationTokens,
            'payment_confirmation_sent'                 => 'Send payment confirmation email',
            'payment_confirmation_subject'              => 'Payment confirmation email subject',
            'payment_confirmation_subject_default'      => 'Payment confirmation message',
            'payment_confirmation_content'              => 'Payment confirmation email content',
            'payment_confirmation_content_default'      => $confirmationTokens,
            'enquiry_sent'                              => 'Send enquiry email',
            'enquiry_subject'                           => 'Enquiry email subject',
            'enquiry_subject_default'                   => 'Enquiry message',
            'enquiry_content'                           => 'Enquiry email content',
            'enquiry_content_default'                   => $enquiryTokens,
            'form'                                      => 'Booking Form',
            'required'                                  => 'Yes (Required)',
            'first_name'                                => 'First Name',
            'last_name'                                 => 'Last Name',
            'phone'                                     => 'Phone',
            'email'                                     => 'Email',
            'company'                                   => 'Company',
            'address'                                   => 'Address',
            'zip'                                       => 'Zip code',
            'city'                                      => 'City',
            'country'                                   => 'Country',
            'notes'                                     => 'Notes',
            'voucher'                                   => 'Voucher',
            'capcha'                                    => 'Capcha',
        ],
        'working_time'  => [
            'index'         => 'Working Time',
            'days_of_week'  => 'Week day',
            'start_time'    => 'Start time',
            'end_time'      => 'End time',
            'day_off'       => 'Day off',
        ],
    ],
];
