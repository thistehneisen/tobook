<?php
return [
    'options' => [
        'general' => [
            // Index section
            'index' => [
                'layout'          => [
                    'type'         => 'Dropdown',
                    'values'       => [1 => 'Layout 1', 2 => 'Layout 2', 3 => 'Layout 3'],
                    'default'      => 1,
                    'flipValues' => false
                ],
                'currency'        => [
                    'type'    => 'Dropdown',
                    'values'  => ['AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BOV', 'BRL', 'BSD', 'BTN', 'BWP', 'BYR', 'BZD', 'CAD', 'CDF', 'CHE', 'CHF', 'CHW', 'CLF', 'CLP', 'CNY', 'COP', 'COU', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EEK', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK', 'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LTL', 'LVL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MWK', 'MXN', 'MXV', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STD', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'USN', 'USS', 'UYU', 'UZS', 'VEF', 'VND', 'VUV', 'WST', 'XAF', 'XAG', 'XAU', 'XBA', 'XBB', 'XBC', 'XBD', 'XCD', 'XDR', 'XFU', 'XOF', 'XPD', 'XPF', 'XPT', 'XTS', 'XXX', 'YER', 'ZAR', 'ZMK', 'ZWL'],
                    'default' => 'EUR',
                ],
                'timezone' => [
                    'type' => 'TimezoneDropdown',
                    'default' => 'Europe/Helsinki'
                ],
                // 'datetime_format' => [
                //     'type'    => 'DateTimeDropdown',
                //     'values'  => ['d.m.Y, H:i', 'd.m.Y, H:i:s', 'm.d.Y, H:i', 'm.d.Y, H:i:s', 'Y.m.d, H:i', 'Y.m.d, H:i:s', 'j.n.Y, H:i', 'j.n.Y, H:i:s', 'n.j.Y, H:i', 'n.j.Y, H:i:s', 'Y.n.j, H:i', 'Y.n.j, H:i:s', 'd/m/Y, H:i', 'd/m/Y, H:i:s', 'm/d/Y, H:i', 'm/d/Y, H:i:s', 'Y/m/d, H:i', 'Y/m/d, H:i:s', 'j/n/Y, H:i', 'j/n/Y, H:i:s', 'n/j/Y, H:i', 'n/j/Y, H:i:s', 'Y/n/j, H:i', 'Y/n/j, H:i:s', 'd-m-Y, H:i', 'd-m-Y, H:i:s', 'm-d-Y, H:i', 'm-d-Y, H:i:s', 'Y-m-d, H:i', 'Y-m-d, H:i:s', 'j-n-Y, H:i', 'j-n-Y, H:i:s', 'n-j-Y, H:i', 'n-j-Y, H:i:s', 'Y-n-j, H:i', 'Y-n-j, H:i:s'],
                //     'default' => 'j/n/Y, H:i'
                // ],
                // 'date_format'     => [
                //     'type' => 'DateTimeDropdown',
                //     'values' => ['d.m.Y', 'm.d.Y', 'Y.m.d', 'j.n.Y', 'n.j.Y', 'Y.n.j', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'j/n/Y', 'n/j/Y', 'Y/n/j', 'd-m-Y', 'm-d-Y', 'Y-m-d', 'j-n-Y', 'n-j-Y', 'Y-n-j'],
                //     'default' => 'd-m-Y',
                // ],
                // 'time_format' => [
                //     'type' => 'DateTimeDropdown',
                //     'values' => ['H:i', 'G:i', 'h:i', 'h:i a', 'h:i A', 'g:i', 'g:i a', 'g:i A'],
                //     'default' => 'H:i',
                // ],
                // 'week_numbers' => [
                //     'type' => 'Radio'
                // ],
                // 'week_start' => [
                //     'type' => 'Dropdown',
                //     'values' => [
                //         trans('common.sun'),
                //         trans('common.mon'),
                //         trans('common.tue'),
                //         trans('common.wed'),
                //         trans('common.thu'),
                //         trans('common.fri'),
                //         trans('common.sat'),
                //     ],
                //     'default' => 1,
                //     'flipValues' => false
                // ],
            ]
        ],
        'booking' => [
            'index' => [
                // 'accept_bookings' => [
                //     'type' => 'Radio',
                // ],
                'hide_prices' => [
                    'type' => 'Radio',
                    'default' => false
                ],
                // 'status_if_not_paid' => [
                //     'type' => 'Dropdown',
                //     'values' => [
                //         'confirmed' => trans('as.options.booking.confirmed'),
                //         'pending'   => trans('as.options.booking.pending')
                //     ],
                //     'default' => 'confirmed'
                // ],
                // 'status_if_paid' => [
                //     'type' => 'Dropdown',
                //     'values' => [
                //         'confirmed' => trans('as.options.booking.confirmed'),
                //         'pending'   => trans('as.options.booking.pending')
                //     ],
                //     'default' => 'confirmed'
                // ],
                // 'step' => [
                //     'type' => 'Dropdown',
                //     'values' => [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60],
                //     'default' => 15
                // ],
                // 'bookable_date' => [
                //     'type' => 'Text',
                //     'options' => ['class' => 'form-control input-sm date-picker']
                // ]
            ],
            'confirmations' => [
                'confirm_email_enable' => [
                    'type'   => 'Radio',
                    'default' => true
                ],
                'confirm_sms_enable' => [
                    'type'   => 'Radio',
                    'default' => true
                ],
                'confirm_subject_client' => [
                    'type' => 'Text',
                    'default' => trans('as.options.booking.confirm_subject_client_default')
                ],
                'confirm_tokens_client' => [
                    'type' => 'Textarea',
                    'default' => trans('as.options.booking.confirm_tokens_client_default')
                ],
                'confirm_subject_employee' => [
                    'type' => 'Text',
                    'default' => trans('as.options.booking.confirm_subject_employee_default')
                ],
                'confirm_tokens_employee' => [
                    'type' => 'Textarea',
                    'default' => trans('as.options.booking.confirm_tokens_employee_default')
                ],
                'confirm_subject_admin' => [
                    'type' => 'Text',
                    'default' => trans('as.options.booking.confirm_subject_admin_default')
                ],
                'confirm_tokens_admin' => [
                    'type' => 'Textarea',
                    'default' => trans('as.options.booking.confirm_tokens_admin_default')
                ],
                'confirm_sms_country_code' => [
                    'type' => 'Text',
                    'default' => '358',
                ],
                'confirm_consumer_sms_message' => [
                    'type' => 'Textarea',
                    'default' => trans('as.options.booking.confirm_consumer_body_sms_message_default'),
                ],
                'confirm_employee_sms_message' => [
                    'type' => 'Textarea',
                    'default' => trans('as.options.booking.confirm_employee_body_sms_message_default'),
                ],
            ],
            // 'reminders' => [
            //     'reminder_enable' => [
            //         'type' => 'Radio',
            //     ],
            //     'reminder_email_before' => [
            //         'type' => 'Spinner',
            //         'values' => 10,
            //         'options' => ['class' => 'form-control input-sm spinner', 'data-positive' => 'true']
            //     ],
            //     'reminder_subject' => [
            //         'type' => 'Text',
            //         'values' => trans('as.options.booking.reminder_subject_default')
            //     ],
            //     'reminder_body' => [
            //         'type' => 'Textarea',
            //         'values' => trans('as.options.booking.reminder_body_default')
            //     ],
            //     'reminder_sms_hours' => [
            //         'type' => 'Spinner',
            //         'values' => 2,
            //         'options' => ['class' => 'form-control input-sm spinner', 'data-positive' => 'true']
            //     ],
            //     'reminder_sms_country_code' => [
            //         'type' => 'Text',
            //         'values' => '358',
            //     ],
            //     'reminder_sms_message' => [
            //         'type' => 'Textarea',
            //         'values' => trans('as.options.booking.reminder_sms_message_default'),
            //     ],
            // ],
            'terms' => [
                'terms_enabled' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'flipValues' => false
                ],
                'terms_url' => [
                    'type' => 'Text'
                ],
                'terms_body' => [
                    'type' => 'Textarea',
                    'values' => trans('as.options.booking.terms_body_default')
                ]
            ],
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
                // 'style_text_color' => [
                //     'type' => 'Text'
                // ],
                'style_main_color' => [
                    'type' => 'Text'
                ],
                'style_background' => [
                    'type' => 'Text'
                ],
                'style_custom_css' => [
                    'type' => 'Textarea'
                ],
            ]
        ],
        // Custom options with no form definition
        'working_time' => [
            'index' => [
                'working_time' => [
                    'default' => [
                        'mon' => ['start' => '08:00', 'end' => '20:00'],
                        'tue' => ['start' => '08:00', 'end' => '20:00'],
                        'wed' => ['start' => '08:00', 'end' => '20:00'],
                        'thu' => ['start' => '08:00', 'end' => '20:00'],
                        'fri' => ['start' => '08:00', 'end' => '20:00'],
                        'sat' => ['start' => '08:00', 'end' => '20:00'],
                        'sun' => ['start' => '08:00', 'end' => '20:00'],
                    ]
                ]
            ]
        ],
    ]
];
