<?php
namespace App\Consumers\Models;

use Mail;

class Sms extends \App\Core\Models\Base
{
    public $fillable = [
        'title',
        'content',
    ];

    protected $table = 'mt_sms';

    protected $rulesets = ['saving' => [
        'title' => 'required',
        'content' => 'required',
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
    public static function sendConsumers(Sms $sms, array $consumerIds, Group $group = null)
    {
        $count = 0;
        $consumers = $sms->user->consumers()
            ->whereIn('id', $consumerIds)
            ->get();

        foreach ($consumers as $consumer) {
            if (empty($consumer->phone)) {
                continue;
            }

            // check for marketing material opt-out maybe?

            // configurable?
            $from = 'varaa.com';

            \Sms::send($from, $consumer->phone, $sms->content);
            History::quickSave($sms->user, $sms, $consumer, $group);

            $count++;
        }

        return $count;
    }
}
