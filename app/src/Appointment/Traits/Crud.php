<?php namespace App\Appointment\Traits;

use App, Input, Config, Redirect, Route, View;

trait Crud
{
    protected $model;

    public static $crudRoutes = [];

    /**
     * Return a model to interact with database
     *
     * @return Eloquent
     */
    public function getModel()
    {
        if ($this->model === null) {
            $this->model = App::make($this->getModelClass());
        }

        return $this->model;
    }

    /**
     * Get language prefix for this controller
     *
     * @return string
     */
    public function getLangPrefix()
    {
        return '';
    }

    /**
     * Generate CRUD routes
     *
     * @param string $uri
     * @param string $name
     *
     * @return void
     */
    public static function crudRoutes($uri, $name)
    {
        $routes = [
            'index'    => ['get', ''],
            'upsert'   => ['get', 'upsert/{id?}'],
            'doUpsert' => ['post', 'upsert/{id?}'],
            'delete'   => ['get', 'delete'],
        ];

        foreach ($routes as $method => $params) {
            list($httpMethod, $nameSuffix) = $params;

            $def = [];
            $def['as'] = $name.'.'.$method;
            $def['uses'] = __CLASS__.'@'.$method;
            static::$crudRoutes[$method] = $def['as'];

            Route::$httpMethod($uri.'/'.$nameSuffix, $def);
        }
    }

    /**
     * Guess the model name based on controller name
     * If you're building a controller that doesn't have a model with the same
     * name, for example, ReportController using ConsumerModel, redefine this
     * method.
     *
     * @return string
     */
    protected function getModelClass()
    {
        // Because we have namespace like App\Appointments\Controllers
        $namespace = substr(__NAMESPACE__, 0, strrpos(__NAMESPACE__, '\\')).'\Models\\';
        return $namespace.str_singular(class_basename(__CLASS__));
    }

    /**
     * Show all items of the current user and a form to add new one
     *
     * @return View
     */
    public function index()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $this->getModel()
            ->ofCurrentUser()
            ->paginate($perPage);

        // User can overwrite default CRUD template
        $view = View::exists($this->getViewPath().'index')
            ? $this->getViewPath().'index'
            : 'modules.as.crud.index';

        // Get fields to generate tables
        $fields = $items->first()->fillable;

        return View::make($view, [
            'items'      => $items,
            'routes'     => static::$crudRoutes,
            'langPrefix' => $this->getLangPrefix(),
            'fields'     => $fields
        ]);
    }

    /**
     * Show the form to insert or update a record
     *
     * @param int $id Optional. Item's ID
     *
     * @return View
     */
    public function upsert($id = null)
    {
        $model = $this->getModel();
        $item = ($id !== null)
            ? $model->findOrFail($id)
            : new $model;

        $view = View::exists($this->getViewPath().'form')
            ? $this->getViewPath().'form'
            : 'modules.as.crud.form';

        return View::make($view, [
            'item'       => $item,
            'routes'     => static::$crudRoutes,
            'langPrefix' => $this->getLangPrefix(),
        ]);
    }

    /**
     * Handle upsert
     *
     * @param int $id Optional. Item's ID
     *
     * @return Redirect
     */
    public function doUpsert($id = null)
    {
        $model = $this->getModel();
        try {
            $item = ($id !== null)
                ? $model->findOrFail($id)
                : new $model;

            $item = $this->upsertHandler($item);
            $item->saveOrFail();

            $message = ($id !== null)
                ? trans('as.crud.success_edit')
                : trans('as.crud.success_add');

            return Redirect::route(static::$crudRoutes['index'])
                ->with('messages', $this->successMessageBag($message));
        } catch (\Watson\Validating\ValidationException $ex) {
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

        $errors = $this->errorMessageBag(trans('common.err.unexpected'));
        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    /**
     * Take all input and update model attributes
     * Developers might want to rewrite this method to have desired behaviors
     *
     * @param Illuminate\Database\Eloquent\Model $item
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        return $item;
    }

    /**
     * Delete a category
     *
     * @param int $id
     *
     * @return Redirect
     */
    public function delete($id)
    {
        $item = $this->getModel()->findOrFail($id);
        $item->delete();

        return Redirect::route(static::$crudRoutes['index'])
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }
}
