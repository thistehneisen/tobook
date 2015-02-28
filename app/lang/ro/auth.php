<?php
$confirmBody = <<< HTML
<p>Buna ziua %s,</p>
<p>Va rugam să faceti clic pe adresa URL de mai jos pentru a finaliza inregistrarea: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Va multumim</p>
HTML;

$resetBody = <<< HTML
<p>Buna ziua %s,</p>
<p>Cineva (probabil dvs.) a solicitat o noua parola.</p>
<p>Va rugam să faceti clic pe adresa URL de mai jos pentru a schimba parola: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Daca nu dvs. ati solicitat acest lucru, va rugam ignorati acest mesaj.</p>
<p>Va multumim</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Va rugam sa confirmati contul dvs.',
            'title'   => 'Va multumim pentru inregistrarea cu noi',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Resetare parola',
            'title'   => 'Schimba parola',
            'body'    => $resetBody
        ]
    ],
];
