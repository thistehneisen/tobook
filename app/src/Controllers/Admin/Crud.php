<?php namespace App\Controllers\Admin;

use App, Config, Request;

class Crud extends Base
{
    public function __construct()
    {
        $modelName = str_singular(Request::segment(2));
        $this->model = App::make(ucfirst($modelName));
    }

    public function index()
    {
        $items = $this->model->orderBy('id', 'desc')->paginate(Config::get('view.perPage'));
        
        return $this->render('crud.index', [
            'model' => $this->model,
            'items' => $items
        ]);
    }
}
