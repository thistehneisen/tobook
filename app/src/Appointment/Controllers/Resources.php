<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\Resource;
use App\Appointment\Traits\Crud;

class Resources extends AsBase
{
    use App\Appointment\Traits\Crud;

    /**
     * {@inheritdoc}
     */
    protected function getViewPath()
    {
        return 'modules.as.services.resource';
    }

    /**
     * {@inheritdoc}
     */
    protected function getLangPrefix()
    {
        return 'as.services.resource';
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

    public function destroy(){
        //TODO check if it remove all service resources mapping?
        $resources = Input::get('resources', []);
        try{
            $this->resourceModel->destroy($resources);
            $data['success'] = true;
        } catch (\Exception $ex){
            $data['success'] = false;
            $data['error'] = $ex->getMessage();
        }
        return Response::json($data);
    }
}
