<?php namespace App\Appointment\Models;

class BookingServiceRoom
{
    public $timestamps = false;
    protected $table = 'as_booking_service_rooms';


    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function bookingService()
    {
        return $this->belongsTo('App\Appointment\Models\BookingService');
    }

    public function room()
    {
        return $this->belongsTo('App\Appointment\Models\Room');
    }

}
