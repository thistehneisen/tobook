<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Request, Input, Config, Response, DB;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\Multilanguage;

class Categories extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.services.category';
    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\ServiceCategory',
        'langPrefix' => 'as.services.categories',
        'layout' => 'modules.as.layout',
        'bulkActions' => [],
        'sortable' => true,
        'indexFields' => ['name', 'description', 'is_show_front'],
    ];

    /**
     * {@inheritdoc}
     */
    public function upsert($id = null)
    {
        $category = ($id !== null)
            ? ServiceCategory::findOrFail($id)
            : new ServiceCategory();

        $defaultLanguage = Config::get('varaa.default_language');

        $items = ServiceCategory::where('as_service_categories.id', '=', $id)
            ->join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . ServiceCategory::getContext() . "', `varaa_as_service_categories`.`id`)"))->get();

        $data = [];
        foreach (Config::get('varaa.languages') as $locale){
            foreach ($items as $item) {
                if($locale == $item->lang) {
                    $data[$locale][$item->key] = $item->value;
                }
            }
        }

        if(empty($data[$defaultLanguage])) {
            foreach ($this->multilingualAtrributes as $key) {
                $data[$defaultLanguage][$key] = $category->$key;
            }
        }

        // user can overwrite default CRUD tabs template
        $tabsView = View::exists($this->getViewPath().'.tabs')
            ? $this->getViewPath().'.tabs'
            : 'olut::tabs';


        $langPrefix = (string) $this->getOlutOptions('langPrefix');

        return $this->render('form', [
            'category'   => $category,
            'data'       => $data,
            'langPrefix' => $langPrefix,
            'tabsView'   => $tabsView,
            'routes'     => static::$crudRoutes,
            'showTab'    => $this->getOlutOptions('showTab', true),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $names        = Input::get('names');
        $descriptions = Input::get('descriptions');

        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();

        $item->saveMultilanguage($names, $descriptions);

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {

        $category = ServiceCategory::ofCurrentUser()->findOrFail($id);

         //Cannot delete this category if is there any undeleted booking use its services
        if(!$category->isDeletable()){
            //if there are bookings, redirect back
            $errors = $this->errorMessageBag(trans('as.services.categories.error.category_current_in_use'));
            return Redirect::route(static::$crudRoutes['index'])
                ->withInput()->withErrors($errors, 'top');
        }

        $category->delete();

        if (Request::ajax() === true) {
            return Response::json(['success' => true]);
        }

        return Redirect::route(static::$crudRoutes['index'])
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }
}
