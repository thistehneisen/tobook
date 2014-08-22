<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input;
use App\Appointment\Models;

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

    public function doCreateCategory()
    {
        $input = Input::all();
        unset($input['_token']);
        $input['user_id'] = $this->user->id;
        $category = App::make('App\Appointment\Models\ServiceCategory');
        $category->unguard();
        $category->fill($input);
        $category->reguard();
        $category->save();
        return Redirect::route('as.services.categories');
    }

    public function resources()
    {
        return View::make('modules.as.services.resources');
    }
}
