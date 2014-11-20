<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use Confide;
use DB;
use Input;
use Lang;
use Session;
use View;

class Group extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.co.groups';

    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\Group',
        'langPrefix' => 'co.groups',
        'indexFields' => ['name', 'consumers'],
        'layout' => 'modules.co.layout',
        'presenters' => [
            'consumers' => 'App\Consumers\Presenters\GroupConsumers',
        ],
    ];
}
