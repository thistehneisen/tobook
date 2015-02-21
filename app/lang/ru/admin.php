<?php
return [
    'admin' => 'Админ',
    'nav' => [
        'admin'       => 'Новый админ',
        'users'       => 'Пользователи',
        'settings'    => 'Настройки',
        'modules'     => 'Модули',
        'stats'       => 'Статистика',
        'flash_deals' => 'Flash Deals',
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
];
