<?php

//---------------------------- Confirmation ----------------------------//
$confirmEmailConsumer = <<< HTML
Sveicināti!

Paldies par veikto rezervāciju!

Pakalpojums:
{Services}

Depozīts jāmaksā: {Deposit}

Esiet laipni aicināti!
HTML;

$confirmEmailEmployee = <<< HTML
Jums ir jauna rezervācija !


{Services}
{Name}
{Phone}
{Email}
Depozīts jāmaksā: {Deposit}


Piezīmes:
{Notes}
HTML;

$confirmSmsEmployee = <<< HTML
Sveiki,
Jums ir jauna rezervācija {Consumer} pakalpojums {Services}
Depozīts jāmaksā: {Deposit}
HTML;

$confirmSmsConsumer = <<< HTML
Sveicināti!

Paldies par veikto rezervāciju!
{Services}
Depozīts jāmaksā: {Deposit}

Ar cieņu.
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
                    'default' => 'Paldies par rezervāciju!'
                ],
                'confirm_tokens_client' => [
                    'type' => 'Textarea',
                    'default' => $confirmEmailConsumer
                ],
                'confirm_subject_employee' => [
                    'type' => 'Text',
                    'default' => 'Jauna rezervācija!'
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
