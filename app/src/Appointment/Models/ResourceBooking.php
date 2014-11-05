<?php namespace App\Appointment\Models;

class ResourceBooking extends \Eloquent
{
    public $timestamps = false;
    protected $table = 'as_resource_booking';

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
