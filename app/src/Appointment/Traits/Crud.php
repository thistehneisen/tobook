<?php namespace App\Appointment\Traits;

use App, Input, Config, Redirect, Route, View, Validator, Request, Response, DB;

trait Crud
{
    public static $crudRoutes = [];

    protected $model;
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

    public function getIndexFields()
    {
        return !empty($this->crudIndexFields) ? $this->crudIndexFields : null;
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
        if (isset($this->modelClass) && $this->modelClass !== null) {
            return $this->modelClass;
        }

        // Because we have namespace like App\<module>\Controllers
        $namespace = substr(__NAMESPACE__, 0, strrpos(__NAMESPACE__, '\\')).'\Models\\';

        return $namespace.str_singular(class_basename(__CLASS__));
    }

    /**
     * Get language prefix for this controller
     *
     * @return string
     */
    public function getLangPrefix()
    {
        return (isset($this->langPrefix) && $this->langPrefix !== null)
            ? $this->langPrefix
            : '';
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
            'delete'   => ['get', 'delete/{id}'],
            'search'   => ['get', 'search'],
            'bulk'     => ['post', 'bulk'],
            'order'    => ['post', 'order'],
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
     * Show all items of the current user and a form to add new one
     *
     * @return View
     */
    public function index()
    {
        $query = $this->getModel()->ofCurrentUser();
        $query = $this->applyQueryStringFilter($query);

        // If this controller is sortable
        if (isset($this->crudSortable) && $this->crudSortable === true) {
            $query = $query->orderBy('order');
        }

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        return $this->renderList($items);
    }

    /**
     * Pass value of fillable fields to filter list of items
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function applyQueryStringFilter($query)
    {
        $fillable = $this->getModel()->fillable;

        // If we have filter value in query string
        foreach (Request::instance()->query->all() as $key => $value) {
            if (in_array($key, $fillable)) {
                $query = $query->where(e($key), e($value));
            }
        }

        return $query;
    }

    /**
     * Render the list of items
     *
     * @param Illuminate\Support\Collection $items
     *
     * @return View
     */
    protected function renderList($items)
    {
        // Fields that should be shown in the list
        // First we will check if target controller has crudIndexFields property
        // If not, we will use all fillable fields of the model
        $fillable = $this->getModel()->fillable;
        $fields = $this->getIndexFields() ?: $fillable;

        // User can overwrite default CRUD list template
        $view = View::exists($this->getViewPath().'.index')
            ? $this->getViewPath().'.index'
            : 'modules.as.crud.index';

        return View::make($view, [
            'items'       => $items,
            'routes'      => static::$crudRoutes,
            'langPrefix'  => $this->getLangPrefix(),
            'fields'      => $fields,
            'bulkActions' => $this->getBulkActions(),
            'sortable'    => isset($this->crudSortable) ? $this->crudSortable : false
        ]);
    }

    /**
     * Return an array of methods that allow bulk actions
     *
     * @return array
     */
    protected function getBulkActions()
    {
        return ['destroy'];
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
            : new $model();

        $view = View::exists($this->getViewPath().'.form')
            ? $this->getViewPath().'.form'
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
                : new $model();

            $item = $this->upsertHandler($item);

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
     * @param  Illuminate\Database\Eloquent\Model    $item
     * @throws Watson\Validating\ValidatingException If failed to validate data
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->saveOrFail();

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

    /**
     * Search the given keyword in all fillable fields
     *
     * @return View
     */
    public function search()
    {
        // Escape HTML
        $q = e(Input::get('q'));

        $query = $this->getModel();
        // Apply query string filters
        $query = $this->applyQueryStringFilter($query);

        $fillable = $this->getModel()->fillable;
        $query = $query->where(function ($subQuery) use ($fillable, $q) {
            foreach ($fillable as $field) {
                $subQuery = $subQuery->orWhere($field, 'LIKE', '%'.$q.'%');
            }

            return $subQuery;
        });

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        return $this->renderList($items);
    }

    /**
     * Accept bulk action
     *
     * @return Redirect
     */
    public function bulk()
    {
        $v = Validator::make(Input::all(), [
            'ids' => 'required|array'
        ]);
        if ($v->fails()) {
            if (Request::ajax()) {
                return Response::json($v->errors(), 500);
            }

            return Redirect::back()->withErrors($v);
        }

        $action = Input::get('action');
        if (!method_exists($this, $action)) {
            throw new \InvalidArgumentException('Method is not allowed');
        }

        call_user_func([$this, $action], Input::get('ids'));

        if (Request::ajax()) {
            return Response::json(['message' => trans('as.crud.success_bulk')]);
        }

        return Redirect::route(static::$crudRoutes['index'])
            ->with('messages', $this->successMessageBag(
                trans('as.crud.success_bulk')
            ));
    }

    /**
     * Update orders of items in database
     *
     * @return void
     */
    public function order()
    {
        $orders = Input::get('orders');
        foreach ($orders as $order => $id) {
            DB::table($this->getModel()->getTable())
                ->where('id', $id)
                ->update(['order' => $order]);
        }
    }

    /**
     * Remove all items with those IDs
     *
     * @param array $ids
     *
     * @return bool
     */
    public function destroy($ids)
    {
        return $this->getModel()->whereIn('id', $ids)->delete();
    }
}
