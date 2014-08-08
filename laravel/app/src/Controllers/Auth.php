<?php namespace App\Controllers;

use View, Validator, Input, Redirect;
use User;
use Confide;

class Auth extends Base
{
    /**
     * Contain rules for login and register
     * 
     * @var array
     */
    protected $rules = [
        'login' => [
            'username' => 'required',
            'password' => 'required'
        ]
    ];

    /**
     * Display the form to login
     * 
     * @return View
     */
    public function login()
    {
        // Already logged in, return to home
        if (Confide::user()) {
            return Redirect::route('home');
        }

        $fields = [
            'username' => ['label' => 'Käyttäjänimi'],
            'password' => ['label' => 'Salasana', 'type' => 'password'],
        ];

        return View::make('auth.login', [
            'fields' => $fields,
            'validator' => Validator::make(Input::all(), $this->rules['login'])
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

    public function doLogin()
    {
        $v = Validator::make(Input::all(), $this->rules['login']);
        if ($v->fails()) {
            return Redirect::back()
                ->withInput(Input::except('password'))
                ->withErrors($v);
        }

        if (Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            return Redirect::intended(route('home'));
        }

        $user = new User;

        if (Confide::isThrottled($input )) {
            $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
        } elseif ($user->checkUserExists($input) and !$user->isConfirmed($input)) {
            $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
        } else {
            $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
        }

        return Redirect::route('auth.login')
            ->withInput(Input::except('password'))
            ->with('error', $err_msg);
    }
}
