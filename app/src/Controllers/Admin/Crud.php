<?php namespace App\Controllers\Admin;

use App, Config, Request, Redirect, Input;
use Illuminate\Support\MessageBag;

class Crud extends Base
{
    public function __construct()
    {
        $modelName = str_singular(Request::segment(2));
        $this->model = App::make(ucfirst($modelName));
    }

    /**
     * List all records in database with pagination
     *
     * @return View
     */
    public function index()
    {
        $items = $this->model->paginate(Config::get('view.perPage'));
        
        return $this->render('crud.index', [
            'model' => $this->model,
            'items' => $items
        ]);
    }

    /**
     * Show the form to edit an item
     *
     * @param  string $type 
     * @param  int $id   
     *
     * @return View
     */
    public function edit($type, $id)
    {
        try {
            $item = $this->model->where('id', $id)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            return Redirect::route('admin.crud.index', ['model' => $type]);
        }
        
        return $this->render('crud.edit', [
            'model' => $this->model,
            'item' => $item
        ]);
    }

    /**
     * Update the record in database
     *
     * @param  string $type 
     * @param  int $id   
     *
     * @return Redirect
     */
    public function doEdit($type, $id)
    {
        try {
            $item = $this->model->where('id', $id)->firstOrFail();

            $input = Input::all();
            unset($input['_token']);
            
            $item->unguard();
            $item->fill($input);
            $item->reguard();

            $item->save();
        } catch (\Exception $ex) {
            return Redirect::back()->withInput()
                ->withErrors($this->errorMessageBag($ex->getMessage()));
        }

        return Redirect::route('admin.crud.index', ['model' => $type]);
    }
}
