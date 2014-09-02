<?php namespace App\Appointment\Models;

class Booking extends \App\Core\Models\Base
{
    protected $table = 'as_bookings';

    public $fillable = [
        'date',
        'total',
        'start_at',
        'status',
        'ip',
    ];

    protected function getStatuses(){
        return [
            'confirmed' => trans('as.bookings.confirmed'),
            'pending' => trans('as.bookings.pending'),
            'cancelled' => trans('as.bookings.cancelled')
        ];
    }
    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user(){
        return $this->belongsTo('App\Core\Models\User');
    }

    public function consumer(){
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
