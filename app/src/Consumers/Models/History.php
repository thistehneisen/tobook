<?php
namespace App\Consumers\Models;

use App\Core\Models\User;
use App\Consumers\Models\EmailTemplate;
use App\Consumers\Models\SmsTemplate;
use App\Consumers\Models\Consumer;
use App\Consumers\Models\Group;

class History extends \App\Core\Models\Base
{
    protected $table = 'mt_historys';

    protected $rulesets = ['saving' => []];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function group()
    {
        return $this->belongsTo('App\Consumers\Models\Group');
    }

    public function consumer()
    {
        return $this->belongsTo('App\Consumers\Models\Consumer');
    }

    public function email()
    {
        return $this->belongsTo('App\Consumers\Models\EmailTemplate', 'campaign_id');
    }

    public function sms()
    {
        return $this->belongsTo('App\Consumers\Models\SmsTemplate', 'sms_id');
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * @param User $user
     * @param EmailTemplate|SmsTemplate $template
     * @param Consumer $consumer
     * @param Group $group
     *
     * @return History
     */
    public static function quickSave(User $user, $template, Consumer $consumer, Group $group = null)
    {
        $history = new static();
        $history->user()->associate($user);
        $history->consumer()->associate($consumer);

        if ($template instanceof EmailTemplate) {
            $history->email()->associate($template);
        } elseif ($template instanceof SmsTemplate) {
            $history->sms()->associate($template);
        } else {
            // TODO: add support for Sms
            throw new \InvalidArgumentException('$template must be a valid instance');
        }

        if (!empty($group)) {
            $history->group()->associate($group);
        }

        $history->saveOrFail();

        return $history;
    }
}
