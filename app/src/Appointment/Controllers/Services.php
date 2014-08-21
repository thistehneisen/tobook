<?php namespace App\Appointment\Controllers;

use App, View, URL, Confide, Redirect, Validator, Input;
use App\Core\Controllers\Base;
use App\Appointment\Models;

class Services extends ServiceBase
{
    public function __construct()
    {
        // @todo: Check membership. It's better to have a filter and attach it
        // to this route
        parent::__construct();
    }

    public function index(){
        return View::make('modules.as.services.index');
    }

    public function create()
    {
        return View::make('modules.as.services.create');
    }

    public function categories()
    {
        $categories = $this->categoryModel
            ->where('user_id', $this->user_id)
            ->get();
        return View::make('modules.as.services.categories', [
            'categories' => $categories
        ]);
    }

    public function doCreateCategory(){
        $input = Input::all();
        unset($input['_token']);
        $input['user_id'] = Confide::user()->id;
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
