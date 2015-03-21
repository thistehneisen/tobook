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
    'search_tagline'        => 'What do you want to book?', // @todo
    'search_query'          => 'Enter business name or service', // @todo
    'search_place'          => 'Helsinki, FI', // @todo
    'search'        => [
        'tagline'         => 'What do you want to book?', // @todo
        'query'           => 'Enter business name or service', // @todo
        'location'        => Settings::get('default_location'), // @todo
        'about'           => 'Подробнее',
        'locations_hours' => 'Места &amp; время',
        'business_hours'  => 'Время работы',
        'buy'             => 'Купить',
        'book'            => 'Записаться',
        'button'          => 'Search', // @todo
        'date'            => 'Any date', // @todo
        'time'            => 'Any time', // @todo
        'geo'             => [
            'info' => 'Ваше местоположение нужно для того, чтобы показывать близкие к вам результаты поиска.'
        ]
    ],
    'cart' => [
        'add'         => 'В корзину',
        'items'       => 'item|items',
        'empty'       => 'Пусто!',
        'empty_long'  => 'Ваша корзина пуста.',
        'checkout'    => 'Купить',
        'total'       => 'Итого',
        'heading'     => 'Вы выбрали',
        'why_heading' => 'Зачем регистрироваться перед оплатой?',
        'why_content' => 'Зарегистрированные пользователи могут легко управлять своими заказами и другими возможностями системы Todo.',
        'process'     => 'Заплатить',
        'err'         => [
            'business' => 'Мы пока не поддерживаем бизнес-профили при облате. Покдлючитесь, пожалуйста, как клиент.',
            'zero_amount' => 'Покупка невозможна, поскольку в вашей корзине ничего нет',
        ]
    ],
    'choose_category'     => 'Choose category', // @todo
    'how_does_it_work'    => 'How does it work?', // @todo
    'businesses'          => 'All businesses', // @todo
    'businesses_category' => 'Businesses of <strong>:category</strong>', // @todo
    'more'                => 'More', // @todo
    'less'                => 'Less', // @todo
    'companies_offers'    => 'Companies with offers', // @todo
    'categories'          => 'Categories', // @todo
    'best_offers'         => 'Best offers', // @todo
    'no_offers'           => 'There is no offer available.', // @todo
    // Business
    'business' => [
        'openning_hours' => 'Openning hours', // @todo
        'map'            => 'Map', // @todo
        'phone'          => 'Phone', // @todo
        'email'          => 'Email', // @todo
        'request'        => [
            'link'    => 'Ask for online booking', // @todo
            'info'    => 'The shop owner will be asked to use our online booking system.', // @todo
            'subject' => 'Request to use online booking system', // @todo
            'mail'    => $requestMail, // @todo
        ],
        'contact'        => [
            'index'   => 'Contact', // @todo
            'heading' => 'Contact us', // @todo
            'name'    => 'Name*', // @todo
            'email'   => 'Email*', // @todo
            'phone'   => 'Phone', // @todo
            'captcha' => 'Please enter the characters below*', // @todo
            'message' => 'Message*', // @todo
            'sent'    => 'Your message has been sent', // @todo
            'subject' => 'You got a contact message', // @todo
            'mail'    => $contactEmail, // @todo
        ]
    ],
];
