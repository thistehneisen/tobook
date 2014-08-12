<?php
$confirmBody = <<< HTML
<p>Hei %s,</p>
<p>Ole hyvä ja klikka alla linkki suoritamaan rekisteröinnisin: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Kiitos</p>
HTML;

$resetBody = <<< HTML
<p>Hei %s,</p>
<p>Joku (luultavasti sinä) pyytii uuden salasanan.</p>
<p>Ole hyvä ja klikka alla linkki päivitämään salasanasin: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Jos sinä et pyytänyt, sivuuta tämä viesti.</p>
<p>Kiitos</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Vahvista sähköpostisi',
            'title'   => 'Kiitos rekisteröinnistä',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Salasanan päivittäminen',
            'title'   => 'Päivitä sanasanasin',
            'body'    => $resetBody
        ]
    ]
];
