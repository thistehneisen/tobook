<?php namespace App\Core\Controllers\Admin;

use Config, View, Input, Redirect, DB, App;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;
use App\Appointment\Models\KeywordTreatmentType;
use App\Core\Models\Setting;
use App\Core\Models\Multilanguage;
use App\Olut\Olut;
use Carbon\Carbon;

class Keywords extends Base
{
    use \CRUD;
    protected $viewPath = 'admin.keywords';

    protected $crudOptions = [
        'modelClass'  => 'App\Appointment\Models\KeywordTreatmentType',
        'layout'      => 'layouts.admin',
        'langPrefix'  => 'admin.keywords',
        'indexFields' => ['keyword', 'treatment_type'],
        'presenters'  => [
            'treatment_type' => ['App\Core\Controllers\Admin\Keywords', 'presentTreatmentType'],
        ]
    ];

    public static function presentTreatmentType($value, $item)
    {
        if ($item->treatment_type !== null) {
            return $item->treatment_type->name;
        }
        return $value;
    }

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
        $keyword  = KeywordTreatmentType::find($id);

        $treatmentTypes = TreatmentType::get()->lists('name', 'id');

        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        // If there is an additional scripts.blade.php in the view folder,
        // we'll include it
        $scriptsView = View::exists($this->getViewPath().'.form_scripts')
            ? $this->getViewPath().'.form_scripts'
            : '';

        return View::make('admin.keywords.form', [
            'item'             => $keyword,
            'treatmentTypes'   => $treatmentTypes,
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
    public function upsertHandler($keywordTreatmentType)
    {
        $keyword     = Input::get('keyword');
        $treatmentId = Input::get('treatment_type_id');

        try{
            $treatmentType = TreatmentType::find($treatmentId);
            $keywordTreatmentType->fill([
                'keyword' => $keyword
            ]);
            $keywordTreatmentType->treatmentType()->associate($treatmentType);
            $keywordTreatmentType->save();
        } catch(\Exception $ex){
            throw $ex;
        }
        return $keywordTreatmentType;
    }
}
