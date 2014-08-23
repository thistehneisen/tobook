<?php namespace App\Core\Controllers\Admin;

use App, Config, Request, Redirect, Input, Confide, Session, Auth, Validator;
use App\Core\Models\Module;
use Carbon\Carbon;

class Users extends Crud
{
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
        $user = $this->model->with('modules')->find($id);
        return $this->render('users.modules', [
            'modules' => Module::all(),
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
        $v = Validator::make(Input::all(), [
            'module_id' => 'required',
            'start'     => 'required|date',
            'end'       => 'required|date',
        ]);

        if ($v->fails()) {
            return Redirect::back()->withInput()->withErrors($v, 'top');
        }

        // Insert into database
        $user = $this->model->find($id);
        $module = Module::find(Input::get('module_id'));

        // Check if the selected period is overlapped with existing data
        $overlapped = $module->isOverlapped(
            $user,
            new Carbon(Input::get('start')),
            new Carbon(Input::get('start'))
        );
        if ($overlapped) {
            return Redirect::back()
                ->withInput()
                ->withErrors($this->errorMessageBag(
                    trans('admin.modules.err_overlapped')
                ), 'top');
        }

        $user->modules()->attach($module->id, [
            'start' => Input::get('start'),
            'end'   => Input::get('end'),
        ]);
        return Redirect::back()
            ->with('messages', $this->successMessageBag(
                trans('admin.modules.success_enabled')
            ));
    }
}
