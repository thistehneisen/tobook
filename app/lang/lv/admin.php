<?php
return [
    'admin' => 'Administrators',
    'nav' => [
        'admin'       => 'Jauns administrators',
        'users'       => 'Biznesi',
        'settings'    => 'Uzstādījumi',
        'modules'     => 'Moduļi',
        'stats'       => 'Statistika',
        'flash_deals' => 'Karstie piedāvājumi',
    ],
    'edit_heading'       => 'Rediģēt :model #:id',
    'create_heading'     => 'Izveidot jaunu :model',
    'index_heading'      => 'Visi :model',
    'search_placeholder' => 'Ierakstiet meklējamo vārdu vai frāzi',
    'search'             => 'Meklēt',
    'create'             => 'Izveidot',
    'create_note'        => 'Lietotājam tiks nosūtīta jauna parole.',
    'actions'            => 'Darbības',
    'edit'               => 'Rediģēt',
    'delete'             => 'Dzēst',
    'enable'             => 'Iespējot',
    'activate'           => 'Aktivizēt',
    'deactivate'         => 'Deaktivizēt',
    'paid'               => 'Samaksāts',
    'total'              => 'Kopā',
    'modules'            => [
        'modules'               => 'Pakalpojumi',
        'active_time'           => 'Aktīvais laiks',
        'all_modules'           => 'Visi moduļi',
        'enabled_modules'       => 'Aktīvie pakalpojumi',
        'enable_module_heading' => 'Pievienot jaunu pakalpojumu',
        'name'                  => 'Nosaukums',
        'start'                 => 'Sākums',
        'end'                   => 'Beigas',
        'success_enabled'       => 'Modulis <strong>:module</strong> ir pieejams lietotājama <strong>:user</strong>',
        'success_activation'    => 'Moduļa statuss veiksmīgi mainīts',
        'err_overlapped'        => 'Izvēlētais laika periods pārklājas ar sistēmā esošajiem. Pārbaudiet informāciju, izlabojiet un mēģiniet vēlreiz.',
        'err_time_passed'       => 'Aktīvais laiks ir pagājis',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Karsto piedāvājumu statistika',
            'sold' => 'Pārdoti karstie piedāvājumi',
            'business' => 'Bizness',
            'labels' => [
                'revenue' => 'Ieņēmumi',
                'total'   => 'Kopā'
            ]
        ]
    ],
    'commissions'        => [
        'index'                                => 'Pasūtījumi',
        'done'                                 => 'Pasūtījumi saglabāti',
        'fail'                                 => 'Nevarēja saglabāt datus. Lūdzu, pārbaudiet informāciju, izlabojiet to un mēģiniet vēlreiz.',
        'amount'                               => 'Apjoms',
        'note'                                 => 'Piezīme (pēc izvēles)',
        'date'                                 => 'Datums',
        'name'                                 => 'Vārds',
        'price'                                => 'Cena',
        'customer'                             => 'Klients',
        'employee'                             => 'Darbinieks',
        'employees'                            => 'Darbinieki',
        'booking_status'                       => 'Paid/Booked/Deposit',
        'commission_status'                    => 'Komisijas statuss',
        'notes'                                => 'Piezīmes',
        'paid_this_month'                      => 'Apmaksātais šomēnes',
        'created_at'                           => 'Izveidots',
        'booking_date'                         => 'Rezervācijas datums',
        'payment_pending'                      => 'Neapstrādāts maksājums',
        'email_monthly_report'                 => 'E-pasts ikmēneša pārskatu',
        'email_title'                          => 'Virsraksts',
        'email_content'                        => 'Saturs',
        'number_of_orders'                     => 'Pasūtīju skaits',
        'number_of_consumers'                  => 'Klientu skaits',
        'commission'                           => 'Komisija',
        'commission_total'                     => 'Kopējā komisija',
        'payment_for_online_order'             => 'Samaksa par online pasūtījumu',
        'payment_for_money_transfer'           => 'Samaksa izmantojot pārskaitījumu',
        'payment_for_new_consumers'            => 'Samaksa par jaunajiem klientiem',
        'percentage'                           => '%',
        'total'                                => 'Kopā',
        'sum'                                  => 'Skaits',
        'domain_commission_total'              => '%s kopējā komisijal',
        'domain_charged_from_visitor'          => '%s iekasētā summa no klientiem',
        'domain_amount_transfered_to_cusomter' => 'Summa pārskaitīta klientam',
        'commission_calculation'               => 'Komisijas aprēķins',
        'status' => [
            'paid'      => 'Samaksāts',
            'confirmed' => 'Samaksa uz vietas',
            'deposit'   => 'Depozīts',
            'suspend'   => 'Apturēt',
            'cancelled' => 'Atcelts',
            'initial'   => 'Sākuma',
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
        'deposit_rate'           => 'Deposit rate', // @todo
        'deposit_payment'        => 'Deposit payment', // @todo
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
