<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'users'       => 'Användare',
        'settings'    => 'Inställningar',
        'modules'     => 'Moduler',
        'stats'       => 'Statistik',
        'flash_deals' => 'Flash Deals',
    ],
    'edit_heading'       => 'Redigera :model #:id',
    'create_heading'     => 'Lägg till ny :model',
    'index_heading'      => 'Listvy :model',
    'search_placeholder' => 'Sök',
    'search'             => 'Sök',
    'create'             => 'Lägg till',
    'actions'            => 'Händelser',
    'edit'               => 'Redigera',
    'delete'             => 'Ta bort',
    'enable'             => 'Slå på',
    'activate'           => 'Aktivera',
    'deactivate'         => 'Inaktivera',
    'modules'            => [
        'modules'               => 'Tjänster',
        'active_time'           => 'Aktiv tid',
        'all_modules'           => 'Alla moduler',
        'enabled_modules'       => 'Aktiva tjänster',
        'enable_module_heading' => 'Lägg till ny tjänst',
        'name'                  => 'Namn',
        'start'                 => 'Start',
        'end'                   => 'Slut',
        'success_enabled'       => 'Modul <strong>:module</strong> är nu tillgänglig för användare <strong>:user</strong>',
        'success_activation'    => 'Modulen är nu uppdaterad',
        'err_overlapped'        => 'Den valda tidsperioden krockar med en som redan finns. Välj ny och försök igen.',
        'err_time_passed'       => 'Den aktiva tiden har passerat.',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Flash Deals Statistik',
            'sold' => 'Sålda flash deals',
            'business' => 'Företag',
            'labels' => [
                'revenue' => 'Intäkter',
                'total'   => 'Totalt'
            ]
        ]
    ]
];