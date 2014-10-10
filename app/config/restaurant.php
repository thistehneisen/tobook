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
                    'default'           => trans('rb.options.booking.price_default')
                ],
                'length'            => [
                    'type'              => 'Text',
                    'default'           => trans('rb.options.booking.length_default')
                ],
                'hours_before'      => [
                    'type'              => 'Text',
                    'default'           => trans('rb.options.booking.hours_before_default')
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
                    'default'           => trans('rb.options.booking.group_minimum_default')
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
        ],
    ],
];
