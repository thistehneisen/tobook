<?php
return [
    "accepted"             => ":attribute trebuie sa fie acceptat.",
    "active_url"           => ":attribute nu este un URL valid.",
    "after"                => ":attribute trebuie sa fie o data dupa :date.",
    "alpha"                => ":attribute poate contine numai litere.",
    "alpha_dash"           => ":attribute poate contine numai litere, numere sau linii.",
    "alpha_num"            => ":attribute oate contine numai litere sau numere",
    "array"                => ":attribute trebuie sa fie o matrice",
    "before"               => ":attribute trebuie sa fie o data inainte de :date.",
    "between"              => array(
        "numeric" => ":attribute trebuie sa fie intre :min si :max.",
        "file"    => ":attribute trebuie sa fie :min si :max kb.",
        "string"  => ":attribute trebuie sa fie :min si :max caractere.",
        "array"   => ":attribute trebuie sa contina intre :min si :max elemente.",
    ),
    "confirmed"            => " Confirmarea pentru :attribute nu se potriveste.",
    "date"                 => ":attribute nu este o data valida.",
    "date_format"          => ":attribute nu se potriveste cu formatul :format.",
    "different"            => ":attribute si :other trebuie sa difere.",
    "digits"               => ":attribute trebuie sa fie de :digits cifre.",
    "digits_between"       => ":attribute trebuie sa contina intre :min si :max cifre.",
    "email"                => ":attribute trebuie sa fie o adresa de email valida.",
    "exists"               => ":attribute nu este valid.",
    "image"                => ":attribute trebuie sa fie o imagine.",
    "in"                   => ":attribute nu este valid.",
    "integer"              => ":attribute trebuie sa fie un numar intreg",
    "ip"                   => ":attribute trebuie sa fie o adresa IP valida.",
    "max"                  => array(
        "numeric" => ":attribute nu poate fi mai mare decat :max.",
        "file"    => ":attribute nu poate fi mai mare decat :max kb.",
        "string"  => ":attribute nu poate fi mai mare decat :max caractere.",
        "array"   => ":attribute nu poate avea mai mult de :max elemente.",
    ),
    "mimes"                => ":attribute trebuie sa fie un fisier de tip: :values.",
    "min"                  => array(
        "numeric" => ":attribute trebuie sa fie de cel putin :min.",
        "file"    => ":attribute trebuie sa fie de cel putin :min kb.",
        "string"  => ":attribute trebuie sa fie de cel putin :min caractere.",
        "array"   => ":attribute trebuie sa aiba cel putin :min elemente.",
    ),
    "not_in"               => ":attribute nu este valid.",
    "numeric"              => ":attribute trebuie sa fie un numar.",
    "regex"                => "Formatul :attribute nu este valid.",
    "required"             => "Campul :attribute este necesar.",
    "required_if"          => "Campul :attribute este necesar cand :other este :value.",
    "required_with"        => "Campul :attribute este necesar cand :values este prezent.",
    "required_with_all"    => "Campul :attribute este necesar cand :values este prezent.",
    "required_without"     => "Campul :attribute este necesar cand :values nu este prezent.",
    "required_without_all" => "Campul :attribute este necesar cand nici una dintre :values nu sunt prezente.",
    "same"                 => "Campul :attribute si :other trebuie sa se potriveasca.",
    "size"                 => array(
        "numeric" => ":attribute trebuie sa fie de :size.",
        "file"    => ":attribute trebuie sa fie de :size kb.",
        "string"  => ":attribute trebuie sa fie de :size caractere.",
        "array"   => ":attribute trebuie sa contina :size elemente.",
    ),
    "unique"               => ":attribute a fost deja luat.",
    "url"                  => "Formatul :attribute nu este valid.",

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
            'required' => 'Este nevoie de Utilizator',
        ],
        'password' => [
            'required' => 'Este nevoie de parola',
            'confirmed' => 'Parola nu se potriveste',
        ],
        'password_confirmation' => [
            'required' => 'Este nevoie de parola',
        ],
        'email' => [
            'required' => 'Este nevoie de email',
            'email' => 'Formular de email este incorect',
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
