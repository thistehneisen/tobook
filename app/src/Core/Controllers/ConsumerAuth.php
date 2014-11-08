<?php namespace App\Core\Controllers;

use App\Consumers\Models\Consumer;
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
        // If there's logged in user, kick him/her out
        if (Confide::user()) {
            Confide::logout();
        }

        $lomake = Lomake::make('App\Core\Models\User', [
            'route'      => ['consumer.auth.register'],
            'overwrite'  => true,
            'langPrefix' => 'user',
            'fields'     => [
                'email'                 => ['type' => 'Email'],
                'password'              => ['type' => 'Password', 'options' => ['id' => 'register-password']],
                'password_confirmation' => ['type' => 'Password'],
                'first_name'            => ['type' => 'Text'],
                'last_name'             => ['type' => 'Text'],
                'phone'                 => ['type' => 'Text'],
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
        $user->email                 = e(Input::get('email'));

        // do NOT escape passwords!
        $user->password              = Input::get('password');
        $user->password_confirmation = Input::get('password_confirmation');

        // Optional inforamtion
        $user->first_name            = e(Input::get('first_name'));
        $user->last_name             = e(Input::get('last_name'));
        $user->phone                 = e(Input::get('phone'));

        try {
            // Now we need to check existing consumer
            $user->attachConsumer();
        } catch (\Watson\Validating\ValidationException $ex) {
            Redirect::route('consumer.auth.register')
                ->withInput(Input::except('password'))
                ->withErrors($ex->getMessageBag());
        }

        if (!$user->save()) {
            return Redirect::route('consumer.auth.register')
                ->withInput(Input::except('password'))
                ->withErrors($user->errors(), 'top');
        }

        // Assign the role
        $role = Role::consumer();
        $user->attachRole($role);

        // If this request is from checkout page, we will skip the confirmation,
        // auto login and redirect user to checkout page
        if ((bool) Input::get('fromCheckout') === true) {
            // Confirm me, beast
            Confide::confirm($user->confirmation_code);

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
