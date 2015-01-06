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

    public static function makeOrGet($consumer, $userId)
    {
        if ($consumer->lc !== null) {
            return $consumer->lc;
        }

        $lcConsumer = new self();
        $lcConsumer->total_points = 0;
        $lcConsumer->total_stamps = '';
        $lcConsumer->consumer_id = $consumer->id;
        $lcConsumer->user_id = $userId;
        $lcConsumer->save();

        return $lcConsumer;
    }
}
