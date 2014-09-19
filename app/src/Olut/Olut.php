<?php namespace App\Olut;

use App, Input, Config, Redirect, Route, View, Validator, Request, Response, DB;
use Lomake;

trait Olut
{
    /**
     * Name of all auto-generated routes for CRUD
     *
     * @var array
     */
    public static $crudRoutes = [];

    /**
     * Instance of the model class associated with this controller
     *
     * @var Illuminate\Database\Eloquent\Model
     */
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

    /**
     * Guess the model name based on controller name
     * If you're building a controller that doesn't have a model with the same
     * name, for example, ReportController using ConsumerModel, redefine this
     * method. Or better, just define `modelClass` in your $crudOptions
     *
     * @return string
     */
    protected function getModelClass()
    {
        $modelClass = $this->getOlutOptions('modelClass');
        if ($modelClass !== null) {
            return $modelClass;
        }

        // Because we have namespace like App\<module>\Controllers
        $namespace = substr(__NAMESPACE__, 0, strrpos(__NAMESPACE__, '\\')).'\Models\\';

        return $namespace.str_singular(class_basename(__CLASS__));
    }

    /**
     * Return options defined in $this->crudOptions
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getOlutOptions($key)
    {
        if (isset($this->crudOptions) && isset($this->crudOptions[$key])) {
            return $this->crudOptions[$key];
        }
    }

    /**
     * Automatically generate CRUD routes
     * Usage: Call this method inside routes.php
     * <code>
     *     \App\Consumers\Controllers\Consumer::crudRoutes(
     *         'consumers',
     *         'co.consumers'
     *     );
     * </code>
     *
     * @param string $uri  The URI part will be used for CRUD of this
     * @param string $name The prefix will be used in routes' name
     *
     * @return void
     */
    public static function crudRoutes($uri, $name)
    {
        // The patterns here is
        //  '$handler' => ['$httpMethod', '$route']
        // where
        //  $handler   : Name of method in this trait to handle request
        //  $httpMethod: HTTP method to be accepted
        //  $route     : Route definition
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
            // Define the name of this route. Useful in URL generating
            $def['as'] = $name.'.'.$method;
            // Define the action to handle this method
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
        // To make sure that we only show records of current user
        $query = $this->getModel()->ofCurrentUser();

        // Allow to filter results in query string
        $query = $this->applyQueryStringFilter($query);

        // If this controller is sortable
        if ($this->getOlutOptions('sortable') === true) {
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

        // Get all query string values
        // If present, limit the return results
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
        $fields = $this->getViewableFields();

        // User can overwrite default CRUD list template
        $view = View::exists($this->getViewPath().'.index')
            ? $this->getViewPath().'.index'
            : 'olut::index';

        // Get language prefix
        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        // List of methods that can handle bulk actions
        $bulkActions = $this->getOlutOptions('bulkActions') ?: ['destroy'];

        // Bartender will take care the output of fields
        $bartender = new Bartender($this->getOlutOptions('presenters') ?: []);

        return View::make($view, [
            'items'       => $items,
            'routes'      => static::$crudRoutes,
            'langPrefix'  => $langPrefix,
            'fields'      => $fields,
            'bulkActions' => $bulkActions,
            'sortable'    => $this->getOlutOptions('sortable') ?: false,
            'showTab'     => $this->getOlutOptions('showTab') ?: true,
            'layout'      => $this->getOlutOptions('layout') ?: 'olut::layout',
            'bartender'   => $bartender
        ]);
    }

    /**
     * Return the list of fields that should be shown in list page
     *
     * @return array
     */
    protected function getViewableFields()
    {
        // First we will check if target controller has indexFields property
        // If not, we will use all fillable fields of the model
        return $this->getOlutOptions('indexFields')
            ?: $this->getModel()->fillable;
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
            ? $model->ofCurrentUser()->findOrFail($id)
            : new $model();

        $view = View::exists($this->getViewPath().'.form')
            ? $this->getViewPath().'.form'
            : 'olut::form';

        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        $options = $this->getOlutOptions('lomake') ?: [];
        $lomake = Lomake::make($item, [
            'route'      => [static::$crudRoutes['upsert'], isset($item) ? $item->id : null],
            'fields'     => $options,
            'langPrefix' => $langPrefix,
            'noRender'   => $view !== 'olut::form'
        ]);

        $data = [
            'item'       => $item,
            'routes'     => static::$crudRoutes,
            'langPrefix' => $langPrefix,
            'layout'      => $this->getOlutOptions('layout') ?: 'olut::layout',
            'showTab'    => $this->getOlutOptions('showTab') ?: true,
            'lomake'     => $lomake
        ];

        return View::make($view, $data);
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
                ? $model->ofCurrentUser()->findOrFail($id)
                : new $model();

            $item = $this->upsertHandler($item);

            $message = ($id !== null)
                ? trans('olut::olut.success_edit')
                : trans('olut::olut.success_add');

            return Redirect::route(static::$crudRoutes['index'])
                ->with('messages', $this->successMessageBag($message));
        } catch (\Watson\Validating\ValidationException $ex) {
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

        $errors = $this->errorMessageBag(trans('olut::olut.err.unexpected'));

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
        $item = $this->getModel()->ofCurrentUser()->findOrFail($id);
        $item->delete();

        if (Request::ajax() === true) {
            return Response::json(['success' => true]);
        }

        return Redirect::route(static::$crudRoutes['index'])
            ->with(
                'messages',
                $this->successMessageBag(trans('olut::olut.success_delete'))
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
        // Add ID to be candicate for searching
        $fillable[] = 'id';
        $query = $query->where(function ($subQuery) use ($fillable, $q) {
            foreach ($fillable as $field) {
                $subQuery = $subQuery->orWhere($field, 'LIKE', '%'.$q.'%');
            }

            return $subQuery;
        });

        // Limit results to of the current user only
        if (method_exists($this->getModel(), 'user')) {
            $query = $query->ofCurrentUser();
        }

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        // Disable sorting items
        $this->crudSortable = false;

        return $this->renderList($items);
    }

    /**
     * Handle bulk actions
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
            return Response::json(['message' => trans('olut::olut.success_bulk')]);
        }

        return Redirect::route(static::$crudRoutes['index'])
            ->with('messages', $this->successMessageBag(
                trans('olut::olut.success_bulk')
            ));
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
}