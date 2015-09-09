<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'admin'       => 'New admin',
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
    ],
    'commissions'        => [
        'index'  => 'Commissions',
        'done'   => 'Commission has been saved',
        'fail'   => 'Cannot save data. Please check your input and try again.',
        'amount' => 'Amount',
        'note'   => 'Note (optional)',
    ],
    'settings' => [
        'site_name'              => 'Site Name', // @todo
        'head_script'            => 'Script inserted in &lt;/HEAD&gt;', // @todo
        'bottom_script'          => 'Script inserted before &lt;/BODY&gt;', // @todo
        'allow_robots'           => 'Allow search engine robots', // @todo
        'meta_title'             => 'Meta title', // @todo
        'meta_description'       => 'Meta description', // @todo
        'meta_keywords'          => 'Meta keywords', // @todo
        'social_facebook'        => 'Facebook', // @todo
        'social_linkedin'        => 'Linkedin', // @todo
        'social_youtube'         => 'Youtube', // @todo
        'copyright_name'         => 'Company name for footer copyright', // @todo
        'copyright_url'          => 'URL for footer copyright', // @todo
        'enable_cart'            => 'Enable shopping cart', // @todo
        'phone_country_code'     => 'Phone country code', // @todo
        'currency'               => 'Currency symbol', // @todo
        'commission_rate'        => 'Commission rate taken from businesses, e.g. 30% = 0.3', // @todo
        'default_location'       => 'Default location to show in front page, e.g. Helsinki, FI', // @todo
        'footer_contact_message' => 'Text to be included in every contact message of business', // @todo
        'social_google-plus'     => 'Google+', // @todo
        'default_layout'         => 'The default layout', // @todo
        'default_paygate'        => 'The default paygate', // @todo
        'big_cities'             => 'Big cities (One city per line)', // @todo
        'contact_email'          => 'Contact email', // @todo
        'force_pay_at_venue'     => 'Force pay at venue',
    ],
    'master-cats' => [
        'all'         => 'All master category',// @todo
        'edit'        => 'Edit',// @todo
        'add'         => 'Add',// @todo
        'name'        => 'Name',// @todo
        'description' => 'Description',// @todo
        'language'    => 'Language',// @todo
        'translation_not_found' => 'Not available in current language',// @todo
    ],
    'treatment-types' => [
        'all'             => 'All treatment types',// @todo
        'edit'            => 'Edit',// @todo
        'add'             => 'Add',// @todo
        'name'            => 'Name',// @todo
        'description'     => 'Description',// @todo
        'language'        => 'Language',// @todo
        'master_category' => 'Master category',// @todo
        'translation_not_found' => 'Not available in current language',// @todo
    ],
];
