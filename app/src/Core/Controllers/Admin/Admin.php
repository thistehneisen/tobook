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
        try {
            // Turn off email confirmation
            Config::set('confide::signup_email', false);

            $user = new User();
            $user->email                 = Input::get('email');
            $user->password              = Input::get('password');
            $user->password_confirmation = Input::get('password');
            $user->save();
            $user->attachRole(Role::admin());

            // Automatically confirm
            Confide::confirm($user->confirmation_code);

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

            return Redirect::route('admin.create')
                ->with('messages', $this->successMessageBag(
                    trans('common.success')
                ));
        } catch (\Exception $ex) {
            return Redirect::route('admin.create')
                ->withErrors($this->errorMessageBag($ex->getMessage()), 'top');
        }
    }
}
