<?php namespace App\Appointment\Models;

class BookingResource extends \Eloquent
{
    public $timestamps = false;
    protected $table = 'as_booking_resource';

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function resource()
    {
        return $this->belongsTo('App\Appointment\Models\Resource');
    }

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }
}
