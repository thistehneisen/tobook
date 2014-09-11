<?php namespace App\Appointment\Models;

class BookingExtraService extends \App\Core\Models\Base
{
    protected $table = 'as_booking_extra_services';

    public $fillable = [
        'date',
        'tmp_uuid',
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function extraService()
    {
        return $this->belongsTo('App\Appointment\Models\ExtraService');
    }

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }
}
