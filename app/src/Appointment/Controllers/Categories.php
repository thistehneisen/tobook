<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Request, Input, Config, Response, DB;
use App\Appointment\Models\ServiceCategory;

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

        $data = $category->getMultilingualData();

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

        /**
        * Copy any avaialble category name of any language of required field
        * to the default language in case it is empty
        */
        $data = $item->getDefaultData(Input::all());

        $item->fill($data);
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
        if (!$category->isDeletable()) {
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
