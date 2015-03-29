<?php namespace App\Core\Controllers;

use App\Consumers\Models\Consumer;
use View, Validator, Input, Redirect, Config, Session, App;
use Confide, Lomake;
use App\Core\Models\User;
use App\Core\Models\Role;

class ConsumerAuth extends Auth
{
    protected $viewPath = 'auth.consumer';
    /**
     * This will store the ID of newly created user, in case of failing to
     * attach consumer (see below)
     *
     * @var string
     */
    protected $sessionId = 'consumer.register.userId';

    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        // If there's logged in user, kick him/her out
        if (Confide::user()) {
            Confide::logout();
        }

        // Fields to create registration form
        $fiels = [];
        if (!Session::has($this->sessionId)) {
            $fields['email']    = ['type' => 'Email'];
            $fields['password'] = ['type' => 'Password', 'options' => [
                'id'                => 'register-password',
                'class'             => 'form-control',
            ]];
            $fields['password_confirmation'] = ['type' => 'Password', 'required' => true];
        }
        $fields['first_name']            = ['type' => 'Text', 'required' => true];
        $fields['last_name']             = ['type' => 'Text', 'required' => true];
        $fields['phone']                 = ['type' => 'Text', 'required' => true];

        $lomake = Lomake::make('App\Core\Models\User', [
            'route'      => ['consumer.auth.register'],
            'overwrite'  => true,
            'langPrefix' => 'user',
            'fields'     => $fields
        ]);

        return $this->render('register', [
            'lomake' => $lomake,
        ]);
    }

    /**
     * Do as normal registration process, but assign to role Consume
     *
     * @return Redirect
     */
    public function doRegister()
    {
        $user = null;
        if (Session::has($this->sessionId)) {
            $user = User::find(Session::get($this->sessionId));
        }

        // If we failed to get user from database, it's OK to create a new one
        if ($user === null) {
            $user                        = new User();
            $user->email                 = Input::get('email');
            $user->password              = Input::get('password');
            $user->password_confirmation = Input::get('password_confirmation');

            if (!$user->save()) {
                return Redirect::route('consumer.auth.register')
                    ->withInput(Input::except('password'))
                    ->withErrors($user->errors(), 'top');
            }

            // Assign the role
            $role = Role::consumer();
            $user->attachRole($role);

            // Confirm me, beast
            Confide::confirm($user->confirmation_code);

            // Login so that we'll have remember token
            App::make('auth')->login($user);
        }

        try {
            // Now we need attach consumer data
            $user->attachConsumer([
                'email'         => $user->email,
                'first_name'    => Input::get('first_name'),
                'last_name'     => Input::get('last_name'),
                'phone'         => Input::get('phone'),
            ]);
        } catch (\Watson\Validating\ValidationException $ex) {
            // Attach the newly created user to session, so that we don't need
            // to register new accout again
            Session::set($this->sessionId, $user->id);

            // Logout
            Confide::logout();

            return Redirect::route('consumer.auth.register')
                ->withInput(Input::except('password'))
                ->withErrors($ex->getErrors(), 'top');
        }

        // If this request is from checkout page, we will skip the confirmation,
        // auto login and redirect user to checkout page
        if ((bool) Input::get('fromCheckout') === true) {
            // Automatically login
            App::make('auth')->login($user);

            // Forget all session data
            Session::forget($this->sessionId);

            // Get me back to the page I was previously, beast
            return Redirect::intended(route('cart.checkout'));
        }

        $notice = trans('confide::confide.alerts.account_created')
            .' '.trans('confide::confide.alerts.instructions_sent');

        return Redirect::route('auth.register.done')->with('notice', $notice);
    }
}
