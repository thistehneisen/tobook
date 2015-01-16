<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use App\Consumers\Models\EmailTemplate;
use App\Consumers\Models\SmsTemplate;
use Confide, Config;
use DB;
use Input;
use Lang;
use Redirect;
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
        'showTab' => false,
        'bulkActions' => [
            'destroy',
            'send_email',
            'send_sms',
        ],
        'presenters' => [
            'consumers' => 'App\Consumers\Presenters\GroupConsumers',
        ],
    ];

    public function bulkSendEmail($ids)
    {
        $campaign = null;
        $campaignId = intval(Input::get('campaign_id'));
        if (!empty($campaignId)) {
            $campaign = EmailTemplate::ofCurrentUser()->findOrFail($campaignId);
        }

        if (!empty($campaign)) {
            list($sent, $total) = EmailTemplate::sendGroups($campaign, $ids);

            return Redirect::route('consumer-hub.history.email', ['campaign_id' => $campaign->id])
                ->with('messages', $this->successMessageBag(
                    trans('co.email_templates.sent_to_x_of_y', [
                        'sent' => $sent,
                        'total' => $total,
                    ])
                ));
        }

        $campaigns = EmailTemplate::ofCurrentUser()->get();
        $campaignPairs = [];
        foreach ($campaigns as $campaign) {
            $campaignPairs[$campaign->id] = $campaign->subject;
        }

        $groups = \App\Consumers\Models\Group::ofCurrentUser()->whereIn('id', $ids)->get();

        return View::make('modules.co.groups.bulk_send_email', [
            'campaignPairs' => $campaignPairs,
            'groups' => $groups,
        ]);
    }

    public function bulkSendSms($ids)
    {
        $sms = null;
        $smsId = intval(Input::get('sms_id'));
        if (!empty($smsId)) {
            $sms = SmsTemplate::ofCurrentUser()->findOrFail($smsId);
        }

        if (!empty($sms)) {
            list($sent, $total) = SmsTemplate::sendGroups($sms, $ids);

            return Redirect::route('consumer-hub.history.sms', ['sms_id' => $sms->id])
                ->with('messages', $this->successMessageBag(
                    trans('co.sms_templates.sent_to_x_of_y', [
                        'sent' => $sent,
                        'total' => $total,
                    ])
                ));
        }

        $smsAll = SmsTemplate::ofCurrentUser()->get();
        $smsPairs = [];
        foreach ($smsAll as $sms) {
            $smsPairs[$sms->id] = sprintf(
                '%s (%s: %s)',
                $sms->title,
                trans('co.sms_templates.from_name'),
                $sms->from_name ?: Config::get('varaa.name')
            );
        }

        $groups = \App\Consumers\Models\Group::ofCurrentUser()->whereIn('id', $ids)->get();

        return View::make('modules.co.groups.bulk_send_sms', [
            'smsPairs' => $smsPairs,
            'groups' => $groups,
        ]);
    }
}
