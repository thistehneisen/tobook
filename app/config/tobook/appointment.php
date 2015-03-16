<?php

//---------------------------- Confirmation ----------------------------//
$confirmEmailConsumer = <<< HTML
Hi!

Thank you for booking an appointment!

Service: {Services}

Welcome!

This service is provided by Tobook.lv
HTML;

$confirmEmailEmployee = <<< HTML
Hi!

You have a new booking:

Service:
{Services}

Consumer information:
Name: {Name}
Phone: {Phone}
Email: {Email}

Notes:
{Notes}
HTML;

$confirmSmsConsumer = <<< HTML
Hi!

Thank you for booking an appointment!

{Services}

Best Regards.
HTML;

//---------------------------- Reminder ----------------------------//
$reminderEmail = <<< HTML
Hej, {Name},
Detta är en påminnelse om din bokning:

Boknings ID: {BookingID}

Tjänster

{Services}

Hälsningar,
HTML;

$reminderSms = <<< HTML
Hej,

Tack för att du bokat:

{Services}

Hälsningar,
HTML;

//---------------------------- Terms ----------------------------//
$terms = <<< HTML
Villkor för bokning

En bokning träder i kraft när kunden har gjort en reservation och fått en bekräftelse via telefon eller e-post. Leverantören förväntas tillhandahålla den tjänst kunden bokat och, i annat fall, kommunicera detta till kunden.

Vi kan använda din e-postadress för att skicka ut fördelaktiga erbjudanden och nyheter till dig.

Villkor för avbokning

Avbokning ska ske senast 48 timmar innan den reserverade tiden. I annat fall förbehåller vi oss rätten att ta ut full betalning för tjänsten enligt aktuell prislista.
HTML;

return [
    'options' => [
        'booking' => [
            'booking_form' => [
                'terms_body' => [
                    'type' => 'Textarea',
                    'default' => $terms
                ],
            ],
            'confirmations' => [
                'confirm_subject_client' => [
                    'type' => 'Text',
                    'default' => 'Tack för din bokning'
                ],
                'confirm_tokens_client' => [
                    'type' => 'Textarea',
                    'default' => $confirmEmailConsumer
                ],
                'confirm_subject_employee' => [
                    'type' => 'Text',
                    'default' => 'Ny bokning'
                ],
                'confirm_tokens_employee' => [
                    'type' => 'Textarea',
                    'default' => $confirmEmailEmployee
                ],
                'confirm_consumer_sms_message' => [
                    'type' => 'Textarea',
                    'default' => $confirmSmsConsumer
                ],
                'confirm_employee_sms_message' => [
                    'type' => 'Textarea',
                    'default' => $confirmSmsEmployee
                ],
            ]
        ]
    ]
];
