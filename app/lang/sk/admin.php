<?php
return [
    'admin' => 'Admin',
    'nav' => [
        'admin'             => 'Nový admin',
        'users'             => 'Podnikatelia',
        'settings'          => 'Nastavenia',
        'modules'           => 'Moduly',
        'stats'             => 'Štatistiky',
        'flash_deals'       => 'Top ponuky',
        'master_categories' => 'Hlavné kategórie',
        'treatment_types'   => 'Treatment types',
    ],
    'edit_heading'       => 'Upraviť :model #:id',
    'create_heading'     => 'Vytvoriť nový :model',
    'index_heading'      => 'Zoznam :model',
    'search_placeholder' => 'Zadaj čo hľadáte',
    'search'             => 'Hľadať',
    'create'             => 'Vytvoriť',
    'create_note'        => 'Nové heslo bude užívateľovi zaslané.',
    'actions'            => 'Akcie',
    'edit'               => 'Upraviť',
    'delete'             => 'Vymazať',
    'enable'             => 'Povoliť',
    'activate'           => 'Aktivovať',
    'deactivate'         => 'Deaktivovať',
    'paid'               => 'Zaplatené',
    'total'              => 'Spolu',
    'modules'            => [
        'modules'               => 'Služby',
        'active_time'           => 'Active time',
        'all_modules'           => 'Všetky moduly',
        'enabled_modules'       => 'Aktívne služby',
        'enable_module_heading' => 'Pridať novú službu',
        'name'                  => 'Meno',
        'start'                 => 'Štart',
        'end'                   => 'Koniec',
        'success_enabled'       => 'Modul <strong>:module</strong> je odteraz dostupný pre užívateľa <strong>:user</strong>',
        'success_activation'    => 'Status modulu bol úspešne aktualizovaný',
        'err_overlapped'        => 'The selected period is overlapped with existing ones. Please recheck and try again.',
        'err_time_passed'       => 'The active time has passed',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Štatistiky top ponúk',
            'sold' => 'Predané top ponuky',
            'business' => 'Business',
            'labels' => [
                'revenue' => 'Obrat',
                'total'   => 'Spolu'
            ]
        ]
    ],
    'commissions'        => [
        'index'  => 'Provízie',
        'done'   => 'Provízie boli uložené',
        'fail'   => 'Nemôžeme uložiť údaje. Prosím, skontrolujte údaje.',
        'amount' => 'Množstvo',
        'note'   => 'Poznámka (nepovinná)',
        'date'   => 'Dátum'
    ],
    'settings' => [
        'site_name'              => 'Meno stránky',
        'head_script'            => 'Script inserted in &lt;/HEAD&gt;',
        'bottom_script'          => 'Script inserted before &lt;/BODY&gt;',
        'allow_robots'           => 'Allow search engine robots',
        'meta_title'             => 'Meta title',
        'meta_description'       => 'Meta description',
        'meta_keywords'          => 'Meta keywords',
        'social_facebook'        => 'Facebook',
        'social_linkedin'        => 'Linkedin',
        'social_youtube'         => 'Youtube',
        'copyright_name'         => 'Company name for footer copyright',
        'copyright_url'          => 'URL for footer copyright',
        'enable_cart'            => 'Povoliť nákupný košik',
        'phone_country_code'     => 'Phone country code',
        'currency'               => 'Symbol meny',
        'commission_rate'        => 'Commission rate taken from businesses, e.g. 30% = 0.3',
        'default_location'       => 'Default location to show in front page, e.g. Helsinki, FI',
        'footer_contact_message' => 'Text to be included in every contact message of business',
        'social_google-plus'     => 'Google+',
        'default_paygate'        => 'The default paygate',
        'big_cities'             => 'Big cities (One city per line)',
    ],
    'master-cats' => [
        'all'         => 'Všetky hlavné kategórie',
        'edit'        => 'Upraviť',
        'add'         => 'Pridať',
        'name'        => 'Meno',
        'description' => 'Popis',
        'language'    => 'Jazyk',
        'translation_not_found' => 'Nedostupné v aktuálnom jazyku',
    ],
    'treatment-types' => [
        'all'             => 'All treatment types',
        'edit'            => 'Upraviť',
        'add'             => 'Pridať',
        'name'            => 'Meno',
        'description'     => 'Popis',
        'language'        => 'Jazyk',
        'master_category' => 'Hlavná kategória',
        'translation_not_found' => 'Nedostupné v aktuálnom jazyku',
    ],
];
