<?php namespace App\Core\Controllers;

use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\Image;
use App\Core\Models\User as UserModel;
use Confide;
use Hash;
use Input;
use Log;
use Lomake;
use Redirect;
use Session;
use Validator;
use View;

class User extends Base
{
    protected $rules = [
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

        $businessLomake = null;
        if ($user->is_business) {
            $isAdmin = $user->is_admin || Session::has('stealthMode');
            $businessLomake = Lomake::make($business, [
                'route'             => 'user.profile',
                'langPrefix'        => 'user.business',
                'fields'            => [
                    'description'      => ['type' => 'html_multilang', 'default' => $business->description_html],
                    'lat'              => ['type' => false],
                    'lng'              => ['type' => false],
                    'size'             => ['type' => false],
                    'note'             => ['type' => false],
                    'payment_options'  => ['type' => false],
                    'deposit_rate'     => ['type' => false],
                    'bank_account'     => ['type' => 'text'          , 'hidden' => !$isAdmin],
                    'meta_title'       => ['type' => 'text_multilang', 'hidden' => !$isAdmin],
                    'meta_description' => ['type' => 'text_multilang', 'hidden' => !$isAdmin],
                    'meta_keywords'    => ['type' => 'text_multilang', 'hidden' => !$isAdmin],
                    'is_hidden'        => ['type' => 'radio'         , 'hidden' => !$isAdmin],
                ],
            ]);
        }

        $consumer = $user->is_consumer
            ? $user->consumer
            : null;

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
            'email'       => Input::get('email'),
            'business_id' => Input::get('business_id'),
            'account'     => Input::get('account'),
            'first_name'  => Input::get('first_name'),
            'last_name'   => Input::get('last_name'),
            'phone'       => Input::get('phone'),
            'address'     => Input::get('address'),
            'city'        => Input::get('city'),
            'postcode'    => Input::get('postcode'),
            'country'     => Input::get('country'),
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

        if ($user->is_business) {
            $business = $user->business;
            $business->payment_options = Input::get('payment_options', []);
            $business->deposit_rate = Input::get('deposit_rate');

            $business->save();
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
        $user = Confide::user();
        $business = $user->business !== null ? $user->business : new Business();
        $errors = null;

        try {
            $business->updateInformation(Input::all(), $user);
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        } catch (\Exception $ex) {
            // Silently fail
            Log::error($ex->getMessage());
            $errors = $this->errorMessageBag(trans('common.err.unexpected'));
        }

        $redirect = Redirect::back()->with('tab', 'business');
        //To prevent payment methods are reset
        //@see https://github.com/varaa/varaa/issues/593
        if(!empty($errors)) {
            $redirect->withInput()->withErrors($errors);
        }

        return $redirect;
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

    /**
     * Update working hours of a business
     *
     * @return void
     */
    protected function updateWorkingHours()
    {
        // Get business of this user
        $business = Confide::user()->business;
        $business->working_hours = Input::get('working_hours');
        $business->saveOrFail();
    }
}
