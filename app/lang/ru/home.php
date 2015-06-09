<?php
$contactEmail = <<< HTML
<p>Hello,</p>

<p>Visitor <strong>:name</strong> (:phone) from :email has sent you a message:</p>

<p>------------------</p>
<p>:message</p>
<p>------------------</p>

<p>:footer</p>
HTML;

$requestMail = <<< HTML
<p>Hello,</p>

<p>Visitor <strong>:name</strong> from :email requested you to start using our onling booking solution.</p>

<p>Start using it now! It's FREE!</p>
HTML;

return [
    'customer_websites'     => 'Сайт клиента',
    'description'           => 'Создавайте великолепный вебсайты!',
    'copyright_policy'      => 'Авторские права',
    'copyright'             => 'Copyright',
    'newsletter'            => 'Рассылка',
    'enter_your_email'      => 'Введите ваш е-мейл',
    'submit'                => 'ПОДТВЕРДИТЬ',
    'location'              => 'Местоположение',
    'telephone'             => 'Телефон',
    'homepages'             => 'Сайты',
    'loyaltycard'           => 'Карта лояльности',
    'customer_register'     => 'Customer Register',
    'cashier'               => 'Кассир',
    'description_1'         => 'Наша цель - создание сайтов высшего качества, которыми бы гордились наши клиенты',
    'description_2'         => 'See the company\'s home page layout, and you see the shop and office space with elegance and comfort',
    'start_now'             => 'Бесплатный пробный период',
    'tagline'               => 'Все, что вам нужно <br>для удобного и прибыльного бизнеса',
    'next_timeslot'         => 'Следующие доступные "окна"',
    'time'                  => 'Время',
    'search_tagline'        => 'Что вы хотите заказать?', // @todo
    'search_query'          => 'Enter business name or service', // @todo
    'search_place'          => 'Helsinki, FI', // @todo
    'search'        => [
        'tagline'         => 'Что вы хотите заказать?', // @todo
        'query'           => 'Введите компанию', // @todo
        'location'        => Settings::get('default_location'), // @todo
        'about'           => 'Подробнее',
        'locations_hours' => 'Места &amp; время',
        'business_hours'  => 'Время работы',
        'buy'             => 'Купить',
        'book'            => 'Записаться',
        'button'          => 'поиск', // @todo
        'date'            => 'Любая дата', // @todo
        'time'            => 'любое время', // @todo
        'current_location' => 'Ваше местоположение',
        'validation'      => [
            'q'        => 'Please enter or select a service', // @todo
            'location' => 'Please enter or select a location', // @todo
        ],
        'geo'             => [
            'info' => 'Ваше местоположение нужно для того, чтобы показывать близкие к вам результаты поиска.'
        ]
    ],
    'cart' => [
        'add'             => 'В корзину',
        'items'           => 'item|items',
        'empty'           => 'Пусто!',
        'empty_long'      => 'Ваша корзина пуста.',
        'checkout'        => 'Виды оплаты',
        'total'           => 'Сумма заказа',
        'heading'         => 'Вы выбрали',
        'why_heading'     => 'Зачем регистрироваться перед оплатой?',
        'why_content'     => 'Зарегистрированные пользователи могут легко управлять своими заказами и другими возможностями системы Todo.',
        'process'         => 'Заплатить',
        'total_deposit'   => 'Сумма депозита',
        'pay_deposit'     => 'Оплатить депозит',
        'pay_full'        => 'Оплатить всю сумму',
        'pay_venue'       => 'Оплатить на месте',
        'terms'           => 'Пожалуйста, учтите, что если вы не появитесь в зарезервированное для вас время, вы причините салону убытки. Если вы сделаете это снова, салон может внести вас в "черный список" и не будет принимать от вас резервацию.',
        'deposit_message' => 'Выберите вид оплаты',
        'err'         => [
            'business' => 'Мы пока не поддерживаем бизнес-профили при облате. Покдлючитесь, пожалуйста, как клиент.',
            'zero_amount' => 'Покупка невозможна, поскольку в вашей корзине ничего нет',
        ]
    ],
    'choose_category'     => 'Что вы хотите заказать?', // @todo
    'how_does_it_work'    => 'How does it work?', // @todo
    'businesses'          => 'Что вы хотите заказать?', // @todo
    'businesses_category' => 'Businesses of <strong>:category</strong>', // @todo
    'more'                => 'более', // @todo
    'less'                => 'менее', // @todo
    'companies_offers'    => 'Компании с предложениями', // @todo
    'categories'          => 'категории', // @todo
    'best_offers'         => 'Лучшие предложения', // @todo
    'no_offers'           => 'Там нет никаких предложений доступны.', // @todo
    'map'                 => 'карта', // @todo
    'show_more'           => 'Показать больше', // @todo
    // How it works?
    'hiw' => [
        'heading' => 'How it works?', // @todo
        'steps' => [
            '1'      => 'Step 1', // @todo
            '2'      => 'Step 2', // @todo
            '3'      => 'Step 3', // @todo
            '1_text' => 'Select a service', // @todo
            '2_text' => 'Select a business', // @todo
            '3_text' => 'Book a time', // @todo
        ],
    ],
    // Business
    'business' => [
        'about'          => 'о нас', // @todo
        'openning_hours' => 'время работы', // @todo
        'map'            => 'карта', // @todo
        'phone'          => 'телефон', // @todo
        'email'          => 'Электронная Почта', // @todo
        'online_booking' => 'онлайн бронирование', // @todo
        'request'        => [
            'link'    => 'Спросите для онлайн-бронирования', // @todo
            'info'    => 'TВладелец магазина будет предложено использовать нашу систему бронирования онлайн.', // @todo
            'subject' => 'Запрос на использование системы онлайн-бронирования', // @todo
            'mail'    => $requestMail, // @todo
        ],
        'contact'        => [
            'index'   => 'связаться с нами', // @todo
            'heading' => 'связаться с нами', // @todo
            'name'    => 'Имя*', // @todo
            'email'   => 'Электронная Почта*', // @todo
            'phone'   => 'телефон', // @todo
            'captcha' => 'Пожалуйста, введите символы, указанные ниже*', // @todo
            'message' => 'Сообщение*', // @todo
            'sent'    => 'Ваше сообщение отправлено', // @todo
            'subject' => 'Вы получили контактную', // @todo
            'mail'    => $contactEmail, // @todo
        ]
    ],
];
