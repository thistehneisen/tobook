<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'users'       => 'Käyttäjät',
        'settings'    => 'Asetukset',
        'modules'     => 'Moduulit',
        'stats'       => 'Statistics',
        'flash_deals' => 'Flash Deals',
    ],
    'edit_heading'       => 'Edit :model #:id',
    'create_heading'     => 'Create a new :model',
    'index_heading'      => 'List of :model',
    'search_placeholder' => 'Enter anything you want to search',
    'search'             => 'Etsi',
    'create'             => 'Create',
    'actions'            => 'Actions',
    'edit'               => 'Muokkaa',
    'delete'             => 'Poista',
    'enable'             => 'Enable',
    'activate'           => 'Aktivoi',
    'deactivate'         => 'Deaktivoi',
    'modules'            => [
        'modules'               => 'Moduulit',
        'active_time'           => 'Active time',
        'all_modules'           => 'Kaikki moduulit',
        'enabled_modules'       => 'Aktiiviset moduulit',
        'enable_module_heading' => 'Lisää uusi palvelu',
        'name'                  => 'Nimi',
        'start'                 => 'Alku',
        'end'                   => 'Loppu',
        'success_enabled'       => 'Module <strong>:module</strong> is now available for user <strong>:user</strong>',
        'success_activation'    => 'Module status changed successfully',
        'err_overlapped'        => 'The selected period is overlapped with existing ones. Please recheck and try again.',
        'err_time_passed'       => 'The active time has passed',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Äkkilähtö statistiikka',
            'sold' => 'Myydyt äkkilähdöt',
            'business' => 'Business',
            'labels' => [
                'revenue' => 'Liikevaihto',
                'total'   => 'Yhteensä'
            ]
        ]
    ]
];
