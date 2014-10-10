<?php

// TODO
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

// TODO
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
        'index'     => 'Pöydät',
        'all'       => 'Kaikki pöydät',
        'add'       => 'Lisää pöydän',
        'name'      => 'Pöydän nimi',
        'seats'     => 'Paikkojen lukumäärä',
        'minimum'   => 'Vähimmäismäärä',
        'edit'      => 'Muokkaa pöydän',
    ],
    'groups'    => [
        'index'         => 'Ryhmät',
        'all'           => 'Kaikki ryhmät',
        'add'           => 'Lisää ryhmän',
        'name'          => 'Ryhmän nimi',
        'description'   => 'Kuvaus',
        'edit'          => 'Muokkaa ryhmän',
        'tables'        => 'Pöydät',
    ],
    'services'  => [
        'index'         => 'Palvelut',
        'all'           => 'Kailli palvelut',
        'add'           => 'Add new palvelun',
        'name'          => 'Palvelun nimi',
        'start_at'      => 'Alkaa',
        'end_at'        => 'Loppuu',
        'length'        => 'Pituus',
        'price'         => 'Hinta',
        'edit'          => 'Muokkaa palvelun',
    ],
    'menus'     => [
        'index'         => 'Listat',
        'all'           => 'Kaikki listat',
        'add'           => 'Lisää listan',
        'name'          => 'Listan nimi',
        'type'          => 'Listan tyypi',
        'edit'          => 'Muokkaa listan',
    ],
    'bookings'  => [
        'index'             => 'Varaukset',
        'all'               => 'Kaikki varaukset',
        'add'               => 'Lisää varauksen',
        'edit'              => 'Muokkaa varauksen',
        'uuid'              => 'UUID',
        'date'              => 'Päivämäärä',
        'start_at'          => 'Alkaa',
        'end_at'            => 'Loppuu',
        'status'            => 'Tila',
        'note'              => 'Note', // TODO
        'total'             => 'Yhteensä',
        'is_group_booking'  => 'Ryhmän varaus',
        'source'            => 'Source', // TODO
    ],
    'options'   => [
        'index'             => 'Asetukset',
        'updated'           => 'Asetukset päivitetty',
        'general'       => [
            'index'             => 'Yleinen',
            'currency'          => 'Valuutta',
            'date_format'       => 'Päivämäärän muoto',
            'datetime_format'   => 'Datetime format', // TODO
            'time_format'       => 'Ajan muoto',
            'timezone'          => 'Aikavyöhyke',
        ],
        'booking'       => [ // TODO
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
        'working_time'  => [ // TODO
            'index'         => 'Working Time',
            'days_of_week'  => 'Week day',
            'start_time'    => 'Start time',
            'end_time'      => 'End time',
            'day_off'       => 'Day off',
        ],
    ],
];
