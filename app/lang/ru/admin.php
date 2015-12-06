<?php
return [
    'admin' => 'Админ',
    'nav' => [
        'admin'             => 'Новый админ',
        'users'             => 'Пользователи',
        'settings'          => 'Настройки',
        'modules'           => 'Модули',
        'stats'             => 'Статистика',
        'flash_deals'       => 'Flash Deals',
        'reviews'           => 'Отзывы',
    ],
    'edit_heading'       => 'Edit :model #:id',
    'create_heading'     => 'Создать :model',
    'index_heading'      => 'Список of :model',
    'search_placeholder' => 'Что вы хотите искать?',
    'search'             => 'Искать',
    'create'             => 'Создать',
    'create_note'        => 'Новый пароль будет отправлен пользователю.',
    'actions'            => 'Действия',
    'edit'               => 'Редактировать',
    'delete'             => 'Удалить',
    'enable'             => 'Включить',
    'activate'           => 'Активировать',
    'deactivate'         => 'Деактивировать',
    'paid'               => 'Paid',
    'total'              => 'Всего',
    'modules'            => [
        'modules'               => 'Сервысы',
        'active_time'           => 'Активное время',
        'all_modules'           => 'Все модули',
        'enabled_modules'       => 'Активные модули',
        'enable_module_heading' => 'Добавить новый сервис',
        'name'                  => 'Имя',
        'start'                 => 'Начало',
        'end'                   => 'Конец',
        'success_enabled'       => 'Модуль <strong>:module</strong> теперь доступен для пользователя <strong>:user</strong>',
        'success_activation'    => 'Статус модуля успешно изменен',
        'err_overlapped'        => 'Выбранный период перекрывает уже существующие. Попробуйте что-то изменить.',
        'err_time_passed'       => 'Время действия закончилось',
    ],
    'stats' => [
        'fd' => [
            'heading' => 'Flash Deals Statistics',
            'sold' => 'Sold flash deals',
            'business' => 'Бизнес',
            'labels' => [
                'revenue' => 'Оборот',
                'total'   => 'Итого'
            ]
        ]
    ],
    'commissions'        => [
        'index'  => 'Комиссии',
        'done'   => 'Комиссия сохранена',
        'fail'   => 'Сохранение невозможно. Попробуйте еще раз.',
        'amount' => 'Количество',
        'note'   => 'Заметки (не обязательно)',
        'date'   => 'Дата'
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
