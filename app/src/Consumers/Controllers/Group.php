<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use Input, View;

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
        return View::make('modules.co.groups.bulk_send_email', static::sendEmails($ids, true));
    }

    public function bulkSendSms($ids)
    {
        return View::make('modules.co.groups.bulk_send_sms', static::sendSms($ids, true));
    }
}
