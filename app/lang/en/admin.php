<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'users'       => 'Businesses',
        'settings'    => 'Settings',
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
    'paid'               => 'Paid',
    'total'              => 'Total',
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
            'sold' => 'Sold flash deals',
            'business' => 'Business',
            'labels' => [
                'revenue' => 'Revenue',
                'total'   => 'Total'
            ]
        ]
    ],
    'commissions'        => [
        'index'  => 'Commissions',
        'done'   => 'Commission has been saved',
        'fail'   => 'Cannot save data. Please check your input and try again.',
        'amount' => 'Amount',
        'note'   => 'Note (optional)',
        'date'   => 'Date'
    ],
];
