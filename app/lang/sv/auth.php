<?php
$confirmBody = <<< HTML
<p>Hej %s,</p>
<p>Klicka på länken nedan för att slutföra registreringen: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Tack!</p>
HTML;

$resetBody = <<< HTML
<p>Hej %s,</p>
<p>Någon (sannolikt du) har bett om ett nytt lösenord.</p>
<p>Klicka på länken nedan för att byta lösenord: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Om det inte var du som bad om att få byta lösenord kan du bara ignorera det här meddelandet.</p>
<p>Tack!</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Vänligen verifiera ditt konto',
            'title'   => 'Tack för din registrering',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Återställ lösenord',
            'title'   => 'Återställ ditt lösenord',
            'body'    => $resetBody
        ]
    ],
];
