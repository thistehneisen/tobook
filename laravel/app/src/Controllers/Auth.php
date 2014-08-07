<?php namespace App\Controllers;

use View;

class Auth extends Base
{
    public function login()
    {
        $fields = [
            'username' => ['label' => 'Käyttäjänimi'],
            'password' => ['label' => 'Salasana', 'type' => 'password'],
        ];

        return View::make('auth.login', [
            'fields' => $fields
        ]);
    }

    public function register()
    {
        $fields = [
            'username' => ['label' => 'Käyttäjänimi'],
            'password' => ['label' => 'Salasana', 'type' => 'password'],
            'confirm'  => ['label' => 'Vahvista salasana', 'type' => 'password'],
            'name'     => ['label' => 'Nimi'],
            'email'    => ['label' => 'Sähköposti', 'type' => 'email'],
            'phone'    => ['label' => 'Puhelin'],
        ];

        return View::make('auth.register', [
            'fields' => $fields
        ]);
    }
}
