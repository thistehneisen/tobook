<?php
$confirmBody = <<< HTML
<p>Hello %s,</p>
<p>Please click on the URL below to complete your registration: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>Thank you</p>
HTML;

$resetBody = <<< HTML
<p>Hello %s,</p>
<p>Someone (probably you) requested to have a new password.</p>
<p>Please click on the URL below to change your password: <br>
<a href="%s" title="" target="_blank">%s</a></p>
<p>If you did not do this, please ignore this message.</p>
<p>Thank you</p>
HTML;

return [
    'emails' => [
        'confirm' => [
            'subject' => 'Please confirm your account',
            'title'   => 'Thank you for registering with us',
            'body'    => $confirmBody
        ],
        'reset' => [
            'subject' => 'Password reset',
            'title'   => 'Reset your password',
            'body'    => $resetBody
        ]
    ],
    'business_categories' => [
        'index'      => 'Business areas',
        'beauty'     => 'Beauty',
        'restaurant' => 'Restaurant',
        'car'        => 'Car',
        'wellness'   => 'Wellness',
        'activities' => 'Activities',
    ]
];
