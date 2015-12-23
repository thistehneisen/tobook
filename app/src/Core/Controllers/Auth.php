<?php namespace App\Core\Controllers;

use App;
use App\Core\Models\Business;
use App\Core\Models\BusinessCategory;
use App\Core\Models\Role;
use App\Core\Models\User;
use Cart;
use Confide;
use Config;
use Input;
use Redirect;
use Session;
use Validator;
use View;

class Auth extends Base
{
    /**
     * Contain rules for login and register
     *
     * @var array
     */
    protected $rules = [
        'login' => [
            'username' => 'required',
            'password' => 'required'
        ],
        'register' => [
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
            'email'                 => 'required|email',
            'name'                  => 'required',
            'address'               => 'required',
            'phone'                 => 'required',
        ],
        'forgot' => [
            'email' => 'required|email'
        ],
        'reset' => [
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
        ],
    ];

    /**
     * Display the form to login
     *
     * @return View
     */
    public function login()
    {
        if (Confide::user()) {
            if (isset($_SESSION['owner_id'])) {
                // if already logged in and there is still native session, return to home
                return Redirect::route('home');
            } else {
                // otherwise, logout
                $this->logout();
            }
        }

        $fields = [
            'username' => ['label' => trans('user.username')],
            'password' => ['label' => trans('user.password'), 'type' => 'password'],
        ];

        return View::make('auth.login', [
            'fields' => $fields,
            'validator' => Validator::make(Input::all(), $this->rules['login'])
        ]);
    }

    public function appLogin()
    {
        if (Confide::user()) {
            if (isset($_SESSION['owner_id'])) {
                // if already logged in and there is still native session, return to home
                return Redirect::route('app.lc.index');
            } else {
                // otherwise, logout
                $this->appLogout();
            }
        }

        $fields = [
            'username' => ['label' => trans('user.username')],
            'password' => ['label' => trans('user.password'), 'type' => 'password'],
        ];

        return View::make('modules.lc.app.login', [
            'fields' => $fields,
            'validator' => Validator::make(Input::all(), $this->rules['login'])
        ]);
    }

    /**
     * Handle login
     *
     * @return Redirector
     */
    public function doLogin()
    {
        $input = [
            'username' => Input::get('username'),
            'email'    => Input::get('username'),
            'password' => Input::get('password'),
        ];

        $v = Validator::make($input, $this->rules['login']);
        if ($v->fails()) {
            return Redirect::back()
                ->withInput(Input::except('password'))
                ->withErrors($v);
        }

        $user = User::oldLogin($input['username'], $input['password']);
        if ($user || Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            // Login successfully
            // Dump current user to session for other modules
            Confide::user()->dumpToSession();

            // Go to Dashboard, yahoo!
            if (Confide::user()->is_business) {
                return Redirect::intended(route('dashboard.index'));
            } elseif (Confide::user()->is_consumer) {
                return Redirect::intended(route('home'));
            }
        }

        // Failed, now get the reason
        $user = new User();
        if (Confide::isThrottled($input)) {
            $errMsg = trans('confide::confide.alerts.too_many_attempts');
        } elseif ($user->checkUserExists($input) and !$user->isConfirmed($input)) {
            $errMsg = trans('confide::confide.alerts.not_confirmed');
        } else {
            $errMsg = trans('confide::confide.alerts.wrong_credentials');
        }

        return Redirect::route('auth.login')
            ->withInput(Input::except('password'))
            ->withErrors($this->errorMessageBag($errMsg), 'top');
    }

    public function doAppLogin()
    {
        $input = [
            'username' => Input::get('username'),
            'email'    => Input::get('username'),
            'password' => Input::get('password'),
        ];

        $v = Validator::make($input, $this->rules['login']);
        if ($v->fails()) {
            return Redirect::back()
                ->withInput(Input::except('password'))
                ->withErrors($v);
        }

        $user = User::oldLogin($input['username'], $input['password']);
        if ($user || Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            // Login successfully
            // Dump current user to session for other modules
            Confide::user()->dumpToSession();

            // Go to Dashboard, yahoo!
            return Redirect::intended(route('app.lc.index'));
        }

        // Failed, now get the reason
        $user = new User();
        if (Confide::isThrottled($input)) {
            $errMsg = trans('confide::confide.alerts.too_many_attempts');
        } elseif ($user->checkUserExists($input) and !$user->isConfirmed($input)) {
            $errMsg = trans('confide::confide.alerts.not_confirmed');
        } else {
            $errMsg = trans('confide::confide.alerts.wrong_credentials');
        }

        return Redirect::route('app.lc.login')
            ->withInput(Input::except('password'))
            ->withErrors($this->errorMessageBag($errMsg), 'top');
    }

    /**
     * Display the registration form
     *
     * @return \View
     */
    public function register()
    {
        $typeform = [
            'prod'     => 'https://varaa.typeform.com/to/q6wEUx',
            'tobook'   => 'https://varaa.typeform.com/to/VbW85p',
            'tobook.ru'=> 'https://varaa.typeform.com/to/yiscWX',
        ];

        $env = App::environment();
        $key = $env;

        if (App::getLocale() === 'ru') {
            $key .= '.ru';
        }

        $typeformUrl = array_get($typeform, $key, $typeform[$env]);

        return View::make('auth.register', [
            'iframe' => $typeformUrl,
        ]);
    }

