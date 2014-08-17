<?php namespace App\Controllers\Admin;

use App, Config, Request, Redirect, Input;

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
            'items' => $items,
        ]);
    }

    /**
     * Show the form to edit an item
     *
     * @param string $type
     * @param int    $id
     *
     * @return View
     */
    public function edit($type, $id)
    {
        try {
            $item = $this->model->where('id', $id)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            $message = 'Cannot find data with ID #'.$id;

            return Redirect::route('admin.crud.index', ['model' => $type])
                ->withErrors($this->errorMessageBag($message), 'top');
        }

        return $this->render('crud.edit', [
            'model' => $this->model,
            'item' => $item
        ]);
    }

    /**
     * Update the record in database
     *
     * @param string $type
     * @param int    $id
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
                ->withErrors($this->errorMessageBag($ex->getMessage()), 'top');
        }

        return Redirect::route('admin.crud.index', ['model' => $type]);
    }

    /**
     * Delete a record in database
     *
     * @param string $type
     * @param int    $id
     *
     * @return Redirect
     */
    public function delete($type, $id)
    {
        $item = $this->model->find($id);
        if ($item) {
            $item->delete();
        }

        return Redirect::route('admin.crud.index', ['model' => $type])
            ->with(
                'messages',
                $this->successMessageBag('ID #'.$id.' was deleted')
            );
    }

    /**
     * Search items in database
     *
     * @return View
     */
    public function search()
    {
        $q = Input::get('q');
        $query = $this->model;
        foreach ($this->model->visible as $field) {
            $query = $query->orWhere($field, 'LIKE', '%'.$q.'%');
        }

        $items = $query->paginate(Config::get('view.perPage'));

        return $this->render('crud.search', [
            'model' => $this->model,
            'items' => $items,
        ]);
    }
}
