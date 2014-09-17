<?php namespace App\Olut;

use App, Input, Config, Redirect, Route, View, Validator, Request, Response, DB;

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
            'layout'      => $this->getOlutOptions('layout'),
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

}
