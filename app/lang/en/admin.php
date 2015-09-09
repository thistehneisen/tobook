<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'admin'             => 'New admin',
        'users'             => 'Businesses',
        'settings'          => 'Settings',
        'booking_terms'     => 'Booking terms',
        'modules'           => 'Modules',
        'stats'             => 'Statistics',
        'flash_deals'       => 'Flash Deals',
        'master_categories' => 'Master categories',
        'treatment_types'   => 'Treatment types',
        'keywords'          => 'Keywords',
        'seo'               => 'SEO',
        'pages'             => 'Static pages',
        'misc'              => 'Misc.',
    ],
    'edit_heading'       => 'Edit :model #:id',
    'create_heading'     => 'Create a new :model',
    'index_heading'      => 'List of :model',
    'search_placeholder' => 'Enter anything you want to search',
    'search'             => 'Search',
    'create'             => 'Create',
    'create_note'        => 'New password will be sent to user.',
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
    'commissions'  => [
        'index'                                => 'Commissions',
        'done'                                 => 'Commission has been saved',
        'fail'                                 => 'Cannot save data. Please check your input and try again.',
        'amount'                               => 'Amount',
        'note'                                 => 'Note (optional)',
        'date'                                 => 'Date',
        'name'                                 => 'Name',
        'price'                                => 'Price',
        'commission'                           => 'Commission',
        'customer'                             => 'Customer',
        'employee'                             => 'Employee',
        'employees'                            => 'Employees',
        'booking_status'                       => 'Paid/Booked/Deposit',
        'commission_status'                    => 'Commission Status',
        'notes'                                => 'Notes',
        'paid_this_month'                      => 'Paid this month',
        'created_at'                           => 'Created At',
        'booking_date'                         => 'Booking Date',
        'payment_pending'                      => 'Payment pending',
        'email_monthly_report'                 => 'Email monthly report to',
        'email_title'                          => 'Title',
        'email_content'                        => 'Content',
        'number_of_orders'                     => 'Number of orders',
        'number_of_consumers'                  => 'Number of consumers',
        'commission'                           => 'Commission',
        'commission_total'                     => 'Commission total',
        'payment_for_online_order'             => 'Payment for online orders',
        'payment_for_money_transfer'           => 'Payment for money transfer',
        'payment_for_new_consumers'            => 'Payment for new consumers',
        'percentage'                           => '%',
        'total'                                => 'Total',
        'sum'                                  => 'Sum',
        'domain_commission_total'              => '%s commission total',
        'domain_charged_from_visitor'          => '%s charged from visitors',
        'domain_amount_transfered_to_cusomter' => 'Amount tranfered to customer',
        'commission_calculation'               => 'Commission calculation',
        'status' => [
            'paid'      => 'Paid',
            'confirmed' => 'Booked',
            'deposit'   => 'Deposit',
            'suspend'   => 'Suspend',
            'cancelled' => 'Cancelled',
            'initial'   => 'Initial',
            'pending'   => 'Pending',
        ]
    ],
    'settings' => [
        'site_name'                    => 'Site Name',
        'head_script'                  => 'Script inserted in &lt;/HEAD&gt;',
        'bottom_script'                => 'Script inserted before &lt;/BODY&gt;',
        'allow_robots'                 => 'Allow search engine robots',
        'meta_title'                   => 'Meta title',
        'meta_description'             => 'Meta description',
        'meta_keywords'                => 'Meta keywords',
        'social_facebook'              => 'Facebook',
        'social_linkedin'              => 'Linkedin',
        'social_youtube'               => 'Youtube',
        'copyright_name'               => 'Company name for footer copyright',
        'copyright_url'                => 'URL for footer copyright',
        'enable_cart'                  => 'Enable shopping cart',
        'phone_country_code'           => 'Phone country code',
        'currency'                     => 'Currency symbol',
        'commission_rate'              => 'Commission rate taken from businesses, e.g. 30% =0.3',
        'default_location'             => 'Default location to show in front page, e.g. Helsinki, FI',
        'footer_contact_message'       => 'Text to be included in every contact message of business',
        'social_google-plus'           => 'Google+',
        'default_layout'               => 'The default layout',
        'default_paygate'              => 'The default paygate',
        'deposit_rate'                 => 'Deposit rate',
        'deposit_payment'              => 'Deposit payment',
        'big_cities'                   => 'Big cities (One city per line)',
        'districts'                    => 'Districts (One per line)',
        'contact_email'                => 'Contact email',
        'constant_commission'          => 'Constant commission taken from businesses (&euro;)',
        'new_consumer_commission_rate' => 'New consumer commission, e.g. 30% =0.3',
        'booking_terms'                => 'Booking terms',
        'force_pay_at_venue'           => 'Force pay at venue',
        'sms_length_limiter'           => 'SMS length limiter',
        'enable_homepage_modal'        => 'Enable homepage modal',
        'homepage_modal_url'           => 'Homepage modal URL',
    ],
    'master-cats' => [
        'all'         => 'All master category',
        'edit'        => 'Edit',
        'add'         => 'Add',
        'name'        => 'Name',
        'description' => 'Description',
        'language'    => 'Language',
        'translation_not_found' => 'Not available in current language',
    ],
    'treatment-types' => [
        'all'             => 'All treatment types',
        'edit'            => 'Edit',
        'add'             => 'Add',
        'name'            => 'Name',
        'description'     => 'Description',
        'language'        => 'Language',
        'master_category' => 'Master category',
        'translation_not_found' => 'Not available in current language',
        'keywords'        => 'Keywords',
    ],
    'keywords' => [
        'all'             => 'All keywords',
        'edit'            => 'Edit',
        'add'             => 'Add',
        'name'            => 'Name',
        'description'     => 'Description',
        'language'        => 'Language',
        'treatment_type'  => 'Treatment type',
        'master_category' => 'Master category',
        'keyword'         => 'Keyword',
        'keywords'        => 'Keywords',
        'mapped'          => 'Mapping to',
        'duplicated'      => 'The keyword has already existed. Keyword must be unique.',
    ],
];
