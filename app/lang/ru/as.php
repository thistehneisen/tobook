<?php

$cancelMessage = <<< HTML
Вы отменили заказ: {BookingID}

{Services}
HTML;

return [
    'index' => [
        'heading'       => 'Здравствуйте!',
        'description'   => 'Вы можете просмотреть календарь всех доступных работников. Зеленое время свободно для заказа, серое заказать нельзя.',
        'today'         => 'Сегодня',
        'tomorrow'      => 'Завтра',
        'print'         => 'Печатать',
        'refresh'       => 'Refresh',//@todo
        'calendar'      => 'Календарь',
    ],
    'services' => [
        'heading'       => 'Услуги',
        'edit'          => 'Редактировать услугу',
        'custom_time'   => 'Выбранное время',
        'master_categories'  => 'Oсновная категория',
        'treatment_types'    => 'Тип обработки',
        'categories' => [
            'all'           => 'Все категории',
            'add'           => 'Добавить категорию',
            'edit'          => 'Редактировать категорию',
            'name'          => 'Название',
            'description'   => 'Описание',
            'is_show_front' => 'Показать на первой странице?',
            'no_services'   => 'Для этой категории нет услуг',
            'availability'  => 'Доступность',
            'category_name' => 'Название категории',
            'error'         => [
                'category_current_in_use' => 'Эта категория сейчас используется. Сначала удалите все услуги, которые имеют к ней отношение.'
            ]
        ],
        'resources' => [
            'all'         => 'Все ресурсы',
            'add'         => 'Добавить новый ресурс',
            'edit'        => 'Редактировать ресурс',
            'name'        => 'Название',
            'description' => 'Описание',
            'quantity'    => 'Количество',
        ],
        'rooms' => [
            'all'         => 'Все комнаты',
            'add'         => 'Добавить комнату',
            'edit'        => 'Редактировать комнату',
            'name'        => 'Название',
            'description' => 'Описание',
        ],
        'extras' => [
            'all'         => 'Все дополнительные услуги',
            'add'         => 'Добавить дополнительную услугу',
            'edit'        => 'Редактировать дополнительную услугу',
            'name'        => 'Название',
            'description' => 'Описание',
            'price'       => 'Цена',
            'length'      => 'Длительность',
            'is_hidden'   => 'Hidden from frontend',
            'msg_extra'   => 'Хотите зарезервировать?',
        ],
        'all'          => 'Все услуги',
        'index'        => 'Услуги',
        'desc'         => 'Здесь можно редактировать или добавлять новые услуги',
        'add'          => 'Добавить новую услугу',
        'add_desc'     => 'Добавьте нового работника: укажите название и описание услуги. Вы можете связать работника с услугой',
        'name'         => 'Название',
        'description'  => 'Описание',
        'price'        => 'Цена',
        'duration'     => 'Длительность',
        'length'       => 'Всего',
        'during'       => 'Длительность',
        'before'       => 'До',
        'after'        => 'После',
        'total'        => 'Bремя визитa',
        'category'     => 'Категория',
        'is_active'    => 'Активно',
        'resource'     => 'Ресурс',
        'room'         => 'Комната',
        'extra'        => 'Дополнительные услуги',
        'employees'    => 'Работники',
        'no_employees' => 'Вы не выбрали ни одного работника',
        'no_name'      => 'Без названия',
        'error'        => [
            'service_current_in_use' => 'Услуга сейчас используется. Сначала удалите все заказы, которые с ней связаны.'
        ]
    ],
    'bookings' => [
        'confirmed'         => 'Подтверждено',
        'pending'           => 'В очереди',
        'cancelled'         => 'Отменено',
        'arrived'           => 'Получено',
        'paid'              => 'Оплачено',
        'not_show_up'       => 'Kлиент не пришел',
        'change_status'     => 'Изменить статус заказа',
        'all'               => 'Заказы',
        'add'               => 'Добавить новый заказ',
        'invoices'          => 'Счета',
        'consumer'          => 'Клиент',
        'date'              => 'Дата',
        'total'             => 'Всего',
        'start_at'          => 'Начало',
        'end_at'            => 'Конец',
        'status'            => 'Статус',
        'total_price'       => 'Цена',
        'uuid'              => 'UUID',
        'ip'                => 'IP',
        'add_service'       => 'Добавить услугу',
        'booking_info'      => 'Информация о заказе',
        'booking_id'        => 'ID заказа',
        'categories'        => 'Категории',
        'services'          => 'Услуги',
        'service_time'      => 'Время услуги',
        'modify_time'       => 'Изменить время',
        'plustime'          => 'Резерв времени',
        'total_length'      => 'Общая длительность',
        'modify_duration'   => 'Изменить длительность',
        'employee'          => 'Работник',
        'notes'             => 'Заметки',
        'first_name'        => 'Имя',
        'last_name'         => 'Фамилия',
        'email'             => 'Е-мейл',
        'phone'             => 'Телефон',
        'address'           => 'Адрес',
        'city'              => 'Город',
        'postcode'          => 'Почтовый индекс',
        'country'           => 'Страна',
        'confirm_booking'   => 'Подтвердить заказ',
        'service_employee'  => 'Работник',
        'date_time'         => 'Дата',
        'price'             => 'Цена',
        'extra_service'     => 'Дополнительная услуга',
        'keyword'           => 'Ключевое слово',
        'edit'              => 'Редактировать заказы',
        'terms'             => 'Условия',
        'terms_agree'       => 'Я прочел и согласен с условиями заказа',
        'terms_of_agreement'=> 'Cогласен с <a {terms_class}>Правилами пользования</a>',
        'cancel_message'    => $cancelMessage,
        'cancel_confirm'    => 'Вы уверены, что хотите отменить заказ %s?',
        'modify_booking'    => 'Изменить заказ',
        'reschedule'        => 'Обновить календарь',
        'confirm_reschedule'=> 'Подтвердить обновление календаря',
        'cancel_reschedule' => 'Отменить обновление календаря',
        'own_customer'      => 'Свой клиент',
        'request_employee'  => 'Запрос конкретного работника',
        'deposit'           => 'Deposit payment',
        'search_placeholder'=> 'Поиск потребителя',
        'cancel_email_title'=> 'Резервация успешно отменена',
        'cancel_email_body' => 'Клиент отменил (эту) резервацию: <br> %s',
        'confirmation_settings'=> 'Hастройки подтверждения',
        'confirmation_email'=> 'Hастройки е-мейл',
        'confirmation_sms'  => 'Hастройки cmc',
        'reminder_email'    => 'Напоминание no электронной почте',
        'reminder_sms'      => 'Напоминание no cmc',
        'reminder_sms_before'   => 'Отправить смс с напоминанием до',
        'reminder_email_before' => 'Отправить напоминание электронной почты до',
        'reminder_sms_time_unit'   => 'час/День',
        'reminder_email_time_unit' => 'час/День',
        'error'             => [
            'add_overlapped_booking'      => 'Время заказа перекрывается!',
            'insufficient_slots'          => 'Для этого заказа не хватает свободных "окон"!',
            'invalid_consumer_info'       => 'Сохранение информации о клиенте невозможно',
            'terms'                       => 'Вы должны согласиться с условиями использования.',
            'service_empty'               => 'Пожалуйста, выберите услугу и время!',
            'unknown'                     => 'Что-то пошло не так!',
            'exceed_current_day'          => 'Окончание времени заказа не может выходить за рамки рабочего дня',
            'overllapped_with_freetime'   => 'Заказ совпадает со свободным временем работника',
            'empty_total_time'            => 'Общее время оказания услуги должно быть как минимум больше 1',
            'uuid_notfound'               => 'ID заказа не найден',
            'not_enough_slots'            => 'Для заказа недостаточно свободного времени.',
            'employee_not_servable'       => 'Этот работник не оказывает данную услугу.',
            'id_not_found'                => 'Заказ не найден',
            'start_time'                  => 'Время начала заказа указанл неверно',
            'service_time_invalid'        => 'Время оказания услуги для этого заказа не найдено',
            'overlapped_with_freetime'    => 'Заказ совпадает со свободным временем работника',
            'reschedule_single_only'      => 'Заказ нескольких услуг нельзя перепланировать',
            'reschedule_unbooked_extra'   => 'Заказ нельзя перепланировать',
            'not_enough_resources'        => 'Необходимые ресурсы недоступны!',
            'not_enough_rooms'            => 'Недостаточно места!',
            'empty_start_time'            => 'Время начала заказа не может оставаться незаполненным',
            'booking_not_found'           => 'Заказ не найден!',
            'past_booking'                => 'Заказ на даты из прошлого сделать нельзя!',
            'delete_last_booking_service' => 'Вы не можете удалить последний заказ услуги',
            'before_min_distance'         => 'Вы не можете сделать заказ до min distance day',
            'after_max_distance'          => 'Вы не можете сделать заказ после min distance day',
            'missing_services'            => 'Add a service to continue!',//@todo
        ],
        'warning'      => [
            'existing_user'   => 'В нашей системе есть пользователь, связанный с этим адресом. Хотите использовать эту информацию?',
        ],
    ],
    'employees' => [
        'all'                              => 'Работники',
        'add'                              => 'Добавить нового работника',
        'edit'                             => 'Редактировать работника',
        'name'                             => 'Имя',
        'phone'                            => 'Телефон',
        'email'                            => 'Е-мейл',
        'description'                      => 'Описание',
        'is_subscribed_email'              => 'Подписан на е-мейл?',
        'is_subscribed_sms'                => 'Подписан на SMS?',
        'is_received_calendar_invitation'  => 'Получает приглашения через календарь?',
        'services'                         => 'Услуги',
        'status'                           => 'Статус',
        'is_active'                        => 'Aктивация',
        'avatar'                           => 'Аватар',
        'default_time'                     => 'Время по умолчанию',
        'custom_time'                      => 'Время по выбору',
        'days_of_week'                     => 'Дни недели',
        'start_time'                       => 'Начало работы',
        'end_time'                         => 'Конец работы',
        'date_range'                       => 'Диапазон',
        'day_off'                          => 'Выходной?',
        'confirm'                          => [
            'delete_freetime' => 'Вы уверены, что хотите удалить выбранное время из календаря?'
        ],
        'free_time'                        => 'Свободное время',
        'free_times'                       => 'Свободное время',
        'free_time_type'                   => 'Свободное тип время',
        'working_free_time'                => 'Рабочая',
        'personal_free_time'               => 'личный',
        'working_times'                    => 'Рабочее время',
        'add_free_time'                    => 'Добавить свободное время',
        'start_at'                         => 'Начало в',
        'end_at'                           => 'Конец в',
        'date'                             => 'Дата',
        'is_day_off'                       => 'Выходной',
        'workshifts'                       => 'Изменения графика',
        'workshift_planning'               => 'Планирование графика',
        'workshift_summary'                => 'Отчет о графике',
        'from_date'                        => 'От',
        'to_date'                          => 'До',
        'weekday'                          => 'Рабочий день',
        'employee'                         => 'Работник',
        'freelancer'                       => 'Freelancer',//@todo
        'business_id'                      => 'Business ID',//@todo
        'account'                          => 'Account',//@todo
        'activation'                       => 'Activation',//@todo
        'saturday_hours'                   => 'Рабочие часы в субботу',
        'sunday_hours'                     => 'Рабочие часы в воскресенье',
        'monthly_hours'                    => 'Рабочие часы за месяц',
        'weekly_hours'                     => 'Еженедельные часов',
        'error'                            => [
            'freetime_overlapped_with_booking' => 'Свободное время совпадает с заказами',
            'empty_employee_ids'               => 'Please select at least one employee!',
        ],
    ],
    'embed' => [
        'heading'          => 'Название',
        'description'      => 'Описание',
        'embed'            => 'Bставлять',
        'preview'          => 'Превью',
        'back_to_services' => 'Назад к услугам',
        'select_date'      => 'Выбрать дату',
        'select_service'   => 'Выбрать услуги',
        'guide_text'       => 'Выбрать доступное время',
        'make_appointment' => 'Записаться',
        'cancel'           => 'Отменить',
        'empty_cart'       => 'Ваша корзина пуста',
        'start_time'       => 'Начинается',
        'end_time'         => 'Заканчивается',
        'booking_form'     => 'Форма заказа',
        'name'             => 'Имя',
        'email'            => 'Е-мейл',
        'phone'            => 'Телефон',
        'checkout'         => 'Оплата',
        'fi_version'       => 'Финский',
        'en_version'       => 'Английский',
        'sv_version'       => 'Шведский',
        'book'             => 'Подтвердить резервацию',
        'loading'          => 'Загружается &hellip;',
        'success'          => 'Спасибо за резервацию! Подтверждение отослано на указанный Вами электронный адресс.',
        'success_line1'    => '<h2>Спасибо!</h2>',
        'success_line2'    => '<h3>Ваша резервация принята.</h3>',
        'success_line3'    => '<h3>Через <span id="as-counter">10</span> секунды вы будете перенаправлены на стартовую страницу.</h3>',
        'thankyou_line1'   => 'СПАСИБО, ВАША РЕЗЕРВАЦИЯ ПРИНЯТА!',
        'thankyou_line2'   => 'За услугу Вы сможете расплатиться на месте, в салоне',
        'confirm'          => 'Подтвердить заказ',
        'layout_2'         => [
            'select_service'      => 'Выбрать услугу и дату',
            'select_service_type' => 'Выбрать рубрику для услуги',
            'services'            => 'Услуги',
            'selected'            => 'Выбранные услуги',
            'extra_services'      => 'Дополнительные услуги',
            'employees'           => 'Работник',
            'choose'              => 'Выбрать дату',
            'unavailable'         => 'Время занято',
            'form'                => 'Контактная информация',
            'date'                => 'Дата',
            'price'               => 'Цена',
            'name'                => 'Имя',
            'phone'               => 'Телефон',
            'email'               => 'Е-мейл',
            'thanks'              => 'Спасибо за ваш заказ!',
        ],
        'layout_3'         => [
            'select_service'  => 'Выбрать услугу',
            'select_employee' => 'Выбрать работника',
            'select_datetime' => 'Выбрать дату и время',
            'contact'         => 'Контактная информация',
            'service'         => 'Услуга',
            'employee'        => 'Работник',
            'name'            => 'Ваше имя',
            'notes'           => 'Заметки',
            'postcode'        => 'Почтовый индекс',
            'empty'           => 'В этот день нет доступного времени.',
            'payment_note'    => 'После того, как вы сделали заказ, вас перенаправят к странице оплаты.',
            'confirm_service' => 'Подтвердить заказ услуги',
        ],
        'cp' => [
            'heading' => 'Забронируйте услуги',
            'select' => 'Выбрать',
            'sg_service' => 'услуга',
            'pl_service' => 'услугi',
            'employee' => 'Работник',
            'time' => 'время',
            'salon' => 'салон',
            'price' => 'цена',
            'service' => 'Услуга',
            'details' => 'Детали бронирования',
            'go_back' => 'Возвращаться',
            'how_to_pay' => 'Как вы хотите платить?',
            'almost_done' => 'Ваш заказ почти сделано',
            'first_employee' => 'Первый доступный сотрудник',
            'coupon_code' => 'код купона',
        ]
    ],
     'options' => [
        'heading'                    => 'Настройки',
        'updated'                    => 'Настройки обновлены',
        'invalid_style_external_css' => 'Invalid external css file!',
        'general' => [
            'index'           => 'Основное',
            'heading'         => 'Основные настройки',
            'info'            => 'Введите ваши настройки',
            'currency'        => 'Валюта',
            'custom_status'   => 'Статус по выбору',
            'datetime_format' => 'Формат даты и времени',
            'date_format'     => 'Формат даты',
            'time_format'     => 'Формат времени',
            'layout'          => 'Дизайн',
            'seo_url'         => 'Ссылки для SEO',
            'timezone'        => 'Часовой пояс',
            'week_numbers'    => 'Номер недели',
            'week_start'      => 'Показывать номер недели?',
            'phone_number'    => 'Номер телефона для SMS',
            'business_name'   => 'Название бизнеса',
        ],
        'booking'   => [
            'heading'                                        => '',
            'info'                                           => '',
            'disable_booking'                                => 'Отключить виджет бронирования',
            'index'                                          => 'Заказы',
            'booking_form'                                   => 'Форма для заказов',
            'reminders'                                      => 'Напоминание',
            'confirmations'                                  => 'Подтверждение',
            'terms'                                          => 'Условия',
            'confirmed'                                      => 'Подтверждено',
            'pending'                                        => 'В очереди',
            'accept_bookings'                                => 'Принять заказы',
            'hide_prices'                                    => 'Скрыть цены',
            'step'                                           => 'Шаг',
            'bookable_date'                                  => 'Дата для заказа',
            'status_if_paid'                                 => 'Режим платных заказов по умолчанию',
            'status_if_not_paid'                             => 'Режим бесплатных заказов по умолчанию',
            'notes'                                          => 'Заметки',
            'address'                                        => 'Адрес',
            'city'                                           => 'Город',
            'postcode'                                       => 'Почтовый индекс',
            'country'                                        => 'Страна',
            'email'                                          => 'Е-мейл',
            'reminder_enable'                                => 'Включить оповещения',
            'reminder_email_before'                          => 'Отправить оповещения по е-мейлу',
            'reminder_subject'                               => 'Тема оповещения',
            'reminder_subject_default'                       => 'Тема оповещения по умолчанию',
            'reminder_body'                                  => 'Текст оповещения по е-мейлу',
            'reminder_sms_hours'                             => 'Отправить SMS-оповещение',
            'reminder_sms_country_code'                      => 'Код страны',
            'reminder_sms_message'                           => 'SMS-сообщение',
            'terms_url'                                      => 'URL условий заказа',
            'terms_body'                                     => 'Условия заказа',
            'confirm_subject_client'                         => 'Заголовок информации о клиенте',
            'confirm_tokens_client'                          => 'Текст е-мейла',
            'confirm_email_enable'                           => 'Влючить е-мейлы',
            'confirm_sms_enable'                             => 'Включить SMS',
            'confirm_sms_country_code'                       => 'Код',
            'confirm_consumer_sms_message'                   => 'SMS клиентам',
            'confirm_employee_sms_message'                   => 'SMS работникам',
            'confirm_subject_employee'                       => 'Заголовок оповещения для работников',
            'confirm_tokens_employee'                        => 'Текст е-мейла',
            'terms_enabled'                                  => 'Включить условия',
            'default_nat_service'                            => 'Следующая доступная услуга по умолчанию',
            'show_quick_workshift_selection'                 => 'Show on calendar workshift selection',
            'min_distance'                                   => 'Мин. расстояние',
            'max_distance'                                   => 'Макс. расстояние',
            'auto_select_employee'                           => 'Автоматический выбор работника',
            'auto_expand_all_categories'                     => 'Автоматическое заполнение категорию',
            'show_employee_request'                          => 'Показать опции просьбой для работника',
            'factor'                                         => 'Factor',
            'hide_empty_workshift_employees'                 => 'Скрыть сотрудники с не рабочей смены',
            'announcements'                                  => 'Объявления',
            'announcement_enable'                            => 'Включить объявления',
            'announcement_content'                           => 'Содержание анонса прошло',
            'cancel_before_limit'                            => 'Отменить действует до (часов)',
            'confirmation_sms'                               => 'Hастройки cmc',
            'confirmation_email'                             => 'Hастройки е-мейл',
            'confirmation_reminder'                          => 'Message default',
            'reminder_sms'                                   => 'SMS-напоминание',
            'reminder_email'                                 => 'Напоминаниена e-mail',
            'reminder_sms_before'                            => 'Отправить SMS до',
            'reminder_email_before'                          => 'Отправить e-mail до',
            'reminder_sms_unit'                              => 'Время СМС устройство',
            'reminder_email_unit'                            => 'E-mail Блок время',
            'reminder_sms_time_unit'                         => 'Часы/дни до',
            'reminder_email_time_unit'                       => 'Часы/дни до',
        ],
        'style' => [
            'heading'                           => '',
            'info'                              => '',
            'index'                             => 'Стиль сайта',
            'style_logo'                        => 'Ссылка для логотипа',
            'style_banner'                      => 'Баннер',
            'style_heading_color'               => 'Цвет "шапки" сайта',
            'style_text_color'                  => 'Цвет текста',
            'style_background'                  => 'Фон',
            'style_custom_css'                  => 'Свой CSS',
            'style_external_css'                => 'External CSS Link',
            'style_main_color'                  => 'Основной цвет',
            'style_heading_background'          => 'Фон "шапки" сайта',
        ],
        'working_time' => [
            'index' => 'Календарь',
        ],
        'discount' => [
            'discount'            => 'Discount',
            'last-minute'         => 'Last minute discount',
            'business-hour'       => 'business hour',
            'business-hours'      => 'business hours',
            'full-price'          => 'Full price',
            'afternoon_starts_at' => 'Afternoon starts at',
            'evening_starts_at'   => 'Evening starts at',
            'is_active'           => 'Is Enabled',
            'before'              => 'Before',
            'error' => [
                'evening_starts_before_afternoon' => 'Afternoon must starts before evening starts'
            ],
        ]
    ],
    'reports' => [
        'index'     => 'Отчеты',
        'employees' => 'Работники',
        'services'  => 'Услуги',
        'generate'  => 'Генерировать',
        'start'     => 'Дата начала',
        'end'       => 'Дата окончания',
        'booking'   => [
            'total'       => 'Всего заказов',
            'confirmed'   => 'Подтвержденные заказы',
            'unconfirmed' => 'Неподтвержденные заказы',
            'cancelled'   => 'Отмененные заказы',
        ],
        'statistics'=> 'Статистика',
        'monthly'   => 'Ежемесячный отчет',
        'stat' => [
            'monthly'      => 'Ежемесячный обзор',
            'bookings'     => 'Заказы',
            'revenue'      => 'Оборот',
            'working_time' => 'Время работы',
            'booked_time'  => 'Время заказов',
            'occupation'   => 'Загруженность %'
        ]
    ],
    'crud' => [
        'bulk_confirm'   => 'Вы уверены?',
        'success_add'    => 'Запись добавлена.',
        'success_edit'   => 'Данные обновлены.',
        'success_delete' => 'Запись удалена.',
        'success_bulk'   => 'Запись удалена.',
        'sortable'       => 'Перетащите, чтобы поменять местами',
    ],
    'coupon' => [
        'not_found'    => 'Этот купон код не найден в tobook.lv системе',
        'used_coupon'  => 'Этот купон код уже используется',
        'invalid_date' => 'Скидочный код не активен, или уже истек',
        'valid_coupon' => 'Вы получаете %d%s скидку с этим купоном',
        'invalid_coupon' => 'Неверный купон',
    ],
    'nothing_selected' => 'Выберите',
    'reminder' => [
        'sms_reminder_content' => 'Напоминание о Вашей резервации: {Services}',
        'email_reminder_content' => 'Atgādinājums par Jūsu rezervāciju: {Services}, {Address}',
    ]
];
