<?php
$confirmBody = <<< HTML
<p>Sveicināti %s,</p>
<p>Lūdzu, aktivizējiet šo saiti, lai pabeigtu reģistrāciju: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Paldies!</p>
HTML;

$resetBody = <<< HTML
<p>Sveicināti %s,</p>
<p>Kāds (iespējams, jūs) ir pieprasījis jaunu paroli. </p>
<p>Lai mainītu paroli, lūdzu, aktivizējiet šo saiti: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Ja tas nebijāt jūs, ignorējiet šo ziņu.</p>
<p>Paldies!</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Lūdzu, apstipriniet reģistrāciju',
            'title'   => 'Paldies, ka reģistrējāties!',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Paroles maiņa',
            'title'   => 'Atiestatiet savu paroli',
            'body'    => $resetBody
        ]
    ],
];
