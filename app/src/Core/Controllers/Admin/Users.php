<?php namespace App\Core\Controllers\Admin;

use App, Config, Request, Redirect, Input, Confide, Session, Auth, Validator;
use Lomake;
use App\Core\Models\DisabledModule;
use App\Core\Models\User;
use App\Core\Models\Business;
use App\Core\Models\Role;
use App\Core\Models\BusinessCategory;
use Carbon\Carbon;

class Users extends Base
{
    use \CRUD;
    protected $viewPath = 'admin.users';

    protected $crudOptions = [
        'modelClass'  => 'App\Core\Models\User',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'user',
        'actionsView' => 'admin.users.actions',
        'indexFields' => ['business_name', 'email', 'types'],
        'presenters'  => [
            'business_name' => ['App\Core\Controllers\Admin\Users', 'presentBusinessName'],
            'types'         => ['App\Core\Controllers\Admin\Users', 'presentTypes'],
        ]
    ];

    /**
     * Overwrite upsert behavior
     *
     * @param View $view
     * @param Eloquent $user
     *
     * @return View
     */
    public function overwrittenUpsert($view, $user)
    {
        // Additional data to be passed to View
        $data = [];
        $businessLomake = null;
        if ($user->is_business) {

            $business = !empty($user->business)
                ? $user->business
                : new Business;
            $data['business'] = $business;

            $businessLomake = Lomake::make($business, [
                'route'             => ['admin.users.business', $user->id],
                'langPrefix'        => 'user.business',
                'fields'            => [
                    'description'   => ['type' => 'html_field', 'default' => $business->description_html],
                    'size'          => ['type' => false],
                    'lat'           => ['type' => false],
                    'lng'           => ['type' => false],
                ],
            ]);

            // Get all business categories
            $categories = BusinessCategory::getAll();
            $data['categories'] = $categories;
            // Selected business categories
            $selectedCategories = $business->businessCategories->lists('id');
            $data['selectedCategories'] = $selectedCategories;
        }

        $data['businessLomake'] = $businessLomake;
        $view->with($data);
        return $view;
    }

    /**
     * @{@inheritdoc}
     */
    public function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->updateUniques();
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function doEdit($type, $id = null)
    {
        // Too bad now $type has the model ID
        $id = $type;
        $type = 'users';

        try {
            $item = $this->model->where('id', $id)->firstOrFail();

            $input = Input::all();
            unset($input['_token']);

            $item->unguard();
            $item->fill($input);
            $item->reguard();

            $item->updateUniques();
        } catch (\Exception $ex) {
            return Redirect::back()
                ->withInput()
                ->withErrors($this->errorMessageBag($ex->getMessage()), 'top');
        }

        return Redirect::route('admin.crud.index', ['model' => $type]);
    }

    /**
     * Login as a user
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function stealSession($id)
    {
        if (Confide::user()->hasRole('Admin') ||
            Session::get('stealthMode') !== null) {
            Auth::loginUsingId($id);

            // Also dump data to session for Service usage
            Auth::user()->dumpToSession();

            // No reset value of `stealthMode` since the first one is the
            // genuine admin
            if (Session::has('stealthMode') === false) {
                Session::set('stealthMode', Confide::user()->id);
            }
        }

        return Redirect::route('home');
    }

    /**
     * Allow to edit associated modules of a user
     *
     * @param int $id User ID
     *
     * @return View
     */
    public function modules($id)
    {
        $user = User::findOrFail($id);
        $modules = Config::get('varaa.premium_modules');

        return $this->render('users.modules', [
            'modules' => $modules,
            'user'    => $user,
        ]);
    }

    /**
     * Enable a service module of a user
     *
     * @param  int $id User ID
     *
     * @return Redirect
     */
    public function enableModule($id)
    {
        $user =  User::findOrFail($id);

        // Find disabled modules
        $modules = array_keys(Config::get('varaa.premium_modules'));
        $selected = Input::get('modules');

        $disabled = array_diff($modules, $selected);

        try {
            foreach ($disabled as $name) {
                $module = new DisabledModule(['module' => $name]);
                $user->disabledModules()->save($module);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            // Silently failed
        }

        return Redirect::back()
            ->with('messages', $this->successMessageBag(
                trans('admin.modules.success_enabled', [
                    'module' => $module->name,
                    'user'   => $user->username
                ])
            ));
    }

    /**
     * Toggle activation of a module
     *
     * @param int $userId
     * @param int $id ID in the pivot table
     *
     * @return Redirect
     */
    public function toggleActivation($userId, $id)
    {

        try {
            $result = Module::toggleActivation($id);
            return Redirect::route('admin.users.modules', ['id' => $userId])
                ->with('messages', $this->successMessageBag(
                    trans('admin.modules.success_activation')
                ));
        } catch (\Exception $ex) {
            $errors = $this->errorMessageBag(trans('common.err.unexpected'));
            return Redirect::back()->withErrors($errors);
        }
    }

    /**
     * Update business information of a user
     *
     * @return Redirect
     */
    public function updateBusiness()
    {
        $errors = null;
        $user = Confide::user();
        $business = !empty($user->business)
            ? $user->business
            : new Business;

        try {
            $business->updateInformation(Input::all(), $user);
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        } catch (\Exception $ex) {
            $errors = $this->errorMessageBag($ex->getMessage());
        }
        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    //--------------------------------------------------------------------------
    // PRESENTERS
    //--------------------------------------------------------------------------
    public static function presentBusinessName($value, $item)
    {
        if (!empty($item->business->name)) {
            return $item->business->name;
        }

        return $value;
    }

    public static function presentTypes($value, $item)
    {
        return implode(', ', array_map(function ($r) {
            return $r->name === 'User' ? 'Business' : $r->name;
        }, iterator_to_array($item->roles)));
    }
}
