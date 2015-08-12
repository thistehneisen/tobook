<?php

//---------------------------- Confirmation ----------------------------//
$confirmEmailConsumer = <<< HTML
Hei!

Kiitos varauksestasi!

Valitut palvelut:
{Services}

**Mikäli peruutat varauksen se tulee tehdä 48 tuntia ennen varattua aikaa.

Tervetuloa!



Palvelun tarjoaa varaa.com
HTML;

$confirmEmailEmployee = <<< HTML
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

$confirmSmsConsumer = <<< HTML
Hei,

Kiitos varauksestasi palveluun:

{Services}

Terveisin,
HTML;

$confirmSmsEmployee = <<< HTML
Hei,

Sinulle on uusi varaus asiakkaalta {Consumer} palveluun {Services}

Terveisin,
HTML;

//---------------------------- Reminder ----------------------------//
$reminderEmail = <<< HTML
Hei {Name},
Tämä on muistutusviesti varauksestasi!

Varaus id: {BookingID}

Palvelut

{Services}

Terveisin,
HTML;

$reminderSms = <<< HTML
Hei,

Kiitos varauksestasi palveluun:

{Services}

Terveisin,
HTML;

//---------------------------- Terms ----------------------------//
$terms = <<< HTML
Varausehdot

Varaus tulee sitovasti voimaan, kun asiakas on tehnyt varauksen ja saanut siitä vahvistuksen joko puhelimitse tai kirjallisesti sähköpostitse. Palveluntarjoaja kantaa kaiken vastuun palvelun tuottamisesta ja hoitaa tarvittaessa kaiken yhteydenpidon asiakkaisiin.

Peruutusehdot

Varaajalla on oikeus peruutus- ja varausehtojen puitteissa peruuttaa varauksensa ilmoittamalla siitä puhelimitse vähintään 48h ennen palveluajan alkamista. Muutoin paikalle saapumatta jättämisestä voi palveluntarjoaja halutessaan periä voimassaolevan hinnastonsa mukaisen palvelukorvauksen.
HTML;

return [
    'options' => [
        'booking' => [
            'index' => [
                'hide_prices' => [
                    'type' => 'Radio',
                    'default' => false
                ],
                'auto_select_employee' => [
                    'type' => 'Radio',
                    'default' => false
                ],
                'auto_expand_all_categories' => [
                    'type' => 'Radio',
                    'default' => false
                ],
                'show_employee_request' => [
                    'type' => 'Radio',
                    'default' => true
                ],
                'default_nat_service' => [
                    'type' => 'DefaultNatDropdown',
                    'default' => -1
                ],
                'min_distance' => [
                    'type' => 'Spinner',
                    'default' => '0',
                    'options' => ['class' => 'form-control input-sm spinner', 'data-positive' => 'true']
                ],
                'max_distance' => [
                    'type' => 'Spinner',
                    'default' => '0',
                    'options' => ['class' => 'form-control input-sm spinner', 'data-positive' => 'true']
                ],
            ],
            'booking_form' => [
                'email' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 2,
                    'flipValues' => false
                ],
                'notes' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'flipValues' => false
                ],
                'address' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'flipValues' => false
                ],
                'city' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'flipValues' => false
                ],
                'postcode' =>[
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'flipValues' => false
                ],
                'country' => [
                    'type' => 'Dropdown',
                    'values' => [
                        1 => trans('common.no'),
                        2 => trans('common.yes'),
                        3 => trans('common.yes_required'),
                    ],
                    'default' => 1,
                    'flipValues' => false
                ],
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
                'factor' => [
                    'type' => 'Text',
                ],
                'terms_body' => [
                    'type' => 'Textarea',
                    'default' => $terms
                ],
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
                    'default' => 'Kiitos varauksestasi'
                ],
                'confirm_tokens_client' => [
                    'type' => 'Textarea',
                    'default' => $confirmEmailConsumer
                ],
                'confirm_subject_employee' => [
                    'type' => 'Text',
                    'default' => 'Uusi varaus on saapunut'
                ],
                'confirm_tokens_employee' => [
                    'type' => 'Textarea',
                    'default' => $confirmEmailEmployee
                ],
                'confirm_consumer_sms_message' => [
                    'type' => 'Textarea',
                    'default' => $confirmSmsConsumer,
                ],
                'confirm_employee_sms_message' => [
                    'type' => 'Textarea',
                    'default' => $confirmSmsEmployee
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
            //         'values' => 'Muistutus varauksestasi'
            //     ],
            //     'reminder_body' => [
            //         'type' => 'Textarea',
            //         'values' => $reminderEmail
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
            //         'values' => $reminderSms,
            //     ],
            // ],
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
                'style_external_css' => [
                    'type' => 'Text',
                    'admin_only'=> true
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
    ],

    // employee configs
    'employee' => [
        'default_time' => [
            ['type' => 'mon', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
            ['type' => 'tue', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
            ['type' => 'wed', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
            ['type' => 'thu', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
            ['type' => 'fri', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
            ['type' => 'sat', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
            ['type' => 'sun', 'start_at' => '08:00', 'end_at' => '18:00', 'is_day_off' => false],
        ]
    ],
];
