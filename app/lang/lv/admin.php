<?php
return [
    'admin' => 'Administrators',
    'nav' => [
        'admin'       => 'Jauns administrators',
        'users'       => 'Biznesi',
        'settings'    => 'Uzstâdîjumi',
        'modules'     => 'Moduïi',
        'stats'       => 'Statistika',
        'flash_deals' => 'Karstie piedâvâjumi',
    ],
    'edit_heading'       => 'Rediìçt :model #:id',
    'create_heading'     => 'Izveidot jaunu :model',
    'index_heading'      => 'Visi :model',
    'search_placeholder' => 'Ierakstiet meklçjamo vârdu vai frâzi',
    'search'             => 'Meklçt',
    'create'             => 'Izveidot',
    'create_note'        => 'Lietotâjam tiks nosûtîta jauna parole.',
    'actions'            => 'Darbîbas',
    'edit'               => 'Rediìçt',
    'delete'             => 'Dzçst',
    'enable'             => 'Iespçjot',
    'activate'           => 'Aktivizçt',
    'deactivate'         => 'Deaktivizçt',
    'paid'               => 'Samaksâts',
    'total'              => 'Kopâ',
    'modules'            => [
        'modules'               => 'Pakalpojumi',
        'active_time'           => 'Aktîvais laiks',
        'all_modules'           => 'Visi moduïi',
        'enabled_modules'       => 'Aktîvie pakalpojumi',
        'enable_module_heading' => 'Pievienot jaunu pakalpojumu',
        'name'                  => 'Nosaukums',
        'start'                 => 'Sâkums',
        'end'                   => 'Beigas',
        'success_enabled'       => 'Modulis <strong>:module</strong> ir pieejams lietotâjama <strong>:user</strong>',
        'success_activation'    => 'Moduïa statuss veiksmîgi mainîts',
        'err_overlapped'        => 'Izvçlçtais laika periods pârklâjas ar sistçmâ esoðajiem. Pârbaudiet informâciju, izlabojiet un mçìiniet vçlreiz.',
        'err_time_passed'       => 'Aktîvais laiks ir pagâjis',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Karsto piedâvâjumu statistika',
            'sold' => 'Pârdoti karstie piedâvâjumi',
            'business' => 'Bizness',
            'labels' => [
                'revenue' => 'Ieòçmumi',
                'total'   => 'Kopâ'
            ]
        ]
    ],
    'commissions'        => [
        'index'  => 'Pasûtîjumi',
        'done'   => 'Pasûtîjumi saglabâti',
        'fail'   => 'Nevarçja saglabât datus. Lûdzu, pârbaudiet informâciju, izlabojiet to un mçìiniet vçlreiz.',
        'amount' => 'Apjoms',
        'note'   => 'Piezîme (pçc izvçles)',
        'date'   => 'Datums'
    ],
    'settings' => [
        'site_name'          => 'Site Name', // @todo
        'head_script'        => 'Script inserted in &lt;/HEAD&gt;', // @todo
        'bottom_script'      => 'Script inserted before &lt;/BODY&gt;', // @todo
        'allow_robots'       => 'Allow search engine robots', // @todo
        'meta_title'         => 'Meta title', // @todo
        'meta_description'   => 'Meta description', // @todo
        'meta_keywords'      => 'Meta keywords', // @todo
        'social_facebook'    => 'Facebook', // @todo
        'social_linkedin'    => 'Linkedin', // @todo
        'social_youtube'     => 'Youtube', // @todo
        'copyright_name'     => 'Company name for footer copyright', // @todo
        'copyright_url'      => 'URL for footer copyright', // @todo
        'enable_cart'        => 'Enable shopping cart', // @todo
        'phone_country_code' => 'Phone country code', // @todo
        'currency'           => 'Currency symbol', // @todo
        'commission_rate'    => 'Commission rate taken from businesses, e.g. 30% = 0.3', // @todo
        'default_location'   => 'Default location to show in front page, e.g. Helsinki, FI', // @todo
    ],
];
