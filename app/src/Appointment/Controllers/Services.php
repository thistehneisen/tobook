<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Response;
use App\Appointment\Models\ServiceCategory;

class Services extends ServiceBase
{

    public function index()
    {
        return View::make('modules.as.services.index');
    }

    public function create()
    {
        return View::make('modules.as.services.create');
    }

    /**
     * Show all categories of the current user and a form to add new category
     *
     * @return View
     */
    public function categories()
    {
        $categories = $this->categoryModel
            ->where('user_id', $this->user->id)
            ->get();

        return View::make('modules.as.services.categories', [
            'categories' => $categories
        ]);
    }

    /**
    * Show empty category form
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
        $input = Input::all();
        $input['user_id'] = $this->user->id;
        $category = new ServiceCategory;
        $this->_saveOrUpdate($category);

        return Redirect::route('as.services.categories')
            ->with('messages', $this->successMessageBag('Category was created.'));
    }

    public function editCategory($id){

        try{
            $category = $this->categoryModel->findOrFail($id);
        } catch (ModelNotFoundException $e){
             return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag('Category with ID {$id} was not found.'));
        }

        return View::make('modules.as.services.category.form', [
            'category' => $category
        ]);
    }

    public function doEditCategory($id){
        try{
            $category = $this->categoryModel->findOrFail($id);
            $this->_saveOrUpdate($category);
        } catch (ModelNotFoundException $e){
             return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag('Category with ID {$id} was not found.'));
        }

        return Redirect::route('as.services.categories')
            ->with('messages', $this->successMessageBag('Category with ID {$id} was updated.'));
    }

    /**
    * Handle create new category or update existed category
    *
    * return Redriect
    */
    private function _saveOrUpdate($category){
        $input = Input::all();
        $input['user_id'] = $this->user->id;
        $category->fill($input);
        $category->save();

        if (!$category->save()) {
            return Redirect::back()
                ->withInput()
                ->withErrors($category->getErrors(), 'top');
        }
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

    public function resources()
    {
        return View::make('modules.as.services.resources');
    }
}
