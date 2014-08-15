<?php namespace App\Controllers;

use Session, Validator, Input, View, Redirect, Hash, Confide;
use User as UserModel;

class User extends Base
{
    protected $rules = [
        'profile' => [
            'old_password'          => 'required',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
        ]
    ];

    /**
     * Display the form to change user profile
     *
     * @return View
     */
    public function profile()
    {
        $fields = [
            'old_password'          => ['label' => trans('user.old_password'), 'type' => 'password'],
            'password'              => ['label' => trans('user.password'), 'type' => 'password'],
            'password_confirmation' => ['label' => trans('user.password_confirmation'), 'type' => 'password'],
        ];

        return View::make('user.profile', [
            'fields' => $fields,
            'validator' => Validator::make(Input::all(), $this->rules['profile'])
        ]);
    }

    /**
     * Change user profile
     *
     * @return Redirect
     */
    public function changeProfile()
    {
        $v = Validator::make(Input::all(), $this->rules['profile']);
        if ($v->fails()) {
            return Redirect::back()->withErrors($v);
        }

        $user = UserModel::oldLogin(
            Confide::user()->username,
            Input::get('old_password')
        );
        if ($user || Hash::check(Input::get('old_password'), Confide::user()->password)) {
            // Old password OK, let's change
            Confide::user()->resetPassword([
                'password'              => Input::get('password'),
                'password_confirmation' => Input::get('password_confirmation'),
            ]);

            if (Confide::user()->old_password !== null) {
                // Remove old password
                Confide::user()->removeOldPassword();
            }

            return Redirect::back()
                ->with(
                    'messages',
                    $this->successMessageBag(trans('user.change_profile_success'))
                );
        };

        return Redirect::back()
            ->withErrors(
                $this->errorMessageBag(trans('user.change_profile_failed')),
                'top'
            );
    }
}
