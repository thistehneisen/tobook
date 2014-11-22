<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use App\Lomake\Fields\HtmlField;
use Confide;
use DB;
use Input;
use Lang;
use Session;
use View;

class Sms extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.co.sms';

    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\Sms',
        'langPrefix' => 'co.sms',
        'indexFields' => ['title', 'content'],
        'layout' => 'modules.co.layout',
        'showTab' => false,
    ];

    protected function upsertHandler($item)
    {
        $item->fill([
            'title' => Input::get('title'),
            'content' => Input::get('content'),
        ]);
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
