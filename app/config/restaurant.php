<?php
return [
    'options'   => [
        'general'   => [
            'index'     => [
                'currency'      => [
                    'type'    => 'Dropdown',
                    'values'  => ['AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BOV', 'BRL', 'BSD', 'BTN', 'BWP', 'BYR', 'BZD', 'CAD', 'CDF', 'CHE', 'CHF', 'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EEK', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LTL', 'LVL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MXV', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'USS', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XAG', 'XAU', 'XBA', 'XBB', 'XBC', 'XBD', 'XCD', 'XDR', 'XFU', 'XOF', 'XPD', 'XPF', 'XPT', 'XTS', 'XXX', 'YER', 'ZAR', 'ZMK', 'ZWL'],
                    'default' => 'EUR',
                ],
                'date_format'     => [
                    'type' => 'DateTimeDropdown',
                    'values' => ['d.m.Y', 'm.d.Y', 'Y.m.d', 'j.n.Y', 'n.j.Y', 'Y.n.j', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'j/n/Y', 'n/j/Y', 'Y/n/j', 'd-m-Y', 'm-d-Y', 'Y-m-d', 'j-n-Y', 'n-j-Y', 'Y-n-j'],
                    'default' => 'd-m-Y',
                ],
                'datetime_format' => [
                    'type'    => 'DateTimeDropdown',
                    'values'  => ['d.m.Y, H:i', 'd.m.Y, H:i:s', 'm.d.Y, H:i', 'm.d.Y, H:i:s', 'Y.m.d, H:i', 'Y.m.d, H:i:s', 'j.n.Y, H:i', 'j.n.Y, H:i:s', 'n.j.Y, H:i', 'n.j.Y, H:i:s', 'Y.n.j, H:i', 'Y.n.j, H:i:s', 'd/m/Y, H:i', 'd/m/Y, H:i:s', 'm/d/Y, H:i', 'm/d/Y, H:i:s', 'Y/m/d, H:i', 'Y/m/d, H:i:s', 'j/n/Y, H:i', 'j/n/Y, H:i:s', 'n/j/Y, H:i', 'n/j/Y, H:i:s', 'Y/n/j, H:i', 'Y/n/j, H:i:s', 'd-m-Y, H:i', 'd-m-Y, H:i:s', 'm-d-Y, H:i', 'm-d-Y, H:i:s', 'Y-m-d, H:i', 'Y-m-d, H:i:s', 'j-n-Y, H:i', 'j-n-Y, H:i:s', 'n-j-Y, H:i', 'n-j-Y, H:i:s', 'Y-n-j, H:i', 'Y-n-j, H:i:s'],
                    'default' => 'j/n/Y, H:i'
                ],
                'time_format' => [
                    'type' => 'DateTimeDropdown',
                    'values' => ['H:i', 'G:i', 'h:i', 'h:i a', 'h:i A', 'g:i', 'g:i a', 'g:i A'],
                    'default' => 'H:i',
                ],
                'timezone' => [
                    'type' => 'TimezoneDropdown',
                    'default' => 'Europe/Helsinki'
                ],
            ],
        ],
        'booking'   => [
            'index'     => [
                'price'             => [
                    'type'              => 'Text',
                    'default'           => 50
                ],
                'length'            => [
                    'type'              => 'Text',
                    'default'           => 180
                ],
                'hours_before'      => [
                    'type'              => 'Text',
                    'default'           => 2
                ],
                'booking_type'      => [
                    'type'              => 'Dropdown',
                    'values'            => [
                        'categories'        => trans('rb.options.booking.booking_type_categories'),
                        'time'              => trans('rb.options.booking.booking_type_time')
                    ],
                    'default'           => trans('rb.options.booking.booking_type_time')
                ],
                'group_minimum'     => [
                    'type'              => 'Text',
                    'default'           => 10
                ],
                'booking_status'    => [
                    'type'              => 'Dropdown',
                    'values'            => [
                        'pending'           => trans('rb.options.booking.booking_status_pending'),
                        'confirmed'         => trans('rb.options.booking.booking_status_confirmed'),
                        'cancelled'         => trans('rb.options.booking.booking_status_cancelled')
                    ],
                    'default'           => trans('rb.options.booking.booking_status_pending')
                ],
                'booking_status_after_payment'  => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'pending'           => trans('rb.options.booking.booking_status_pending'),
                        'confirmed'         => trans('rb.options.booking.booking_status_confirmed'),
                        'cancelled'         => trans('rb.options.booking.booking_status_cancelled')
                    ],
                    'default'   => trans('rb.options.booking.booking_status_confirmed')
                ],
                'page_after_paying' => [
                    'type'      => 'Text',
                    'default'   => 'http://varaa.com/'
                ],
                'payment_disable'   => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'    => trans('common.no'),
                    ],
                    'default'   => trans('common.no')
                ],
                'paypal_allowed'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'    => trans('common.no'),
                    ],
                    'default'   => trans('common.yes')
                ],
                'paypal_email'  => [
                    'type'      => 'Text',
                    'default'   => 'paypal@domain.com'
                ],
                'authorizedotnet_allowed' => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'    => trans('common.no'),
                    ],
                    'default'   => trans('common.no')
                ],
                'cash_allowed'  => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'    => trans('common.no'),
                    ],
                    'default'   => trans('common.yes')
                ],
                'credit_card_allowed'   => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'    => trans('common.no'),
                    ],
                    'default'   => trans('common.yes')
                ],
            ],
            'confirmation'  => [
                'email_address' => [
                    'type'      => 'Text',
                    'default'   => 'info@domain.com',
                ],
                'confirmation_sent' => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'none'          => trans('rb.options.booking.confirmation_sent_none'),
                        'after_booking' => trans('rb.options.booking.confirmation_sent_after_booking'),
                        'after_payment' => trans('rb.options.booking.confirmation_sent_after_payment'),
                    ],
                    'default'   => trans('rb.options.booking.confirmation_sent_after_booking')
                ],
                'confirmation_subject' => [
                    'type'      => 'Text',
                    'default'   => trans('rb.options.booking.confirmation_subject_default')
                ],
                'confirmation_content' => [
                    'type'      => 'Textarea',
                    'default'   => trans('rb.options.booking.confirmation_content_default')
                ],
                'payment_confirmation_sent' => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'none'          => trans('rb.options.booking.confirmation_sent_none'),
                        'after_payment' => trans('rb.options.booking.confirmation_sent_after_payment'),
                    ],
                    'default'   => trans('rb.options.booking.confirmation_sent_none')
                ],
                'payment_confirmation_subject'  => [
                    'type'      => 'Text',
                    'default'   => trans('rb.options.booking.payment_confirmation_subject_default')
                ],
                'payment_confirmation_content' => [
                    'type'      => 'Textarea',
                    'default'   => trans('rb.options.booking.payment_confirmation_content_default')
                ],
                'enquiry_sent' => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'none'          => trans('rb.options.booking.confirmation_sent_none'),
                        'after_booking' => trans('rb.options.booking.confirmation_sent_after_booking'),
                    ],
                    'default'   => trans('rb.options.booking.confirmation_sent_after_booking')
                ],
                'enquiry_subject'  => [
                    'type'      => 'Text',
                    'default'   => trans('rb.options.booking.enquiry_subject_default')
                ],
                'enquiry_content' => [
                    'type'      => 'Textarea',
                    'default'   => trans('rb.options.booking.enquiry_content_default')
                ],
            ],
            'form'  => [
                'first_name'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('rb.options.booking.required')
                ],
                'last_name'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('rb.options.booking.required')
                ],
                'phone'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('rb.options.booking.required')
                ],
                'email'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('rb.options.booking.required')
                ],
                'company'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.no')
                ],
                'address'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.no')
                ],
                'zip'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.no')
                ],
                'city'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.no')
                ],
                'country'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.no')
                ],
                'notes'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.no')
                ],
                'voucher'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('common.yes')
                ],
                'capcha'    => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'no'        => trans('common.no'),
                        'yes'       => trans('common.yes'),
                        'required'  => trans('rb.options.booking.required'),
                    ],
                    'default'   => trans('rb.options.booking.required')
                ],
            ],
            'terms' => [
                'terms_content' => [
                    'type'  => 'Textarea',
                ],
                'terms_enquiry_content'   => [
                    'type'  => 'Textarea',
                ]
            ],
            'reminder'  => [
                'reminder_enable'   => [
                    'type'      => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.yes')
                ],
                'cron_script'   => [
                    'type'      => 'Textarea',
                    'values'    => 'You need to set up a cron job using your hosting account control panel which should execute every hour. Depending on your web server you should use either the URL or script path.

Server path:
/restaurantcron.php

URL:
./cron.php',
                ],
                'send_reminder_time'    => [
                    'type'      => 'Text',
                    'default'   => '2',
                ],
                'reminder_email_subject'    => [
                    'type'      => 'Text',
                    'values'    => trans('rb.options.booking.reminder_email_subject_default'),
                ],
                'reminder_email_body'       => [
                    'type'      => 'Textarea',
                    'default'   => trans('rb.options.booking.reminder_email_body_default'),
                ],
                'send_sms_time' => [
                    'type'      => 'Text',
                    'default'   => '1',
                ],
                'sms_country_code'  => [
                    'type'      => 'Text',
                    'default'   => '358',
                ],
                'sms_messgage'  => [
                    'type'      => 'Textarea',
                    'default'   => trans('rb.options.booking.reminder_sms_message_default'),
                ],
            ],
            'form_fields'   => [
                'first_name' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.yes'),
                ],
                'last_name' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.yes'),
                ],
                'phone' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.yes'),
                ],
                'email' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.yes'),
                ],
                'company' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.no'),
                ],
                'address' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.no'),
                ],
                'city' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.no'),
                ],
                'zip' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.no'),
                ],
                'country' => [
                    'type'  => 'Dropdown',
                    'values'    => [
                        'yes'   => trans('common.yes'),
                        'no'   => trans('common.no'),
                    ],
                    'default'   => trans('common.no'),
                ],
            ],
        ],
        'working_time' => [
            'index' => [
                'working_time' => [
                    'default' => [
                        'mon' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                        'tue' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                        'wed' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                        'thu' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                        'fri' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                        'sat' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                        'sun' => ['start' => '08:00', 'end' => '20:00', 'off' => '0'],
                    ]
                ]
            ]
        ],
        'style' => [
            'index' => [
                'style_logo' => [
                    'type' => 'Text'
                ],
                'style_banner' => [
                    'type' => 'Text'
                ],
                'style_heading_color' => [
                    'type' => 'Text'
                ],
                'style_heading_background' => [
                    'type' => 'Text'
                ],
                'style_main_color' => [
                    'type' => 'Text'
                ],
                'style_background' => [
                    'type' => 'Text'
                ],
                'style_custom_css' => [
                    'type' => 'Textarea'
                ],
            ],
        ],
    ],
];
