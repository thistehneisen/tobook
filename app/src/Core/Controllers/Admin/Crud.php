<?php namespace App\Core\Controllers\Admin;

use App, Config, Request, Redirect, Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Watson\Validating\ValidationException;
use Exception;

class Crud extends Base
{
    /**
     * Name of current processing model, in plural
     *
     * @var string
     */
    protected $modelName;

    public function __construct()
    {
        $this->modelName = Request::segment(2);
        $this->model     = App::make('App\Core\Models\\'.ucfirst(str_singular($this->modelName)));
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
            'model'     => $this->model,
            'modelName' => $this->modelName,
            'items'     => $items,
        ]);
    }

    /**
     * Show the form to create new item
     *
     * @return View
     */
    public function create()
    {
        return $this->render('crud.edit', [
            'model'      => $this->model,
            'modelName'  => $this->modelName,
            'formAction' => route('admin.crud.create')
        ]);
    }

    /**
     * Handle create new item
     *
     * @return Redirect
     */
    public function doCreate()
    {
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $item = new $this->model;
            $item->fill(Input::all());
            $item->saveOrFail();

            return Redirect::route(
                    'admin.crud.index',
                    ['model' => $this->modelName]
                )->with(
                    'messages',
                    $this->successMessageBag(
                        'New '.str_singular($this->modelName).' created.')
                );
        } catch (ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()
            ->withInput()
            ->withErrors($errors, 'top');
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
        } catch (ModelNotFoundException $ex) {
            $message = 'Cannot find data with ID #'.$id;

            return Redirect::route('admin.crud.index', ['model' => $type])
                ->withErrors($this->errorMessageBag($message), 'top');
        }

        return $this->render('crud.edit', [
            'model'      => $this->model,
            'modelName'  => $this->modelName,
            'item'       => $item,
            'formAction' => route('admin.crud.edit', [
                'model' => $this->modelName,
                'id'    => $item->id
            ])
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
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $item = $this->model->where('id', $id)->firstOrFail();
            $item->fill(Input::all());
            $item->saveOrFail();

            return Redirect::route('admin.crud.index', ['model' => $type]);
        } catch (ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()
            ->withInput()
            ->withErrors($errors, 'top');
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
