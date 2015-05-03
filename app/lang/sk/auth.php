<?php
$confirmBody = <<< HTML
<p>Dobrý deň %s,</p>
<p>Prosím, kliknite na URL nižšie pre dokončenie registrácie: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Ďakujeme</p>
HTML;

$resetBody = <<< HTML
<p>Dobrý deň %s,</p>
<p>Niekto (pravdepodobne Vy) ste žiadali o nové heslo.</p>
<p>Prosím, kliknite na URL nižšie pre zmenu hesla: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Ak ste o zmenu nežiadali, ignorujte tento email.</p>
<p>Ďakujeme</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Prosím, aktivujte si svoj účet',
            'title'   => 'Ďakujeme za registráciu',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Reset hesla',
            'title'   => 'Reset hesla',
            'body'    => $resetBody
        ]
    ],
];
