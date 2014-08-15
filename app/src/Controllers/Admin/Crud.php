<?php namespace App\Controllers\Admin;

use App, Config, Request;

class Crud extends Base
{
    public function __construct()
    {
        $modelName = str_singular(Request::segment(2));
        $this->model = App::make(ucfirst($modelName));
    }

    /**
     * List all records in database with pagination
     *
     * @return View
     */
    public function index()
    {
        $items = $this->model->paginate(Config::get('view.perPage'));
        
        return $this->render('crud.index', [
            'model' => $this->model,
            'items' => $items
        ]);
    }

    /**
     * Show the form to edit an item
     *
     * @param  string $type 
     * @param  int $id   
     *
     * @return View
     */
    public function edit($type, $id)
    {
        $item = $this->model->where('id', $id)->firstOrFail();
        
        return $this->render('crud.edit', [
            'model' => $this->model,
            'item' => $item
        ]);
    }
}
