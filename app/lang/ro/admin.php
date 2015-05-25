<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'admin'       => 'Administrator nou',
        'users'       => 'Afaceri',
        'settings'    => 'Setari',
        'modules'     => 'Module',
        'stats'       => 'Statistici',
        'flash_deals' => 'Oferte',
    ],
    'edit_heading'       => 'Modificati :model #:id',
    'create_heading'     => 'Creati un nou :model',
    'index_heading'      => 'Lista de :model',
    'search_placeholder' => 'Introduceti orice doriti sa cautati',
    'search'             => 'Cauta',
    'create'             => 'Creeaza',
    'create_note'        => 'Noua parola va fi trimisa catre utilizator',
    'actions'            => 'Actiuni',
    'edit'               => 'Modificati',
    'delete'             => 'Sterge',
    'enable'             => 'Permite',
    'activate'           => 'Activeaza',
    'deactivate'         => 'Dezactiveaza',
    'paid'               => 'Platit',
    'total'              => 'Total',
    'modules'            => [
        'modules'               => 'Servicii',
        'active_time'           => 'Timp activ',
        'all_modules'           => 'Toate modulele',
        'enabled_modules'       => 'Servicii active',
        'enable_module_heading' => 'Adauga un nou serviciu',
        'name'                  => 'Nume',
        'start'                 => 'Start',
        'end'                   => 'Sfarsit',
        'success_enabled'       => 'Modulul <strong>:module</strong> este acum disponibil pentru utilizator <strong>:user</strong>',
        'success_activation'    => 'Starea modulului a fost schimbata cu succes',
        'err_overlapped'        => 'Perioada selectată este suprapusa cu cele existente. Va rugam sa verificati si sa incercati din nou.',
        'err_time_passed'       => 'Timpul activ a expirat',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Statistici oferte',
            'sold' => 'Oferte vandute',
            'business' => 'Afacere',
            'labels' => [
                'revenue' => 'Venituri',
                'total'   => 'Total'
            ]
        ]
    ],
    'commissions'        => [
        'index'  => 'Comisioane',
        'done'   => 'Comisionul a fost salvat',
        'fail'   => 'Nu se pot salva datele. Va rugam sa verificati datele introduse si sa incercati din nou.',
        'amount' => 'Suma',
        'note'   => 'Nota (optional)',
        'date'   => 'Data'
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
