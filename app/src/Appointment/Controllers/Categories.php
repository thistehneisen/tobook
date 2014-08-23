<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
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
        $categories = $this->categoryModel
            ->ofCurrentUser()
            ->paginate(Config::get('view.perPage'));

        return View::make('modules.as.services.categories', [
            'categories' => $categories
        ]);
    }

    /**
    * Show empty category form
    *
    * @return View
    */
    public function createCategory(){
        return View::make('modules.as.services.category.form');
    }

    /**
     * Handle user input to create a new category
     *
     * @return Redirect
     */
    public function doCreateCategory()
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

    public function editCategory($id){

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

    public function doEditCategory($id){
        try{

            $category = $this->categoryModel->findOrFail($id);
            $category->fill(Input::all());
            // Attach user
            $category->user()->associate($this->user);
            $category->saveOrFail();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag('Category with ID' . $id . ' was not found.'));
        }

        return Redirect::route('as.services.categories')
            ->with('messages', $this->successMessageBag('Category with ID' . $id . ' was updated.'));
    }

    public function deleteCategory($id){
        $item = $this->categoryModel->findOrFail($id);
        if ($item) {
            $item->delete();
        }

        return Redirect::route('as.services.categories')->with(
                'messages',
                $this->successMessageBag('ID #'.$id.' was deleted')
            );
    }
}
