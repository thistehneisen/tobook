<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\ServiceCategory;

class Categories extends AsBase
{

     /**
     * Show all categories of the current user and a form to add new category
     *
     * @return View
     */
    public function categories()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $categories = $this->categoryModel
            ->ofCurrentUser()
            ->paginate($perPage);

        return View::make('modules.as.services.categories', [
            'categories' => $categories
        ]);
    }

    public function datatable(){
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $categories = $this->categoryModel
            ->ofCurrentUser()->select(array('id', 'name', 'is_show_front', 'description'))->paginate($perPage);
        $data = array_map('array_values',$categories->getCollection()->toArray());
        return Response::json(array('data'=>$data));
    }

    /**
    * Show empty category form
    *
    * @return View
    */
    public function create(){
        return View::make('modules.as.services.category.form');
    }

    /**
     * Handle user input to create a new category
     *
     * @return Redirect
     */
    public function doCreate()
    {
        $errors = $this->errorMessageBag(trans('common.err.unexpected'));

        try {
            $category = new ServiceCategory;
            $category->fill(Input::all());
            // Attach user
            $category->user()->associate($this->user);
            $category->saveOrFail();

            return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag(
                    trans('as.services.success_add_category')
                ));
        } catch (\Watson\Validating\ValidationException $ex) {
            $errors = $ex->getErrors();
        }

        return Redirect::back()->withInput()->withErrors($errors, 'top');
    }

    public function edit($id){

        try{
            $category = $this->categoryModel->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
             return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag('Category with ID #' . $id . ' was not found.'));
        }

        return View::make('modules.as.services.category.form', [
            'category' => $category
        ]);
    }

    public function doEdit($id){
        try{

            $category = $this->categoryModel->findOrFail($id);
            $category->fill(Input::all());
            // Attach user
            $category->user()->associate($this->user);
            $category->saveOrFail();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag('Category with ID #' . $id . ' was not found.'));
        }

        return Redirect::route('as.services.categories')
            ->with('messages', $this->successMessageBag('Category with ID #' . $id . ' was updated.'));
    }

    public function delete($id){
        $item = $this->categoryModel->findOrFail($id);
        if ($item) {
            $item->delete();
        }

        return Redirect::route('as.services.categories')->with(
                'messages',
                $this->successMessageBag('ID #'.$id.' was deleted')
            );
    }

    public function destroy(){
        $categories = Input::get('categories', []);
        try{
            $this->categoryModel->destroy($categories);
            $data['success'] = true;
        } catch (\Exception $ex){
            $data['success'] = false;
            $data['error'] = $ex->getMessage();
        }
        return Response::json($data);
    }
}
