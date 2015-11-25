<?php
namespace App\Consumers\Models;

use Sms, Config;
use Log;

class SmsTemplate extends \App\Core\Models\Base
{
    public $fillable = [
        'title',
        'content',
        'from_name'
    ];

    protected $table = 'mt_sms';

    protected $rulesets = ['saving' => [
        'title' => 'required',
        'content' => 'required|max:160',
        'from_name' => 'required||max:11|alpha_num',
    ]];

    /**
     * Get all the rulesets.
     *
     * @return array
     */
    public function getRulesets()
    {
        // Custom rule for tobook
        if(is_tobook()) {
            if (!empty($this->rulesets['saving']['content'])) {
                $this->rulesets['saving']['content'] = 'required';
            }
        }

        return $this->rulesets ?: [];
    }


    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function histories()
    {
        return $this->hasMany('App\Consumers\Models\History', 'sms_id');
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function sendConsumers(SmsTemplate $sms, array $consumerIds, Group $group = null)
    {
        $count = 0;

        $sentConsumerIds = $sms->histories()->lists('consumer_id');
        $unsentConsumerIds = [];
        foreach ($consumerIds as $consumerId) {
            if (!in_array($consumerId, $sentConsumerIds)) {
                $unsentConsumerIds[] = $consumerId;
            }
        }
        if (!empty($unsentConsumerIds)) {
            $consumers = $sms->user->consumers()
                ->whereIn('id', $unsentConsumerIds)
                ->where('receive_sms', true)
                ->get();
        } else {
            $consumers = [];
        }

        foreach ($consumers as $consumer) {
            if (empty($consumer->phone) || !(bool)$consumer->receive_sms) {
                continue;
            }

            $from = $sms->from_name ?: Config::get('sms.from');

            Sms::queue($from, $consumer->phone, $sms->content);
            History::quickSave($sms->user, $sms, $consumer, $group);

            $count++;
        }

        return $count;
    }

    public static function sendGroups(SmsTemplate $sms, array $groupIds)
    {
        $count = 0;
        $consumerIds = [];

        $groups = Group::where('user_id', $sms->user_id)
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

            $count += static::sendConsumers($sms, $uniqueConsumerIds, $group);
        }

        return array($count, count($consumerIds));
    }
}
