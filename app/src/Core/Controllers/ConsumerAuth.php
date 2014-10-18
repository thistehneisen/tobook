<?php namespace App\Core\Controllers;

use View, Validator, Input, Redirect, Config, Session;
use Confide, Lomake;
use App\Core\Models\User;
use App\Core\Models\Role;

class ConsumerAuth extends Auth
{
    protected $viewPath = 'auth.consumer';

    /**
     * @{@inheritdoc}
     */
    public function register()
    {
        $lomake = Lomake::make('App\Core\Models\User', [
            'route'      => ['consumer.auth.register'],
            'overwrite'  => true,
            'langPrefix' => 'user',
            'fields'     => [
                'username'              => ['type' => 'Text'],
                'email'                 => ['type' => 'Email'],
                'password'              => ['type' => 'Password'],
                'password_confirmation' => ['type' => 'Password'],
            ]
        ]);

        return $this->render('register', [
            'lomake' => $lomake
        ]);
    }

    /**
     * Do as normal registration process, but assign to role Consume
     *
     * @return Redirect
     */
    public function doRegister()
    {
        $user                        = new User();
        $user->username              = e(Input::get('username'));
        $user->email                 = e(Input::get('email'));
        $user->password              = e(Input::get('password'));
        $user->password_confirmation = e(Input::get('password_confirmation'));

        // Now we need to check existing consumer
        $user->attachConsumer();

        if (!$user->save()) {
            return Redirect::route('consumer.auth.register')
                ->withInput(Input::except('password'))
                ->withErrors($user->errors());
        }

        // Assign the role
        $role = Role::consumer();
        $user->attachRole($role);

        // If this request is from checkout page, we will skip the confirmation,
        // auto login and redirect user to checkout page
        if ((bool) Input::get('fromCheckout') === true) {
            // Confirm me, beast
            Confide::confirm($user->getKey());

            // Log me in, beast
            Confide::logAttempt([
                'username' => e(Input::get('username')),
                'email'    => e(Input::get('email')),
                'password' => e(Input::get('password')),
            ]);

            // Get me back to the page I was previously, beast
            return Redirect::intended(route('cart.checkout'));
        }

        $notice = trans('confide::confide.alerts.account_created')
            .' '.trans('confide::confide.alerts.instructions_sent');

        return Redirect::route('auth.register.done')->with('notice', $notice);
    }
}
