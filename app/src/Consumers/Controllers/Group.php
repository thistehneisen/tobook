<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use Input;
use Redirect;
use View;

class Group extends Base
{
    use \CRUD;
    use \App\Consumers\Traits\Marketing;

    protected $viewPath = 'modules.co.groups';
    protected $crudOptions = [
        'modelClass' => 'App\Consumers\Models\Group',
        'langPrefix' => 'co.groups',
        'indexFields' => ['name', 'consumers'],
        'layout' => 'modules.co.layout',
        'bulkActions' => [
            'destroy',
            'send_email',
            'send_sms',
        ],
        'presenters' => [
            'consumers' => ['App\Consumers\Controllers\Group', 'presentGroup'],
        ],
    ];

    public static function presentGroup($value, $item)
    {
        return View::make('modules.co.groups.presenters.consumers', ['consumers' => $value]);
    }

    /**
     * @{@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }

    public function bulkSendEmail($ids)
    {
        $result = static::sendEmails($ids, true);
        if (array_key_exists('template_id', $result)) {
            list($sent, $consumers) = $result['sent'];

            $params = [
                'sent' => $sent,
                'total' => $result['total'],
            ];
            return Redirect::route('consumer-hub.history.email', ['campaign_id' => $result['template_id']])
                ->with('messages', $this->successMessageBag(
                    trans('co.email_templates.sent_to_x_of_y', $params)
                ));
        }

        return View::make('modules.co.groups.bulk_send_email', $result);
    }

    public function bulkSendSms($ids)
    {
        $result = static::sendSms($ids, true);
        if (array_key_exists('template_id', $result)) {
            list($sent, $consumers) = $result['sent'];

            $params = [
                'sent' => $sent,
                'total' => $result['total'],
            ];

            return Redirect::route('consumer-hub.history.sms', ['sms_id' => $result['template_id']])
                ->with('messages', $this->successMessageBag(
                    trans('co.sms_templates.sent_to_x_of_y', $params)
                ));
        }
        return View::make('modules.co.groups.bulk_send_sms', $result);
    }
}
