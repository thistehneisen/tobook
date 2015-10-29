<?php namespace App\Consumers\Controllers;

use App\Consumers\Models\History;
use App\Core\Controllers\Base;
use Confide, Config, DB, Input, Lang, Redirect, View, Session;

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
        ],
        'lomakeTobook' => [
            'content' => [
                'type' => 'textarea',
                'options' => ['class' => 'form-control', 'rows' => 5]
            ]
        ]
    ];

     /**
     * Return options defined in $this->crudOptions
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getOlutOptions($key, $default = null)
    {
        // If environment is toobok and the user is not admin
        if (is_tobook() && $key === 'lomake') {
            return $this->crudOptions[$key. 'Tobook'];
        }

        if (isset($this->crudOptions) && isset($this->crudOptions[$key])) {
            return $this->crudOptions[$key];
        } else {
            return $default;
        }
    }

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
