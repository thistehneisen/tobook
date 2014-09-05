<?php namespace App\Core\Controllers;

use Session, Validator, Input, View, Redirect, Hash, Confide;
use App\Core\Models\User as UserModel;
use App\Core\Models\BusinessCategory;

class User extends Base
{
    protected $rules = [
        'profile' => [
            'password'              => 'required_with:old_password|confirmed',
            'password_confirmation' => 'required_with:password',
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

        // Get all business categories
        $categories = BusinessCategory::root()->with('children')->get();
        $selectedCategories = Confide::user()->businessCategories->lists('id');

        return View::make('user.profile', [
            'user'               => Confide::user(),
            'fields'             => $fields,
            'validator'          => Validator::make(Input::all(), $this->rules['profile']),
            'categories'         => $categories,
            'selectedCategories' => $selectedCategories
        ]);
    }

    /**
     * Change user profile
     *
     * @return Redirect
     */
    public function updateProfile()
    {
        $v = Validator::make(Input::all(), $this->rules['profile']);
        if ($v->fails()) {
            return Redirect::back()->withErrors($v);
        }

        try {
            $this->changePassword();
            $this->changeInformation();

            return Redirect::route('user.profile')
                ->with('messages', $this->successMessageBag(
                    trans('user.change_profile_success')
                ));
        } catch (\Exception $ex) {

        }

        return Redirect::back()
            ->withErrors($this->errorMessageBag(
                trans('user.change_profile_failed')
            ), 'top');
    }

    /**
     * Update general information of a user
     *
     * @return void
     */
    protected function changeInformation()
    {
        $user = Confide::user();
        $user->fill([
            'description'   => e(Input::get('description')),
            'business_size' => e(Input::get('business_size'))
        ]);

        // Update business categories
        $user->updateBusinessCategories(Input::get('categories'));
        $user->save();
    }

    /**
     * Check old password by using both legacy from old system and Confide
     *
     * @return void
     */
    protected function changePassword()
    {
        // Nothing to do here
        foreach (['old_password', 'password', 'password_confirmation'] as $f) {
            $field = Input::get($f);
            if (empty($field)) {
                return;
            }
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

            return;
        };

        throw new \Exception(trans('user.incorrect_old_password'));
    }
}