    /**
     * When a guest submits the form to register
     *
     * @return \Redirect
     */
    public function doRegister()
    {
        $v = Validator::make(Input::all(), $this->rules['register']);
        if ($v->fails()) {
            return Redirect::back()
                ->withInput(Input::except(['password', 'password_confirmation']))
                ->withErrors($v);
        }

        $user = new User();
        $user->email                 = Input::get('email');
        $user->password              = Input::get('password');
        $user->password_confirmation = Input::get('password_confirmation');
        $user->save();

        if ($user->getKey()) {
            // Assign the role
            $role = Role::user();
            $user->attachRole($role);

            $business = new Business([
                'name'        => Input::get('name'),
                'address'     => Input::get('address'),
                'phone'       => Input::get('phone')
            ]);
            $business->user()->associate($user);

            // ignore business save error
            $business->save();

            $notice = trans('confide::confide.alerts.account_created')
                .' '.trans('confide::confide.alerts.instructions_sent');

            return Redirect::route('auth.register.done')->with('notice', $notice);
        }

        return Redirect::route('auth.register')
            ->withInput(Input::except('password'))
            ->withErrors($user->errors(), 'top');
    }

    /**
     * Show thank message and instruction
     *
     * @return View
     */
    public function showThankYou()
    {
        $content = Session::get(
            'notice',
            trans('auth.emails.confirm.title') . trans('auth.emails.confirm.subject')
        );

        return View::make('front.message', [
            'header' => trans('common.thank_you'),
            'content' => $content
        ]);
    }

    /**
     * Confirm a user registration
     *
     * @param string $code
     *
     * @return View
     */
    public function confirm($code)
    {
        if (Confide::confirm($code)) {
            return View::make('front.message', [
                'header' => trans('common.thank_you'),
                'content' => trans('confide::confide.alerts.confirmation')
            ]);
        }

        return View::make('front.message', [
            'header' => trans('common.errors'),
            'content' => trans('confide::confide.alerts.wrong_confirmation')
        ]);
    }

    /**
     * (Obviously) kick a user out
     *
     * @return Redirect
     */
    public function logout()
    {
        // Destroy native session
        @session_start();
        @session_unset();

        Confide::logout();

        // Remove all session data, including stealthMode
        $cart = Session::get(Cart::SESSION_NAME);
        Session::flush();
        Session::set(Cart::SESSION_NAME, $cart);

        return Redirect::route('home');
    }

    public function appLogout()
    {
        // Destroy native session
        @session_start();
        @session_unset();

        Confide::logout();

        // Remove all session data, including stealthMode
        Session::flush();

        return Redirect::route('app.lc.index');
    }

    /**
     * Show the form to reset password
     *
     * @return View
     */
    public function forgot()
    {
        $fields = [
            'email' => ['label' => trans('user.email'), 'type' => 'email'],
        ];

        return View::make('auth.forgot', [
            'fields' => $fields,
            'validator' => Validator::make(Input::all(), $this->rules['forgot'])
        ]);
    }

    /**
     * Send link to reset password to user
     *
     * @return Redirect
     */
    public function doForgot()
    {
        $v = Validator::make(Input::all(), $this->rules['forgot']);
        if ($v->fails()) {
            return Redirect::back()
                ->withErrors($v);
        }

        if (Confide::forgotPassword(Input::get('email'))) {
            $header = trans('common.notice');
            $content = trans('confide::confide.alerts.password_forgot');

            return View::make('front.message', [
                'header' => $header,
                'content' => $content
            ]);
        }

        $content = trans('confide::confide.alerts.wrong_password_forgot');

        return Redirect::route('auth.forgot')
            ->withInput()
            ->withErrors($this->errorMessageBag($content), 'top');
    }

    public function reset($token)
    {
        $fields = [
            'password'              => ['label' => trans('user.password'), 'type' => 'password'],
            'password_confirmation' => ['label' => trans('user.password_confirmation'), 'type' => 'password'],
        ];

        return View::make('auth.reset', [
            'fields' => $fields,
            'token' => $token,
            'validator' => Validator::make(Input::all(), $this->rules['reset'])
        ]);
    }

    public function doReset($token)
    {
        $input = [
            'token'                 => $token,

            // do NOT escape passwords!
            'password'              => Input::get('password'),
            'password_confirmation' => Input::get('password_confirmation'),
        ];

        // By passing an array with the token, password and confirmation
        if (Confide::resetPassword($input)) {
            $header = trans('common.notice');
            $content = trans('confide::confide.alerts.password_reset');

            return View::make('front.message', [
                'header' => $header,
                'content' => $content
            ]);
        }

        $content = trans('confide::confide.alerts.wrong_password_reset');

        return Redirect::route('auth.reset', ['token' => $token])
            ->withInput()
            ->withErrors($this->errorMessageBag($content), 'top');
    }
}
