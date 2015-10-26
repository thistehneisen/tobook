<?php namespace App\Olut;

use App;
use Config;
use Input;
use Lomake;
use Redirect;
use Request;
use Response;
use Route;
use Validator;
use View;

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

    protected $crudFillable;

    /**
     * Return a model to interact with database
     *
     * @return Eloquent
     */
    public function getModel()
    {
        if ($this->model === null) {
            $this->model = App::make($this->getModelClass());
            $this->crudFillable = $this->model->fillable;
        }

        if (method_exists($this->model, 'scopeOfCurrentUser')) {
            return $this->model->ofCurrentUser();
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
     * Return original fillable property
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->crudFillable;
    }

    /**
     * Return options defined in $this->crudOptions
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getOlutOptions($key, $default = null)
    {
        if (isset($this->crudOptions) && isset($this->crudOptions[$key])) {
            return $this->crudOptions[$key];
        } else {
            return $default;
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
        $query = $this->getModel();

        // Allow to filter results in query string
        $query = $this->applyQueryStringFilter($query);

        // If this controller is sortable
        if ($this->getOlutOptions('sortable') === true) {
            $query = $query->orderBy('order');
        }

        // Eager loading
        if ($prefetch = $this->getOlutOptions('prefetch')) {
            $query = $query->with($prefetch);
        }

        $query = $this->oulutCustomIndexQuery($query);

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $perPage = $perPage < 0 ? 0 : $perPage;
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
        $fillable = $this->getFillable();

        // Get all query string values
        // If present, limit the return results
        foreach (Request::instance()->query->all() as $key => $value) {
            if (in_array($key, $fillable)) {
                $query = $query->where($key, $value);
            }
        }

        return $query;
    }

    /**
     * Allow to modify the query used to get all records
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function oulutCustomIndexQuery($query)
    {
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
        $view = View::exists($this->makeViewPath('index'))
            ? $this->makeViewPath('index')
            : 'olut::index';

        // user can overwrite default CRUD tabs template
        $tabsView = View::exists($this->makeViewPath('tabs'))
            ? $this->makeViewPath('tabs')
            : 'olut::tabs';

        // Get language prefix
        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        // List of methods that can handle bulk actions
        $bulkActions = $this->getOlutOptions('bulkActions', ['destroy']);

        // Bartender will take care the output of fields
        $bartender = new Bartender($this->getOlutOptions('presenters', []));

        // Check if there is additionall actions
        $actionsView = null;
        if (View::exists($this->getOlutOptions('actionsView'))) {
            $actionsView = $this->getOlutOptions('actionsView');
        }

        // If there is an additional scripts.blade.php in the view folder,
        // we'll include it
        $scriptsView = View::exists($this->getViewPath().'.index_scripts')
            ? $this->getViewPath().'.index_scripts'
            : '';

        return View::make($view, [
            'items'       => $items,
            'routes'      => static::$crudRoutes,
            'langPrefix'  => $langPrefix,
            'fields'      => $fields,
            'bulkActions' => $bulkActions,
            'sortable'    => $this->getOlutOptions('sortable', false),
            'showTab'     => $this->getOlutOptions('showTab', true),
            'deleteReason'=> $this->getOlutOptions('deleteReason', false),
            'layout'      => $this->getOlutOptions('layout', 'olut::layout'),
            'bartender'   => $bartender,
            'tabsView'    => $tabsView,
            'actionsView' => $actionsView,
            'scripts'     => $scriptsView,
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
        return $this->getOlutOptions('indexFields', $this->getFillable());
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
        $modelClass = $this->getModelClass();
        $item = ($id !== null)
            ? $model->findOrFail($id)
            : new $modelClass();

        $template = View::exists($this->getViewPath().'.form')
            ? $this->getViewPath().'.form'
            : 'olut::form';

        // user can overwrite default CRUD tabs template
        $tabsView = View::exists($this->getViewPath().'.tabs')
            ? $this->getViewPath().'.tabs'
            : 'olut::tabs';

        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        $options = $this->getOlutOptions('lomake', []);
        $lomake = Lomake::make($item, [
            'route'      => [static::$crudRoutes['upsert'], isset($item) ? $item->id : null],
            'fields'     => $options,
            'langPrefix' => $langPrefix,
        ]);

        // If there is an additional scripts.blade.php in the view folder,
        // we'll include it
        $scriptsView = View::exists($this->getViewPath().'.form_scripts')
            ? $this->getViewPath().'.form_scripts'
            : '';

        $data = [
            'tabsView'   => $tabsView,
            'item'       => $item,
            'routes'     => static::$crudRoutes,
            'langPrefix' => $langPrefix,
            'layout'     => $this->getOlutOptions('layout', 'olut::layout'),
            'showTab'    => $this->getOlutOptions('showTab', true),
            'lomake'     => $lomake,
            'scripts'    => $scriptsView,
        ];

        $view = View::make($template, $data);
        if (method_exists($this, 'overwrittenUpsert')) {
            return $this->overwrittenUpsert($view, $item);
        }

        return $view;
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
        $modelClass = $this->getModelClass();

        try {
            $item = ($id !== null)
                ? $model->findOrFail($id)
                : new $modelClass();

            $item = $this->upsertHandler($item);
            // Sometimes you might want to do something else, for example,
            // redirect to the next step
            if ($item instanceof \Illuminate\Http\RedirectResponse) {
                return $item;
            }

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
        $item = $this->getModel()->findOrFail($id);
        $item->delete();

        if (Request::ajax() === true) {
            return Response::json(['success' => true]);
        }

        $referer = Request::instance()->header('referer');

        return Redirect::to(!empty($referer) ? $referer : static::$crudRoutes['index'])
            ->with('messages',
                $this->successMessageBag(trans('olut::olut.success_delete')));
    }

    /**
     * Search the given keyword in all fillable fields
     *
     * @return View
     */
    public function search()
    {
        $keyword = Input::get('q');

        // Call static search method of this model
        $className = $this->getModelClass();
        $instance = new $className();
        $query = $instance->newQuery();
        if (method_exists($instance, 'scopeOfCurrentUser')) {
            $query->ofCurrentUser();
        }

        $query->where(function ($q) use ($instance, $keyword) {
            foreach ($instance->fillable as $key) {
                if (starts_with($key, 'is_')) {
                    continue;
                }

                $q->orWhere($key, 'LIKE', '%'.$keyword.'%');
            }
        });

        $items = $query->paginate();

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
            // an alternative $action
            $action = 'bulk' . str_replace(' ', '', ucwords(str_replace('_', ' ', $action)));
        }
        if (!method_exists($this, $action)) {
            throw new \InvalidArgumentException('Method is not allowed');
        }

        $response = call_user_func([$this, $action], Input::get('ids'));
        if (is_object($response)) {
            return $response;
        }

        if (Request::ajax()) {
            return Response::json(['message' => trans('olut::olut.success_bulk')]);
        }

        $referer = Request::instance()->header('referer');

        return Redirect::to(!empty($referer) ? $referer : static::$crudRoutes['index'])
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
        return $this->getModel()
            ->whereIn('id', $ids)
            ->delete();
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
            $this->getModel()
                ->where('id', $id)
                ->update(['order' => $order]);
        }
    }
}
