<?php
$confirmBody = <<< HTML
<p>Hei %s,</p>
<p>Ole hyvä ja klikkaa alla linkkiä vahvistamaan rekisteröinnisin: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Kiitos</p>
HTML;

$resetBody = <<< HTML
<p>Hei %s,</p>
<p>Joku (luultavasti sinä) pyysi uuden salasanan.</p>
<p>Ole hyvä ja klikkaa allaolevaa linkkiä päivittääksesi salasanasi: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Jos sinä et pyytänyt, sivuuta tämä viesti.</p>
<p>Kiitos</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Vahvista rekisteröintisi',
            'title'   => 'Kiitos rekisteröinnistä',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Salasanan päivittäminen',
            'title'   => 'Päivitä sanasanasin',
            'body'    => $resetBody
        ]
    ],
    'business_categories' => [
        'index'      => 'Business areas', // @todo
        'beauty'     => 'Kauneus',
        'restaurant' => 'Ravintola',
        'car'        => 'Auto',
        'wellness'   => 'Hyvinvointi',
        'activities' => 'Toiminta',
    ]
];
