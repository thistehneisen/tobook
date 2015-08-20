<?php namespace App\Consumers\Traits;

use App\Consumers\Models\EmailTemplate;
use App\Consumers\Models\SmsTemplate;
use App\Consumers\Models\Consumer;
use App\Consumers\Models\Group;
use Input;
use Config;
use Redirect;
use View;
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

            return [
                'template_id' => $campaign->id,
                'sent' => $sent,
                'total' => count($ids),
            ];
        }

        $campaigns = EmailTemplate::ofCurrentUser()->get();
        $campaignPairs = [];
        foreach ($campaigns as $campaign) {
            $campaignPairs[$campaign->id] = $campaign->subject;
        }

        $targets = [];
        if ($sendGroup) {
            $targets = Group::ofCurrentUser()->whereIn('id', $ids)->get();
        } elseif (!$sendAll) {
            $targets = Consumer::ofCurrentUser()->whereIn('id', $ids)->get();
        }

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

            return [
                'template_id' => $sms->id,
                'sent' => $sent,
                'total' => count($ids),
            ];
        }

        $smsAll = SmsTemplate::ofCurrentUser()->get();
        $smsPairs = [];
        foreach ($smsAll as $sms) {
            $smsPairs[$sms->id] = sprintf(
                '%s (%s: %s)',
                $sms->title,
                trans('co.sms_templates.from_name'),
                $sms->from_name ?: Config::get('sms.from')
            );
        }

        $targets = [];
        if ($sendGroup) {
            $targets = Group::ofCurrentUser()->whereIn('id', $ids)->get();
        } else {
            $targets = $sendAll ? [] : Consumer::ofCurrentUser()->whereIn('id', $ids)->get();
        }

        return [
            'smsPairs' => $smsPairs,
            'targets' => $targets,
            'sendAll' => $sendAll,
        ];
    }
}
