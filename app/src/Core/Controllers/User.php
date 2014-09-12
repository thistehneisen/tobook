<?php namespace App\Core\Controllers;

use Session, Validator, Input, View, Redirect, Hash, Confide;
use App\Core\Models\User as UserModel;
use App\Core\Models\BusinessCategory;
use App\Core\Models\Image;

class User extends Base
{
    protected $rules = [
        'profile' => [
            'business_name' => 'required',
        ],
        'password' => [
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

        // Get all images of this user
        $images = Confide::user()->images()
            ->businessImages()
            // This should be enabled later
            // ->ofCurrentUser()
            ->get();

        return View::make('user.profile', [
            'user'               => Confide::user(),
            'fields'             => $fields,
            'validator'          => Validator::make(Input::all(), $this->rules['profile']),
            'categories'         => $categories,
            'selectedCategories' => $selectedCategories,
            'images'             => $images,
            'activeTab'          => Session::get('tab', 'general'),
            'formData'           => [
                'image_type' => Image::BUSINESS_IMAGE
            ]
        ]);
    }

    /**
     * Change user profile
     *
     * @return Redirect
     */
    public function updateProfile()
    {
        $tab = Input::get('tab');
        if ($tab === null || !method_exists($this, 'update'.studly_case($tab))) {
            return \App::abort(404);
        }

        try {
            $method = 'update'.studly_case($tab);
            $result = $this->$method();

            // If we need to redirect immediately
            if ($result instanceof \Illuminate\Http\RedirectResponse) {
                return $result;
            }

            return Redirect::route('user.profile')
                // Pass the current tab to correctly activate
                ->with('tab', $tab)
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
    protected function updateGeneral()
    {
        $v = Validator::make(Input::all(), $this->rules['profile']);
        if ($v->fails()) {
            return Redirect::back()->withInput()->withErrors($v);
        }

        $user = Confide::user();
        $user->fill([
            'description'   => e(Input::get('description')),
            'business_size' => e(Input::get('business_size')),
            'business_name' => e(Input::get('business_name'))
        ]);

        // Update business categories
        if (Input::has('categories')) {
            $user->updateBusinessCategories(Input::get('categories'));
        }
        $user->save();
    }

    /**
     * Check old password by using both legacy from old system and Confide
     *
     * @return void
     */
    protected function updatePassword()
    {
        // Nothing to do here
        foreach (['old_password', 'password', 'password_confirmation'] as $f) {
            $field = Input::get($f);
            if (empty($field)) {
                return;
            }
        }

        $v = Validator::make(Input::all(), $this->rules['password']);
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

            // Done, nothing to do more
            return;
        };

        throw new \Exception(trans('user.incorrect_old_password'));
    }
}
