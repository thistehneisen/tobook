<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Traits\Crud;

class Categories extends AsBase
{
    use Crud;

    /**
     * {@inheritdoc}
     */
    protected function getModelClass()
    {
        return 'App\Appointment\Models\ServiceCategory';
    }

    /**
     * {@inheritdoc}
     */
    protected function getViewPath()
    {
        return 'modules.as.services.category';
    }

    /**
     * {@inheritdoc}
     */
    protected function getLangPrefix()
    {
        return 'as.services.category';
    }

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        return $item;
    }

    public function destroy()
    {
        $categories = Input::get('categories', []);
        try {
            $this->categoryModel->destroy($categories);
            $data['success'] = true;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['error'] = $ex->getMessage();
        }
        return Response::json($data);
    }
}
