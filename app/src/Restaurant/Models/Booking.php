<?php namespace App\Restaurant\Models;

class Booking extends \App\Core\Models\Base
{
    protected $table = 'rb_bookings';
    public $fillable = ['uuid', 'date', 'start_at', 'end_at', 'total', 'status', 'is_group_booking', 'source', 'note'];
    protected $rulesets = [
        'saving'    => [

        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
