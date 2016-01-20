<?php namespace App\Core\Controllers\Admin;

use Config, View, Input, Redirect, DB, App;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;
use App\Core\Models\Multilanguage;
use App\Olut\Olut;
use Carbon\Carbon;

class TreatmentTypes extends Base
{
    use \CRUD;
    protected $viewPath = 'admin.treatment-types';

    protected $crudOptions = [
        'modelClass'  => 'App\Appointment\Models\TreatmentType',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'admin.treatment-types',
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
        $treatmentType  = TreatmentType::find($id);
        $items = TreatmentType::where('as_treatment_types.id', '=', $id)
            ->join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . TreatmentType::getContext() . "', `varaa_as_treatment_types`.`id`)"))->get();

        $masterCategories = MasterCategory::get()->lists('name', 'id');

        $data = [];
        foreach (Config::get('varaa.languages') as $locale) {
            foreach ($items as $item) {
                if ($locale == $item->lang) {
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

        return View::make('admin.treatment-types.form', [
            'item'             => $treatmentType,
            'masterCategories' => $masterCategories,
            'data'             => $data,
            'tabsView'         => $tabsView,
            'routes'           => static::$crudRoutes,
            'scripts'          => $scriptsView,
            'langPrefix'       => $langPrefix,
            'showTab'          => $this->getOlutOptions('showTab', true),
            'now'              => Carbon::now()
        ]);
    }

    /**
     * @{@inheritdoc}
     */
    public function upsertHandler($treatmentType)
    {
        $names            = Input::get('name');
        $descriptions     = Input::get('description');
        $masterCategoryId = Input::get('master_category_id');
        $default_language = Config::get('varaa.default_language');

        try {
            if (empty($masterCategoryId)) {
                throw make_validation_exception('empty_master_category', trans('admin.treatment-types.errors.empty_master_category'));
            }

            if (empty($names[$default_language])) {
                throw make_validation_exception('empty_name', trans('admin.treatment-types.errors.empty_name'));
            }

            $masterCategory = MasterCategory::findOrFail($masterCategoryId);

            $treatmentType->fill([
                'order'       => 1,
                'name'        => $names[$default_language],
                'description' => $descriptions[$default_language],
            ]);
            $treatmentType->masterCategory()->associate($masterCategory);
            $treatmentType->save();
            $treatmentType->saveMultilanguage($names, $descriptions);
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $treatmentType;
    }

    /**
     * Search for treatment types.
     *
     * @return View
     */
    public function search()
    {
        $keyword = Input::get('q');
        $items = with(new TreatmentType())->search($keyword);

        return $this->renderList($items);
    }

}
