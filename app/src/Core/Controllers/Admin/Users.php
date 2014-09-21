<?php namespace App\Core\Controllers\Admin;

use App, Config, Request, Redirect, Input, Confide, Session, Auth, Validator;
use App\Core\Models\DisabledModule;
use App\Core\Models\User;
use Carbon\Carbon;

class Users extends Base
{
    use \CRUD;
    protected $crudOptions = [
        'modelClass' => 'App\Core\Models\User',
        'layout'     => 'layouts.admin',
        'langPrefix' => 'user',
        'indexFields' => [
            'username',
            'full_name',
            'email',
            'full_address'
        ]
    ];

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
}
