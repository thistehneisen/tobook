<?php namespace App\LoyaltyCard\Models;

use App\Core\Models\Base;

class Consumer extends Base
{
    protected $table = 'lc_consumers';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public $fillable = ['total_points', 'total_stamps'];

    public function consumer()
    {
        return $this->belongsTo('App\Consumers\Models\Consumer');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public static function make($consumerId, $userId)
    {
        $consumer = new self();
        $consumer->total_points = 0;
        $consumer->total_stamps = '';
        $consumer->consumer_id = $consumerId;
        $consumer->user_id = $userId;
        $consumer->save();

        return $consumer;
    }
}
