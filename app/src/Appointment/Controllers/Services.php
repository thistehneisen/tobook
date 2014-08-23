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

    public function createCategory(){
        return View::make('modules.as.services.category.form', [
            'category' => null
        ]);
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
        $category->fill($input);
        $category->save();

        if (!$category->save()) {
            // dd($category->getErrors());
            return Redirect::back()
                ->withInput()
                ->withErrors($category->getErrors(), 'top');
        }
        return Redirect::route('as.services.categories')
            ->with('messages', $this->successMessageBag('Category was created.'));
    }

    public function editCategory($id){

        if(empty($id)){
            return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag('Category was not found.'));
        }

        $category = $categories = $this->categoryModel->find($id);
        return View::make('modules.as.services.category.form', [
            'category' => $category
        ]);
    }

    public function doEdit(){

    }

    public function resources()
    {
        return View::make('modules.as.services.resources');
    }
}
