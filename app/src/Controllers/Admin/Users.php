<?php namespace App\Controllers\Admin;

use App, Config, Request, Redirect, Input, Confide, Session, Auth;

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
     * @param  int $id 
     *
     * @return Redirect
     */
    public function stealSession($id)
    {
        if (Confide::user()->hasRole('Admin')) {
            Session::set('stealthMode', Confide::user()->id);
            Auth::loginUsingId($id);

            // Also dump data to session for Service usage
            Auth::user()->dumpToSession();
        }

        return Redirect::route('home');
    }
}
