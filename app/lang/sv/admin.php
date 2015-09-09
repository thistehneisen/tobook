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
