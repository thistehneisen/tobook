<?php namespace App\Core\Controllers\Admin;

use Input, Redirect, Confide, Config, Mail;
use App\Core\Models\User;
use App\Core\Models\Role;

class Admin extends Base
{
    protected $viewPath = 'admin.admin';

    /**
     * Show the form to create new admin
     *
     * @return View
     */
    public function create()
    {
        return $this->render('create');
    }

    /**
     * Save new admin to database
     *
     * @return RedirectResponse
     */
    public function doCreate()
    {
        $response = Redirect::route('admin.create');
        try {
            // Turn off email confirmation
            Config::set('confide::signup_email', false);

            $user = new User();
            $user->email                 = Input::get('email');
            $user->password              = Input::get('password');
            $user->password_confirmation = Input::get('password');
            $result = $user->save();
            // Automatically confirm
            Confide::confirm($user->confirmation_code);

            if (!$result) {
                return $response->withErrors($user->errors());
            }

            $user->attachRole(Role::admin());

            // Send email with plain password
            Mail::send(
                'admin.users.emails.created',
                ['password' => Input::get('password')],
                function ($message) {
                    $message
                    ->to(Input::get('email'))
                    ->subject(trans('user.password_reminder.created.heading'));
                }
            );

            return $response->with('messages', $this->successMessageBag(trans('common.success')));
        } catch (\Exception $ex) {
            return $response->withErrors($this->errorMessageBag($ex->getMessage()), 'top');
        }
    }
}
