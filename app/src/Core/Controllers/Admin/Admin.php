<?php namespace App\Core\Controllers\Admin;

use Input, Redirect, Confide;
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
            $user = new User();
            $user->email                 = Input::get('email');
            $user->password              = Input::get('password');
            $user->password_confirmation = Input::get('password');
            $user->save();

            $user->attachRole(Role::admin());
            // Confirm
            Confide::confirm($user->confirmation_code);

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
