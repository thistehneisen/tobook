<?php
return [
    "accepted"             => ":attribute должен быть принят.",
    "active_url"           => ":attribute не действительный адрес.",
    "after"                => ":attribute должна быть позже :date.",
    "alpha"                => ":attribute может содержать только буквы.",
    "alpha_dash"           => ":attribute может содержать только буквы, цифры и тире.",
    "alpha_num"            => ":attribute может содержать только буквы и цифры.",
    "array"                => ":attribute должен быть an array.",
    "before"               => ":attribute должна быть раньше :date.",
    "between"              => array(
        "numeric" => ":attribute должен быть между :min и :max.",
        "file"    => ":attribute должен быть между :min и :max килобайт.",
        "string"  => ":attribute должен быть между :min и :max знаков.",
        "array"   => ":attribute должен быть между :min и :max штук.",
    ),
    "confirmed"            => ":attribute подтверждения не совпадают.",
    "date"                 => ":attribute недействительная дата.",
    "date_format"          => ":attribute не совпадает по формату с :format.",
    "different"            => ":attribute и :other должны различаться.",
    "digits"               => ":attribute должен быть :digits.",
    "digits_between"       => ":attribute должен быть между :min и :max.",
    "email"                => ":attribute должен быть действительный адрес е-мейла.",
    "exists"               => "Выбранный :attribute недействителен.",
    "image"                => ":attribute должен быть картинкой.",
    "in"                   => "Выбранный :attribute недействителен.",
    "integer"              => ":attribute должен быть an integer.",
    "ip"                   => ":attribute должен быть действительным IP-адресом.",
    "max"                  => array(
        "numeric" => ":attribute не может превышать :max.",
        "file"    => ":attribute не может превышать :max килобайт.",
        "string"  => ":attribute не может превышать :max знаков.",
        "array"   => ":attribute не может превышать :max штук.",
    ),
    "mimes"                => ":attribute должен быть файлом типа :values.",
    "min"                  => array(
        "numeric" => ":attribute должен быть не менее :min.",
        "file"    => ":attribute должен быть не менее :min килобайт.",
        "string"  => ":attribute должен быть не менее :min знаков.",
        "array"   => ":attribute не менее :min штук.",
    ),
    "not_in"               => "Выбранный :attribute неверен.",
    "numeric"              => ":attribute должен быть числом.",
    "regex"                => ":attribute неправильный формат.",
    "required"             => "поле :attribute нужно заполнить.",
    "required_if"          => "поле :attribute нужно заполнить если :other :value.",
    "required_with"        => "поле :attribute нужно заполнить если присутствуют :values.",
    "required_with_all"    => "поле :attribute нужно заполнить если :values присутствуют.",
    "required_without"     => "поле :attribute нужно заполнить если :values не присутствуют.",
    "required_without_all" => "поле :attribute нужно заполнить не выбрано ничегт из :values.",
    "same"                 => ":attribute и :other должны совпадать.",
    "size"                 => array(
        "numeric" => ":attribute должен быть :size.",
        "file"    => ":attribute должен быть :size килобайт.",
        "string"  => ":attribute должен быть :size знаков.",
        "array"   => ":attribute must contain :size штук.",
    ),
    "unique"               => ":attribute уже занят.",
    "url"                  => ":attribute формат неверен.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => array(
        'attribute-name' => array(
            'rule-name' => 'custom-message',
        ),
        'username' => [
            'required' => 'Имя пользователя требуется',
        ],
        'password' => [
            'required' => 'необходим пароль',
            'confirmed' => 'Пароль не совпадает',
        ],
        'password_confirmation' => [
            'required' => 'необходим пароль',
        ],
        'email' => [
            'required' => 'Требуется е-мейл',
            'email' => 'Форма е-мейла не совпадает',
        ],
    ),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => array(),
];
