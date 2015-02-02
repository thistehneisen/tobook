<?php

//---------------------------- Confirmation ----------------------------//
$confirmEmailConsumer = <<< HTML
Hej!

Tack för din bokning!

Valda tjänster:
{Services}

**Avbokning måste ske senast 48 timmar innan den reserverade tiden.

Välkommen!



Tjänsten tillhandahålls av ClearSense
HTML;

$confirmEmailEmployee = <<< HTML
Hej!

Du har fått en ny bokning:

ID: {BookingID}

Tjänster:
{Services}

Kundinformation
Namn: {Name}
Telefon: {Phone}
E-post: {Email}

Ytterligare information:
{Notes}
HTML;

$confirmSmsConsumer = <<< HTML
Hej,

Tack för att du bokat:

{Services}

Hälsningar,
HTML;

$confirmSmsEmployee = <<< HTML
Hej,

Du har en ny bokning med {Consumer}. Tjänst: {Services}

Hälsningar,
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
