<?php namespace App\Consumers\Controllers;

use App\Consumers\Models\History;
use App\Core\Controllers\Base;
use Confide;
use Config;
use DB;
use Input;
use Lang;
use Session;
use View;

class SmsTemplate extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.co.sms_templates';

    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\SmsTemplate',
        'langPrefix' => 'co.sms_templates',
        'indexFields' => ['title', 'content', 'from_name'],
        'layout' => 'modules.co.layout',
        'actionsView' => 'modules.co.sms_templates.actions',
        'showTab' => false,
        'lomake' => [
            'content' => [
                'type' => 'textarea',
                'options' => ['class' => 'form-control', 'maxlength' => 160, 'rows' => 5]
            ]
        ]
    ];

    protected function upsertHandler($item)
    {
        $item->fill([
            'title' => Input::get('title'),
            'content' => Input::get('content'),
            'from_name' => Input::get('from_name'),
        ]);
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }

    public function history()
    {
        $historyQuery = null;

        $smsId = intval(Input::get('sms_id', 0));
        if (!empty($smsId)) {
            $sms = \App\Consumers\Models\SmsTemplate::ofCurrentUser()->findOrFail($smsId);
            $historyQuery = $sms->histories();
        } else {
            $historyQuery = History::ofCurrentUser();
        }

        $historyQuery->orderBy('created_at', 'desc');
        $historyQuery->with(['group', 'consumer', 'sms']);
        $historyQuery->whereNotNull('sms_id');

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $histories = $historyQuery->paginate($perPage);

        return View::make('modules.co.history.sms', [
            'histories'   => $histories,
        ]);
    }
}
