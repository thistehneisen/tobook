<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'users'       => 'Käyttäjät',
        'settings'    => 'Asetukset',
        'modules'     => 'Modules',
        'stats'       => 'Statistics',
        'flash_deals' => 'Flash Deals',
    ],
    'edit_heading'       => 'Edit :model #:id',
    'create_heading'     => 'Create a new :model',
    'index_heading'      => 'List of :model',
    'search_placeholder' => 'Enter anything you want to search',
    'search'             => 'Search',
    'create'             => 'Create',
    'actions'            => 'Actions',
    'edit'               => 'Edit',
    'delete'             => 'Delete',
    'enable'             => 'Enable',
    'activate'           => 'Activate',
    'deactivate'         => 'Deactivate',
    'modules'            => [
        'modules'               => 'Services',
        'active_time'           => 'Active time',
        'all_modules'           => 'All modules',
        'enabled_modules'       => 'Active services',
        'enable_module_heading' => 'Add new service',
        'name'                  => 'Name',
        'start'                 => 'Start',
        'end'                   => 'End',
        'success_enabled'       => 'Module <strong>:module</strong> is now available for user <strong>:user</strong>',
        'success_activation'    => 'Module status changed successfully',
        'err_overlapped'        => 'The selected period is overlapped with existing ones. Please recheck and try again.',
        'err_time_passed'       => 'The active time has passed',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Flash Deals Statistics',
            'labels' => [
                'revenue' => 'Revenue',
                'total'   => 'Total'
            ]
        ]
    ]
];
