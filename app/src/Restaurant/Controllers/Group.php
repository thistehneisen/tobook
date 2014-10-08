<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;
use App\Restaurant\Models\Table as TableModel;

class Group extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.rb.groups';
    protected $crudOptions = [
        'langPrefix'    => 'rb.groups',
        'modelClass'    => 'App\Restaurant\Models\Group',
        'layout'        => 'modules.rb.layout',
        'presenters'    => [
            'tables'        => 'App\Restaurant\Presenters\GroupPresenter'
        ],
        'indexFields'   => ['name', 'tables', 'description'],
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $tableIds = Input::get('table_id');
        $item->saveOrFail();
        $item->tables()->detach();
        $item->tables()->attach($tableIds);
    }

    public function upsert($id = null)
    {
        $view = $this->defaultUpsert($id);
        $data = $view->getData();

        $tables = TableModel::ofCurrentUser()->get();
        $selectedTables  = $data['item']->tables->lists('id');
        return $view->with('tables', $tables)
            ->with('selectedTables', $selectedTables);
    }
}
