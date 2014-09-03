<?php
return [
    'options' => [
        'general' => [
            // Index section
            'index' => [
                'currency'        => [
                    'type'    => 'Dropdown',
                    'values'  => ['AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BOV', 'BRL', 'BSD', 'BTN', 'BWP', 'BYR', 'BZD', 'CAD', 'CDF', 'CHE', 'CHF', 'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EEK', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LTL', 'LVL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MXV', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'USS', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XAG', 'XAU', 'XBA', 'XBB', 'XBC', 'XBD', 'XCD', 'XDR', 'XFU', 'XOF', 'XPD', 'XPF', 'XPT', 'XTS', 'XXX', 'YER', 'ZAR', 'ZMK', 'ZWL'],
                    'default' => 'EUR',
                ],
                'layout'          => [
                    'type'         => 'Dropdown',
                    'values'       => [1 => 'Layout 1', 2 => 'Layout 2', 3 => 'Layout 3'],
                    'default'      => 1,
                    'key_is_value' => false
                ],
                'datetime_format' => [
                    'type'    => 'DateTimeDropdown',
                    'values'  => ['d.m.Y, H:i', 'd.m.Y, H:i:s', 'm.d.Y, H:i', 'm.d.Y, H:i:s', 'Y.m.d, H:i', 'Y.m.d, H:i:s', 'j.n.Y, H:i', 'j.n.Y, H:i:s', 'n.j.Y, H:i', 'n.j.Y, H:i:s', 'Y.n.j, H:i', 'Y.n.j, H:i:s', 'd/m/Y, H:i', 'd/m/Y, H:i:s', 'm/d/Y, H:i', 'm/d/Y, H:i:s', 'Y/m/d, H:i', 'Y/m/d, H:i:s', 'j/n/Y, H:i', 'j/n/Y, H:i:s', 'n/j/Y, H:i', 'n/j/Y, H:i:s', 'Y/n/j, H:i', 'Y/n/j, H:i:s', 'd-m-Y, H:i', 'd-m-Y, H:i:s', 'm-d-Y, H:i', 'm-d-Y, H:i:s', 'Y-m-d, H:i', 'Y-m-d, H:i:s', 'j-n-Y, H:i', 'j-n-Y, H:i:s', 'n-j-Y, H:i', 'n-j-Y, H:i:s', 'Y-n-j, H:i', 'Y-n-j, H:i:s'],
                    'default' => 'j/n/Y, H:i'
                ],
                'date_format'     => [
                    'type' => 'DateTimeDropdown',
                    'values' => ['d.m.Y', 'm.d.Y', 'Y.m.d', 'j.n.Y', 'n.j.Y', 'Y.n.j', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'j/n/Y', 'n/j/Y', 'Y/n/j', 'd-m-Y', 'm-d-Y', 'Y-m-d', 'j-n-Y', 'n-j-Y', 'Y-n-j'],
                    'default' => 'd-m-Y',
                ],
                'timezone' => [
                    'type' => 'TimezoneDropdown',
                    'default' => 'Europe/Helsinki'
                ],
                'time_format' => [
                    'type' => 'DateTimeDropdown',
                    'values' => ['H:i', 'G:i', 'h:i', 'h:i a', 'h:i A', 'g:i', 'g:i a', 'g:i A'],
                    'default' => 'H:i',
                ],
                'week_numbers' => [
                    'type' => 'Radio'
                ],
                'week_start' => [
                    'type' => 'Dropdown',
                    'values' => [
                        trans('common.sun'),
                        trans('common.mon'),
                        trans('common.tue'),
                        trans('common.wed'),
                        trans('common.thu'),
                        trans('common.fri'),
                        trans('common.sat'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
            ]
        ],
        'booking' => [
            'index' => [
                'accept_bookings' => [
                    'type' => 'Radio',
                ],
                'hide_prices' => [
                    'type' => 'Radio',
                    'default' => false
                ],
                'status_if_not_paid' => [
                    'type' => 'Dropdown',
                    'values' => [
                        'confirmed' => trans('as.options.booking.confirmed'),
                        'pending'   => trans('as.options.booking.pending')
                    ],
                    'default' => 'confirmed'
                ],
                'status_if_paid' => [
                    'type' => 'Dropdown',
                    'values' => [
                        'confirmed' => trans('as.options.booking.confirmed'),
                        'pending'   => trans('as.options.booking.pending')
                    ],
                    'default' => 'confirmed'
                ],
                'step' => [
                    'type' => 'Dropdown',
                    'values' => [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60],
                    'default' => 15
                ],
            ],
            'booking_form' => [
                'bf_address_1' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_address_2' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_captcha' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_city' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_country' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_email' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 3,
                    'key_is_value' => false
                ],
                'bf_name' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 3,
                    'key_is_value' => false
                ],
                'bf_notes' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_phone' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 3,
                    'key_is_value' => false
                ],
                'bf_state' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_terms' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'bf_zip' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
            ],
            'confirmations' => [],
            'reminders' => [
                'reminder_enable' => [
                    'type' => 'Radio',
                ],
                'reminder_email_before' => [
                    'type' => 'Spinner',
                    'values' => 10,
                    'options' => ['class' => 'form-control input-sm spinner', 'data-positive' => 'true']
                ],
                'reminder_subject' => [
                    'type' => 'Text',
                    'values' => ''
                ],
                'reminder_body' => [
                    'type' => 'Textarea',
                    'values' => '',
                ],
                'reminder_sms_hours' => [
                    'type' => 'Spinner',
                    'values' => 2,
                ],
                'reminder_sms_country_code' => [
                    'type' => 'Text',
                    'values' => '',
                ],
                'reminder_sms_message' => [
                    'type' => 'Textarea',
                    'values' => '',
                ],
            ],

        ]
    ]
];
