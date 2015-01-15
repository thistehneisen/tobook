<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Request, Input, Config, Response;
use App\Appointment\Models\ServiceCategory;

class Categories extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.services.category';
    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\ServiceCategory',
        'langPrefix' => 'as.services.categories',
        'layout' => 'modules.as.layout',
        'showTab' => true,
        'bulkActions' => [],
        'crudSortable' => true,
        'indexFields' => [
            'name', 'description', 'is_show_front',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();

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
