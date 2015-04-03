<?php namespace App\Core\Controllers\Admin;

use App\Core\Models\Setting;
use App\Lomake\FieldFactory;
use App\Appointment\Models\MasterCategory;
use App\Core\Models\Multilanguage;
use Config, View, Input, Redirect, DB, App;
use Carbon\Carbon;
use App\Olut\Olut;

class MasterCategories extends Base
{

    use \CRUD;
    protected $viewPath = 'admin.master-cats';

    protected $crudOptions = [
        'modelClass'  => 'App\Appointment\Models\MasterCategory',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'admin.master-cats',
        'indexFields' => ['name', 'description']
    ];

    /**
     * @{@inheritdoc}
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

        // Don't know why model User doesn't take SoftDeleteTrait, so this
        // would fix temporarily.
        $query = $query->whereNull('deleted_at');

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        return $this->renderList($items);
    }

    /**
     * Show the form to insert or update a master category
     *
     * @param int $id
     *
     * @return View
     */
    public function upsert($id = null)
    {
        $masterCat  = MasterCategory::find($id);
        $items = MasterCategory::where('as_master_categories.id', '=', $id)
            ->join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . MasterCategory::getContext() . "', `varaa_as_master_categories`.`id`)"))->get();

        $data = [];
        foreach (Config::get('varaa.languages') as $locale){
            foreach ($items as $item) {
                if($locale == $item->lang) {
                    $data[$locale][$item->key] = $item->value;
                }
            }
        }
        // user can overwrite default CRUD tabs template
        $tabsView = View::exists($this->getViewPath().'.tabs')
            ? $this->getViewPath().'.tabs'
            : 'olut::tabs';

        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        // If there is an additional scripts.blade.php in the view folder,
        // we'll include it
        $scriptsView = View::exists($this->getViewPath().'.form_scripts')
            ? $this->getViewPath().'.form_scripts'
            : '';

        return View::make('admin.master-cats.form', [
            'item'       => $masterCat,
            'data'       => $data,
            'tabsView'   => $tabsView,
            'routes'     => static::$crudRoutes,
            'scripts'    => $scriptsView,
            'langPrefix' => $langPrefix,
            'showTab'    => $this->getOlutOptions('showTab', true),
            'now'        => Carbon::now()
        ]);
    }

    /**
     * @{@inheritdoc}
     */
    public function upsertHandler($masterCat)
    {
        $names = Input::get('name');
        $descriptions = Input::get('description');

        try{
            $masterCat->fill([
                'order' => 1
            ]);
            $masterCat->save();
            $masterCat->saveMultilanguage($names, $descriptions);
        } catch(\Exception $ex){
            throw $ex;
        }
        return $masterCat;
    }

}
