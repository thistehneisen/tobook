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
        'site_name'                             => 'Название сайта',
        'head_script'                           => 'Скрипт вставляется в &lt;/HEAD&gt;',
        'bottom_script'                         => 'Скрипта вставляется до &lt;/BODY&gt;',
        'allow_robots'                          => 'Разрешить поисковых роботов',
        'meta_title'                            => 'Мета название',
        'meta_description'                      => 'Мета описание',
        'meta_keywords'                         => 'Мета ключевые слова',
        'social_facebook'                       => 'Facebook', // @todo
        'social_linkedin'                       => 'Linkedin', // @todo
        'social_youtube'                        => 'Youtube', // @todo
        'copyright_name'                        => 'Название фирмы для нижнего колонтитула (footer) авторских прав',
        'copyright_url'                         => 'Ссылка для нижнего колонтитула (footer) авторских прав',
        'enable_cart'                           => 'Enable shopping cart', // @todo
        'phone_country_code'                    => 'Телефонный код страны', // @todo
        'currency'                              => 'Символ валюты',
        'commission_rate'                       => 'Процент комиссии от бронирования, например, 30% =0,3',
        'default_location'                      => 'Местонахождение по умолчанию, чтобы показать на первой странице, например Минск, BY',
        'sms_length_limiter'                    => 'Ограничитель длины СМС',
        'enable_homepage_modal'                 => 'Включить modal страницы',
        'homepage_modal_url'                    => 'modal URL домашней страницы',
        'enable_business_modal'                 => 'Enable business modal', // @todo
        'business_modal_url'                    => 'URL бизнес modalа',
        'homepage_modal_cookie_name'            => 'Название Cookie',
        'homepage_modal_cookie_expiry_duration' => 'Длительность (в минутах)',
        'coupon'                                => 'Купон',
        'show_discount_suggestion'              => 'Предложения о скидках на главной странице',
        'footer_contact_message'                => 'Текст для контактов, включений в каждом бизнес-сообщение',
        'social_google-plus'                    => 'Google+', // @todo
        'default_layout'                        => 'Расположение (layout) по умолчанию',
        'default_paygate'                       => 'Paygate по умолчанию',
        'deposit_rate'                          => 'Депозитная ставка',
        'deposit_payment'                       => 'Депозитный платеж',
        'big_cities'                            => 'Крупные города (один город в строке)',
        'districts'                             => 'Районы (один район в строке)',
        'constant_commission'                   => 'Постоянная комиссия от бизнеса (€)',
        'new_consumer_commission_rate'          => 'Комиссия от нового клиента, например, 30% = 0,3',
        'contact_email'                         => 'Эл.почта для связи',
        'force_pay_at_venue'                    => 'Оплата на месте',
    ],
    'master-cats' => [
        'all'                   => 'Все главные категории',
        'edit'                  => 'Edit',// @todo
        'add'                   => 'Добавить',
        'name'                  => 'Название',
        'description'           => 'Description',// @todo
        'language'              => 'Language',// @todo
        'translation_not_found' => 'Not available in current language',// @todo
    ],
    'treatment-types' => [
        'all'                   => 'Все виды процедур',
        'edit'                  => 'Edit',// @todo
        'add'                   => 'Добавить',
        'name'                  => 'Название',
        'description'           => 'Описание',
        'language'              => 'Language',// @todo
        'master_category'       => 'Главная категория',
        'translation_not_found' => 'Not available in current language',// @todo
    ],
    'keywords' => [
        'all'             => 'All keywords',// @todo
        'edit'            => 'Edit',// @todo
        'add'             => 'Добавить',
        'name'            => 'Название',
        'description'     => 'Описание',
        'language'        => 'Language',// @todo
        'treatment_type'  => 'Виды процедур',
        'master_category' => 'Главные категории',
        'keyword'         => 'Keyword',// @todo
        'keywords'        => 'Ключевые слова',
        'mapped'          => 'Mapping to',// @todo
        'duplicated'      => 'The keyword has already existed. Keyword must be unique.',// @todo
    ],
    'coupon' => [
        'title'           => 'Coupons',
        'setting'         => 'Установка',
        'settings'        => 'Settings',
        'campaigns'       => 'Акции',
        'index'           => 'Main',
        'create'          => 'Создать акцию',
        'is_used'         => 'Is Used',
        'code'            => 'Code',
        'consumer_name'   => 'Consumer name',
        'datetime'        => 'Date',
        'discount_amount' => 'Discount',
        'salon'           => 'Business',
        'reusable_usage'  => 'Usage',
        'reusable' => 'Reusable',
        'campaign' => [
            'all'           => 'Все акции',
            'add'           => 'Добавить новую акцию',
            'edit'          => 'Modify campaign',
            'name'          => 'Название',
            'bar_chart'     => 'Used / Unused coupons',
            'discount'      => 'Скидка',
            'discount_type' => 'Вид скидки',
            'amount'        => 'Сумма',
            'is_reusable'   => 'Многоразовая',
            'is_disposable' => 'Diposable',
            'reusable_code' => 'Многоразовый код',
            'begin_at'      => 'Начало',
            'expire_at'     => 'До',
            'used'          => 'Used',
            'not_used'      => 'Not used',
            'stats'         => 'Statistics',
            'errors' => [
                'duplicated_code' => 'This reusable code must be unique for each campaign!'
            ]
        ]
    ],
];
