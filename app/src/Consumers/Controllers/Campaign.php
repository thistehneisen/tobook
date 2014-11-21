<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use App\Lomake\Fields\HtmlField;
use Confide;
use DB;
use Input;
use Lang;
use Session;
use View;

class Campaign extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.co.campaigns';

    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\Campaign',
        'langPrefix' => 'co.campaigns',
        'indexFields' => ['subject', 'from_email', 'from_name'],
        'lomake' => [
            'content' => [
                'type' => 'html_field',
            ],
        ],
        'layout' => 'modules.co.layout',
        'showTab' => false,
    ];

    protected function upsertHandler($item)
    {
        $item->fill([
            'subject' => Input::get('subject'),
            'from_email' => Input::get('from_email'),
            'from_name' => Input::get('from_name'),
            'content' => HtmlField::filterInput(Input::all(), 'content'),
        ]);
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
