<?php
namespace App\Consumers\Models;

use Mail;

class Campaign extends \App\Core\Models\Base
{
    public $fillable = [
        'subject',
        'content',
        'from_email',
        'from_name',
    ];

    protected $table = 'mt_campaign';

    protected $rulesets = ['saving' => [
        'subject' => 'required',
        'content' => 'required',
        'from_email' => 'required|email',
        'from_name' => 'required',
    ]];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function histories()
    {
        return $this->hasMany('App\Consumers\Models\History');
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function sendConsumers(Campaign $campaign, array $consumerIds, Group $group = null)
    {
        $count = 0;

        $sentConsumerIds = $campaign->histories()->lists('consumer_id');
        $unsentConsumerIds = [];
        foreach ($consumerIds as $consumerId) {
            if (!in_array($consumerId, $sentConsumerIds)) {
                $unsentConsumerIds[] = $consumerId;
            }
        }
        if (!empty($unsentConsumerIds)) {
            $consumers = $campaign->user->consumers()
                ->whereIn('id', $unsentConsumerIds)
                ->get();
        } else {
            $consumers = [];
        }

        foreach ($consumers as $consumer) {
            if (empty($consumer->email)) {
                continue;
            }

            // check for marketing material opt-out maybe?

            Mail::send('modules.co.campaigns.email', [
                'subject' => $campaign->subject,
                'content' => $campaign->content,
            ], function ($message) use ($campaign, $consumer, $group) {
                $message->from($campaign->from_email, $campaign->from_name);
                $message->subject($campaign->subject);
                $message->to($consumer->email, $consumer->name);

                History::quickSave($campaign->user, $campaign, $consumer, $group);
            });

            $count++;
        }

        return $count;
    }

    public static function sendGroups(Campaign $campaign, array $groupIds)
    {
        $count = 0;
        $consumerIds = [];

        $groups = Group::where('user_id', $campaign->user_id)
            ->whereIn('id', $groupIds)
            ->get();

        foreach ($groups as $group) {
            $groupConsumerIds = $group->consumers->lists('id');
            $uniqueConsumerIds = [];

            foreach ($groupConsumerIds as $consumerId) {
                if (isset($consumerIds[$consumerId])) {
                    continue;
                }

                $uniqueConsumerIds[] = $consumerId;
                $consumerIds[$consumerId] = $group->id;
            }

            $count += static::sendConsumers($campaign, $uniqueConsumerIds, $group);
        }

        return array($count, count($consumerIds));
    }
}
