<?php namespace App\Controllers;

use View, Validator, Input, Redirect, Config, Session;
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
        ],
        'register' => [
            'username'              => 'required',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
            'email'                 => 'required|email'
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

    /**
     * Handle login
     *
     * @return Redirector
     */
    public function doLogin()
    {
        $input = [
            'username' => Input::get('username'),
            'email'    => Input::get('username'),
            'password' => Input::get('password'),
        ];

        $v = Validator::make($input, $this->rules['login']);
        if ($v->fails()) {
            return Redirect::back()
                ->withInput(Input::except('password'))
                ->withErrors($v);
        }

        if (Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            return Redirect::intended(route('home'));
        }

        $user = new User;

        if (Confide::isThrottled($input)) {
            $errMsg = trans('confide::confide.alerts.too_many_attempts');
        } elseif ($user->checkUserExists($input) and !$user->isConfirmed($input)) {
            $errMsg = trans('confide::confide.alerts.not_confirmed');
        } else {
            $errMsg = trans('confide::confide.alerts.wrong_credentials');
        }

        return Redirect::route('auth.login')
            ->withInput(Input::except('password'))
            ->withErrors($this->createMessageBag([$errMsg]), 'top');
    }

    /**
     * Display the registration form
     *
     * @return \View
     */
    public function register()
    {
        $fields = [
            'username'              => ['label' => 'Käyttäjänimi'],
            'password'              => ['label' => 'Salasana', 'type' => 'password'],
            'password_confirmation' => ['label' => 'Vahvista salasana', 'type' => 'password'],
            'email'                 => ['label' => 'Sähköposti', 'type' => 'email'],
            'name'                  => ['label' => 'Nimi'],
            'phone'                 => ['label' => 'Puhelin'],
        ];

        return View::make('auth.register', [
            'fields' => $fields,
            'validator' => Validator::make(Input::all(), $this->rules['register'])
        ]);
    }

    /**
     * When a guest submits the form to register
     *
     * @return \Redirect
     */
    public function doRegister()
    {
        $v = Validator::make(Input::all(), $this->rules['register']);
        if ($v->fails()) {
            return Redirect::back()
                ->withInput(Input::except(['password', 'password_confirmation']))
                ->withErrors($v);
        }

        $user = new User;

        $user->username              = Input::get('username');
        $user->email                 = Input::get('email');
        $user->password              = Input::get('password');
        $user->password_confirmation = Input::get('password_confirmation');

        $user->save();

        if ($user->getKey()) {
            $notice = trans('confide::confide.alerts.account_created')
                .' '.trans('confide::confide.alerts.instructions_sent');

            return Redirect::route('auth.register.done')->with('notice', $notice);
        } else {
            return Redirect::route('auth.register')
                ->withInput(Input::except('password'))
                ->withErrors($user->errors(), 'top');
        }
    }

    /**
     * Show thank message and instruction
     *
     * @return View
     */
    public function showThankYou()
    {
        $content = Session::get(
            'notice',
            'Thanks for registering with us. Please confirm your email.');
        return View::make('home.message', [
            'header' => 'Kiitos',
            'content' => $content
        ]);
    }

    /**
     * Confirm a user registration
     *
     * @param  string $code 
     *
     * @return View
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            return View::make('home.message', [
                'header' => 'Kiitos',
                'content' => trans('confide::confide.alerts.confirmation')
            ]);
        }

        return View::make('home.message', [
            'header' => 'Error',
            'content' => trans('confide::confide.alerts.wrong_confirmation')
        ]);
    }
}
