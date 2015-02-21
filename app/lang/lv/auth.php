<?php
$confirmBody = <<< HTML
<p>Sveicinâti %s,</p>
<p>Lûdzu, aktivizçjiet ðo saiti, lai pabeigtu reìistrâciju: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Paldies!</p>
HTML;

$resetBody = <<< HTML
<p>Sveicinâti %s,</p>
<p>Kâds (iespçjams, jûs) ir pieprasîjis jaunu paroli. </p>
<p>Lai mainîtu paroli, lûdzu, aktivizçjiet ðo saiti: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Ja tas nebijât jûs, ignorçjiet ðo ziòu.</p>
<p>Paldies!</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Lûdzu, apstipriniet reìistrâciju',
            'title'   => 'Paldies, ka reìistrçjâties!',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Paroles maiòa',
            'title'   => 'Atiestatiet savu paroli',
            'body'    => $resetBody
        ]
    ],
];
