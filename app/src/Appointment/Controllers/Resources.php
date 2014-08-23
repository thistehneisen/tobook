<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\Resource;

class Resources extends AsBase
{
    public function resources()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $resources = $this->resourceModel
            ->ofCurrentUser()
            ->paginate($perPage);

        return View::make('modules.as.services.resources', [
            'resources' => $resources
        ]);
    }

    public function create(){
        return View::make('modules.as.services.resource.form');
    }

    /**
     * Handle user input to create a new resource
     *
     * @return Redirect
     */
    public function doCreate(){
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $resource = new Resource;
            $resource->fill(Input::all());
            // Attach user
            $resource->user()->associate($this->user);
            $resource->saveOrFail();

            return Redirect::route('as.services.resources')
                ->with('messages', $this->successMessageBag(
                    trans('as.services.success_add_resrouces')
                ));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    public function edit($id){
        try{
            $resource = $this->resourceModel->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
             return Redirect::route('as.services.resources')
                ->with('messages', $this->successMessageBag('Resource with ID #' . $id . ' was not found.'));
        }

        return View::make('modules.as.services.resource.form', [
                'resource' => $resource
            ]);
    }

    public function doEdit($id){
         try{

            $resource = $this->resourceModel->findOrFail($id);
            $resource->fill(Input::all());
            // Attach user
            $resource->user()->associate($this->user);
            $resource->saveOrFail();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return Redirect::route('as.services.resources')
                ->with('messages', $this->successMessageBag('Resource with ID #' . $id . ' was not found.'));
        }

        return Redirect::route('as.services.resources')
            ->with('messages', $this->successMessageBag('Resource with ID #' . $id . ' was updated.'));
    }

    public function delete($id){
        $item = $this->resourceModel->findOrFail($id);
        if ($item) {
            $item->delete();
        }

        return Redirect::route('as.services.resources')->with(
                'messages',
                $this->successMessageBag('ID #'.$id.' was deleted')
            );
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
