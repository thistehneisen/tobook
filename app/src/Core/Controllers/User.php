<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use App\Lomake\Fields\HtmlField;
use Lomake;
use Session, Validator, Input, View, Redirect, Hash, Confide;
use App\Core\Models\User as UserModel;
use App\Core\Models\BusinessCategory;
use App\Core\Models\Image;

class User extends Base
{
    protected $rules = [
        'business' => [
            'name' => 'required',
            'size' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postcode' => 'required',
            'country' => 'required',
            'phone' => 'required',
        ],
        'profile' => [],
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

        $user = Confide::user();

        $business = $user->business;
        $categories = BusinessCategory::getAll();
        if (!empty($business)) {
            // Get all business categories
            $selectedCategories = $business->businessCategories->lists('id');
        } else {
            $business = new Business();
            $selectedCategories = [];
        }

        if ($user->is_business) {
            $businessLomake = Lomake::make($business, [
                'route'             => 'user.profile',
                'langPrefix'        => 'user.business',
                'fields'            => [
                    'description'   => ['type' => 'html_field', 'default' => $business->description_html],
                    'size'          => ['type' => false],
                    'lat'           => ['type' => false],
                    'lng'           => ['type' => false],
                ],
            ]);
        } else {
            $businessLomake = null;
        }

        if ($user->is_consumer) {
            $consumer = $user->consumer;
        } else {
            $consumer = null;
        }

        // Get all images of this user
        $images = $user->images()
            ->businessImages()
            ->ofCurrentUser()
            ->get();

        return View::make('user.profile', [
            'user'               => $user,
            'business'           => $business,
            'businessLomake'     => $businessLomake,
            'consumer'           => $consumer,
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
     * @return Redirect
     */
    protected function updateGeneral()
    {
        $v = Validator::make(Input::all(), $this->rules['profile']);
        if ($v->fails()) {
            return Redirect::back()->withInput()->withErrors($v);
        }

        $data = [
            'email'      => e(Input::get('email')),
            'first_name' => e(Input::get('first_name')),
            'last_name'  => e(Input::get('last_name')),
            'phone'      => e(Input::get('phone')),
            'address'    => e(Input::get('address')),
            'city'       => e(Input::get('city')),
            'postcode'   => e(Input::get('postcode')),
            'country'    => e(Input::get('country')),
        ];


        $errors = null;
        $user = Confide::user();
        // If this user is a consumer
        $consumer = $user->consumer;
        if ($consumer !== null) {
            $consumer->fill($data);
            try {
                $consumer->saveOrFail();
            } catch (\Watson\Validating\ValidationException $ex) {
                $errors = $ex->getErrors();
            }
        }

        // only `email` will go through to User model
        $user->fill($data);
        if (!$user->updateUniques()) {
            $errors = $user->errors();
        }

        if ($errors !== null) {
            return Redirect::back()
                ->with('tab', 'business')
                ->withInput()
                ->withErrors($errors);
        }
    }

    /**
     * Update business related information
     *
     * @return void
     */
    protected function updateBusiness()
    {
        $v = Validator::make(Input::all(), $this->rules['business']);
        if ($v->fails()) {
            return Redirect::back()->withInput()->withErrors($v);
        }

        $user = Confide::user();

        $business = $user->business;
        if (empty($business)) {
            $business = new Business();
        }

        $business->fill([
            'name'          => e(Input::get('name')),
            'description'   => HtmlField::filterInput(Input::all(), 'description'),
            'size'          => e(Input::get('size')),
            'address'       => e(Input::get('address')),
            'city'          => e(Input::get('city')),
            'postcode'      => e(Input::get('postcode')),
            'country'       => e(Input::get('country')),
            'phone'         => e(Input::get('phone')),
        ]);

        $business->user()->associate($user);

        if (!$business->save()) {
            return Redirect::back()->withInput()->withErrors($business->getErrors());
        }

        // Update business categories
        if (Input::has('categories')) {
            $business->updateBusinessCategories(Input::get('categories'));
        }
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
                // do NOT escape passwords!
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
