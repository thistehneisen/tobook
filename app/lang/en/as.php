<?php
return [
    'index' => [
        'heading'     => 'Welcome',
        'description' => '', // @todo
        'today'       => 'Today',
        'tomorrow'    => 'Tomorrow',
        'print'       => 'Print',
    ],
    'services' => [
        'categories' => [
            'all'           => 'All categories',
            'add'           => 'Add new category',
            'edit'          => 'Edit category',
            'name'          => 'Name',
            'description'   => 'Description',
            'is_show_front' => 'Is shown in frontpage?',
        ],
        'resources' => [
            'all'         => 'All resources',
            'add'         => 'Add new resource',
            'edit'        => 'Edit resource',
            'name'        => 'Name',
            'description' => 'Description',
            'quantity'    => 'Quantity',
        ],
        'extras' => [
            'all'         => 'All extra services',
            'add'         => 'Add new extra service',
            'edit'        => 'Edit extra service',
            'name'        => 'Name',
            'description' => 'Description',
            'price'       => 'Price',
            'length'      => 'Length',
        ],
        'all'          => 'All services',
        'add'          => 'Add new service',
        'add_desc'     => 'Lisää uusi palvelu lisäämällä palvelun nimi, palvelun kesto ja työntekijät',
        'name'         => 'Name',
        'description'  => 'Description',
        'price'        => 'Price',
        'duration'     => 'Duration',
        'before'       => 'Before',
        'after'        => 'After',
        'total'        => 'Total',
        'category'     => 'Category',
        'is_active'    => 'Status',
        'resource'     => 'Resource',
        'extra'        => 'Extra Service',
        'employees'    => 'Employees',
        'no_employees' => 'There is no employee to be selected',
    ],
    'bookings' => [
        'confirmed'  => 'Tila: vahvistettu',
        'pending'    => 'Tila: auki',
        'cancelled'  => 'Tila: peruutettu',
        'all'        => 'Bookings',
        'add'        => 'Add new booking',
        'invoices'   => 'Invoices',
        'customers'  => 'Customers',
        'statistics' => 'Statistics',
        'date'       => 'Date', // @todo
        'total'      => 'Total', // @todo
        'start_at'   => 'Start At', // @todo
        'status'     => 'Status', // @todo
        'ip'         => 'IP', // @todo
    ],
    'employees' => [
        'all'                 => 'Employees',
        'add'                 => 'Add new employee',
        'edit'                => 'Edit employee',
        'name'                => 'Name',
        'phone'               => 'Phone number',
        'email'               => 'Email',
        'description'         => 'Description',
        'is_subscribed_email' => 'Is subscribed email?',
        'is_subscribed_sms'   => 'Is subscribed SMS?',
        'services'            => 'Services',
        'status'              => 'Status',
        'is_active'           => 'Status',
        'avatar'              => 'Avatar',
        'default_time'        => 'Default time',
        'custom_time'         => 'Custom time',
        'days_of_week'        => 'Days of week',
        'start_time'          => 'Start time',
        'end_time'            => 'End time',
        'day_off'             => 'Is day off?'

    ],
    'embed' => [
        'heading'          => 'Otsikko', // @todo
        'description'      => 'Sisältö', // @todo
        'embed'            => 'Embed',
        'preview'          => 'Preview',
        'back_to_services' => 'Back to services',
        'select_date'      => 'Select date',
        'select_service'   => 'Select services',
        'guide_text'       => 'Klikkaa avointa aikaa', //@todo
        'make_appointment' => 'Make appointment',
        'cancel'           => 'Cancel',
        'empty_cart'       => 'Your cart is empty',
        'start_time'       => 'Start time',
        'end_time'         => 'End time',
        'booking_form'     => 'Booking form',
        'name'             => 'Name',
        'email'            => 'Email',
        'phone'            => 'Phone number',
        'checkout'         => 'Checkout', // @todo
    ],
    'reports' => [
        'index'     => 'Reports',
        'employees' => 'Employees',
        'services'  => 'Services',
        'generate'  => 'Generate',
        'start'     => 'Start date',
        'end'       => 'End date',
        'booking'   => [
            'total'       => 'Total bookings',
            'confirmed'   => 'Confirmed bookings',
            'unconfirmed' => 'Unconfirmed bookings',
            'cancelled'   => 'Cancelled bookings',
        ]
    ],
    'items_per_page' => 'Items per page',
    'with_selected'  => 'With selected',
    'crud' => [
        'bulk_confirm'   => 'Are you sure to carry out this action?',
        'success_add'    => 'Item was created successfully.',
        'success_edit'   => 'Data was updated successfully.',
        'success_delete' => 'Item was deleted successfully.',
        'success_bulk'   => 'Item was deleted successfully.',
    ]
];
