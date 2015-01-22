<?php namespace App\Consumers\Traits;

use App\Consumers\Models\EmailTemplate;
use App\Consumers\Models\SmsTemplate;
use App\Consumers\Models\Consumer;
use App\Consumers\Models\Group;
use Input, Config, Redirect, View;
use Lomake;

trait Marketing
{
    public static function sendEmails($ids, $sendGroup = false)
    {
        $sendAll = false;
        if (in_array('all', $ids)) {
            $sendAll = true;
            $ids = Consumer::ofCurrentUser()->lists('id');
        }

        $campaign = null;
        $campaignId = intval(Input::get('campaign_id'));
        if (!empty($campaignId)) {
            $campaign = EmailTemplate::ofCurrentUser()->findOrFail($campaignId);
        }

        if (!empty($campaign)) {
            $sent = $sendGroup
                ? EmailTemplate::sendGroups($campaign, $ids)
                : EmailTemplate::sendConsumers($campaign, $ids);

            return Redirect::route('consumer-hub.history.email', ['campaign_id' => $campaign->id])
                ->with('messages', $this->successMessageBag(
                    trans('co.email_templates.sent_to_x_of_y', [
                        'sent' => $sent,
                        'total' => count($ids),
                    ])
                ));
        }

        $campaigns = EmailTemplate::ofCurrentUser()->get();
        $campaignPairs = [];
        foreach ($campaigns as $campaign) {
            $campaignPairs[$campaign->id] = $campaign->subject;
        }

        $targets = $sendGroup
            ? Group::ofCurrentUser()->whereIn('id', $ids)->get()
            : $sendAll
                ? []
                : Consumer::ofCurrentUser()->whereIn('id', $ids)->get();

        return [
            'campaignPairs' => $campaignPairs,
            'targets' => $targets,
            'sendAll' => $sendAll,
        ];
    }

    public static function sendSms($ids, $sendGroup = false)
    {
        $sendAll = false;
        if (in_array('all', $ids)) {
            $sendAll = true;
            $ids = Consumer::ofCurrentUser()->lists('id');
        }

        $sms = null;
        $smsId = intval(Input::get('sms_id'));
        if (!empty($smsId)) {
            $sms = SmsTemplate::ofCurrentUser()->findOrFail($smsId);
        }

        if (!empty($sms)) {
            $sent = $sendGroup
                ? SmsTemplate::sendGroups($sms, $ids)
                : SmsTemplate::sendConsumers($sms, $ids);

            return Redirect::route('consumer-hub.history.sms', ['sms_id' => $sms->id])
                ->with('messages', $this->successMessageBag(
                    trans('co.sms_templates.sent_to_x_of_y', [
                        'sent' => $sent,
                        'total' => count($ids),
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

        $targets = $sendGroup
            ? Group::ofCurrentUser()->whereIn('id', $ids)->get()
            : $sendAll
                ? []
                : Consumer::ofCurrentUser()->whereIn('id', $ids)->get();

        return [
            'smsPairs' => $smsPairs,
            'targets' => $targets,
            'sendAll' => $sendAll,
        ];
    }
}
