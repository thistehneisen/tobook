<?php
namespace App\Consumers\Models;

use App\Core\Models\User;
use App\Consumers\Models\Campaign;
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

    public function campaign()
    {
        return $this->belongsTo('App\Consumers\Models\Campaign');
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * @param User $user
     * @param Campaign $campaignOrSms
     * @param Consumer $consumer
     * @param Group $group
     *
     * @return History
     */
    public static function quickSave(User $user, $campaignOrSms, Consumer $consumer, Group $group = null)
    {
        $history = new static();
        $history->user()->associate($user);
        $history->consumer()->associate($consumer);

        if ($campaignOrSms instanceof Campaign) {
            $history->campaign()->associate($campaignOrSms);
        } else {
            // TODO: add support for Sms
            throw new \InvalidArgumentException('$campaignOrSms must be a valid instance');
        }

        if (!empty($group)) {
            $history->group()->associate($group);
        }

        $history->saveOrFail();

        return $history;
    }
}
