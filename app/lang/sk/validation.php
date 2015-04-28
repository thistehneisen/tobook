<?php
return [
    'captcha'              => 'Invalid security code', // @todo
    "accepted"             => ":attribute musí byť akceptované.",
    "active_url"           => ":attribute nie je platná URL.",
    "after"                => ":attribute musí byť dátum po :date.",
    "alpha"                => ":attribute môže obsahovať iba písmená.",
    "alpha_dash"           => ":attribute môže obsahovať iba písmená, čísla a pomlčky.",
    "alpha_num"            => ":attribute môže obsahovať iba písmená, čísla.",
    "array"                => ":attribute must be an array.",
    "before"               => ":attribute musí byť dátum pred :date.",
    "between"              => array(
        "numeric" => ":attribute musí byť medzi :min a :max.",
        "file"    => ":attribute musí byť medzi :min a :max kilobitov.",
        "string"  => ":attribute musí byť medzi :min a :max znakov.",
        "array"   => ":attribute musí byť medzi :min a :max položiek.",
    ),
    "confirmed"            => ":attribute potvrdenie nesúhlasí.",
    "date"                 => ":attribute nie je platný dátum.",
    "date_format"          => ":attribute nesúhlasí s formátom :format.",
    "different"            => ":attribute a :other musia byť rozdielne.",
    "digits"               => ":attribute musí byť :digits čísiel.",
    "digits_between"       => ":attribute musí byť medzi :min a :max čísiel.",
    "email"                => ":attribute musí byť platná email adresa.",
    "exists"               => "Vybraný :attribute je neplatný.",
    "image"                => ":attribute musí byť obrázok.",
    "in"                   => "Vybraný :attribute nie je platný.",
    "integer"              => ":attribute musí byť celé číslo.",
    "ip"                   => ":attribute musí byť platná IP adresa.",
    "max"                  => array(
        "numeric" => "The :attribute nemôže byť väčšie ako :max.",
        "file"    => "The :attribute nemôže byť väčšie ako :max kilobytov.",
        "string"  => "The :attribute nemôže byť väčšie ako :max znakov.",
        "array"   => "The :attribute nemôže mať viac ako :max položiek.",
    ),
    "mimes"                => "The :attribute musí byť súbor typu: :values.",
    "min"                  => array(
        "numeric" => "The :attribute musí byť najmenej :min.",
        "file"    => "The :attribute musí byť najmenej :min kilobytov.",
        "string"  => "The :attribute musí byť najmenej :min znakov.",
        "array"   => "The :attribute musí byť najmenej :min položiek.",
    ),
    "not_in"               => "Vybraný :attribute je neplatný.",
    "numeric"              => ":attribute musí byť číslo.",
    "regex"                => ":attribute format je neplatný.",
    "required"             => ":attribute pole je povinné.",
    "required_if"          => ":attribute pole je povinne ak :other je :value.",
    "required_with"        => ":attribute pole je povinne ak :values je prítomný.",
    "required_with_all"    => ":attribute pole je povinne ak :values je prítomný.",
    "required_without"     => ":attribute pole je povinne ak :values nie je prítomný.",
    "required_without_all" => ":attribute pole je povinne ak žiadný z :values nie je prítomný.",
    "same"                 => ":attribute a :other sa musia zhodovať.",
    "size"                 => array(
        "numeric" => "The :attribute musí mať veľkosť :size.",
        "file"    => "The :attribute musí mať :size kilobytov.",
        "string"  => "The :attribute musí mať :size znakov.",
        "array"   => "The :attribute musí obsahovať :size položiek.",
    ),
    "unique"               => ":attribute sa už používa.",
    "url"                  => ":attribute format je neplatný.",

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
            'required' => 'Užívateľské meno je povinné',
        ],
        'password' => [
            'required' => 'Password is required',
            'confirmed' => 'Hesla sa nezhodujú',
        ],
        'password_confirmation' => [
            'required' => 'Heslo je povinné',
        ],
        'email' => [
            'required' => 'Email is required',
            'email' => 'Email je neplatný',
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
