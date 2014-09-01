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
                'custom_status'   => [
                    'type'    => 'Checkbox',
                ],
                'layout'          => [
                    'type'         => 'dropdown',
                    'values'       => [1 => 'Layout 1', 2 => 'Layout 2', 3 => 'Layout 3'],
                    'default'      => 1,
                    'key_is_value' => false
                ],
                'seo_url' => [
                    'type' => 'Checkbox',
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
                ],
                'time_format' => [
                    'type' => 'DateTimeDropdown',
                    'values' => ['H:i', 'G:i', 'h:i', 'h:i a', 'h:i A', 'g:i', 'g:i a', 'g:i A'],
                    'default' => 'H:i',
                ],
                'week_numbers' => [
                    'type' => 'Checkbox',
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
                    'type' => 'Checkbox',
                    'default' => true
                ],
                'hide_prices' => [
                    'type' => 'Checkbox',
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
                'address_1' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'address_2' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'captcha' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'city' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'country' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'email' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 3,
                    'key_is_value' => false
                ],
                'name' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 3,
                    'key_is_value' => false
                ],
                'notes' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'phone' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 3,
                    'key_is_value' => false
                ],
                'state' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'terms' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'key_is_value' => false
                ],
                'zip' => [
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
            'reminders' => [],

        ]
    ]
];
