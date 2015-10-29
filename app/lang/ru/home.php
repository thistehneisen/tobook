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

$homeContactEmail = <<< HTML
<p>Hello,</p>

<p>Visitor from :email sent you a message:</p>

<p>------------------</p>
<p>:content</p>
<p>------------------</p>
HTML;

$footerAbout = <<< HTML
<p>Прямой сервис бронирования и резервации услуг в салонах красоты обеспечивается АО DELFI, которое подчиняется латвийскому законодательству. Офис фирмы находится по адресу: Рига, ул. Мукусалас, 41b-8.Регистрационный номер предприятия 40003504352.</p>
<p>Контактный телефон: 67784050</p>
<p>E-mail: <a href="mailto:info@tobook.lv">info@tobook.lv</a></p>
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
    'search_tagline'        => 'Что вы хотите заказать?',
    'search_query'          => 'Enter business name or service',
    'search_place'          => 'Helsinki, FI',
    'video_tutorial_text'   => 'Как это работает?',
    'video_tutorial_link'   => 'https://www.youtube.com/watch?v=tG170YStzeQ?rel=0&amp;showinfo=0&autoplay=1',
    'image_tutorial_link'   => asset_path('core/img/how-does-it-work-ru.png'),
    'current_total_bookings'=> 'Количество размещенных резерваций',
    'search'        => [
        'tagline'         => 'Что вы хотите заказать?',
        'query'           => 'Bведите компанию, или услуги',
        'location'        => Settings::get('default_location'),
        'about'           => 'Подробнее',
        'locations_hours' => 'Места &amp; время',
        'business_hours'  => 'Время работы',
        'buy'             => 'Купить',
        'book'            => 'Записаться',
        'button'          => 'поиск',
        'date'            => 'Любая дата',
        'time'            => 'любое время',
        'current_location' => 'Ваше местоположение',
        'validation'      => [
            'q'        => 'Пожалуйста, введите бизнес или услугу',
            'location' => 'Пожалуйста, введите местоположение',
        ],
        'geo'             => [
            'info' => 'Ваше местоположение нужно для того, чтобы показывать близкие к вам результаты поиска.'
        ]
    ],
    'cart' => [
        'add'             => 'В корзину',
        'items'           => 'пункт|пункты|', // Must end with a | or will cause errors
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
        'checkout_message'=> 'Выберите вид оплаты',
        'err'         => [
            'business' => 'Мы пока не поддерживаем бизнес-профили при облате. Покдлючитесь, пожалуйста, как клиент.',
            'zero_amount' => 'Покупка невозможна, поскольку в вашей корзине ничего нет',
        ]
    ],
    'choose_category'     => 'Что вы хотите заказать?',
    'how_does_it_work'    => 'Как это работает?',
    'businesses'          => 'Что вы хотите заказать?',
    'businesses_category' => 'Businesses of <strong>:category</strong>', // @todo
    'more'                => 'более',
    'less'                => 'менее',
    'companies_offers'    => 'Компании с предложениями',
    'categories'          => 'категории',
    'best_offers'         => 'Лучшие предложения',
    'no_offers'           => 'На данный момент актуальных предложений нет.',
    'map'                 => 'карта',
    'show_more'           => 'Показать больше',
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
        'about'          => 'о нас',
        'openning_hours' => 'время работы',
        'map'            => 'карта',
        'address'        => 'Адрес',
        'phone'          => 'телефон',
        'email'          => 'Электронная Почта',
        'online_booking' => 'онлайн бронирование',
        'payment_methods'=> 'Oплата',
        'request'        => [
            'link'    => 'Спросите для онлайн-бронирования',
            'info'    => 'TВладелец магазина будет предложено использовать нашу систему бронирования онлайн.',
            'subject' => 'Запрос на использование системы онлайн-бронирования',
            'mail'    => $requestMail,
        ],
        'contact'        => [
            'index'   => 'Kонтакты',
            'heading' => 'Kонтакты',
            'name'    => 'Имя*',
            'email'   => 'Электронная Почта*',
            'phone'   => 'телефон',
            'captcha' => 'Пожалуйста, введите символы, указанные ниже*',
            'message' => 'Сообщение*',
            'sent'    => 'Ваше сообщение отправлено',
            'subject' => 'Вы получили контактную',
            'mail'    => $contactEmail,
        ]
    ],
    // Contact form
    'contact' => [
        'subject' => 'контакт / Tobook.lv',
        'body'    => $homeContactEmail,
        'sent'    => 'Спасибо, мы получили ваше сообщение.',
    ],
    // Static pages
    'pages' => [
        'terms_conditions' => 'Правила и условия',
        'privacy_cookies'  => 'Использование cookies',
    ],
    'footer' => [
        'subscribe' => 'Подписаться на новости',
        'email' => 'Ваш e-mail',
        'btn_subscribe' => 'ПОДПИСАТЬСЯ',
        'about' => 'O :site',
        'about_content' => $footerAbout,
        'info' => 'Информация для покупателей',
        'terms' => 'Правила и условия',
        'policy' => 'Использование cookies',
        'contact' => 'Контактная форма',
        'message' => 'Напишите здесь, пожалуйста, свой вопрос или предложение',
        'send' => 'ОТПРАВИТЬ',
    ]
];
