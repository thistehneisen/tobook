<?php namespace App\Appointment\Models;
use Carbon\Carbon;

class BookingService extends \App\Core\Models\Base
{
    protected $table = 'as_booking_services';

    public $fillable = [
        'tmp_uuid',
        'date',
        'start_at',
        'end_at',
        'modify_time',
        'service_time_id',
        'is_reminder_email',
        'is_reminder_sms'
    ];


    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    //TODO convert string to Carbon object
    // public function getStartAtAttribute()
    // {

    // }

    // public function getEndAtAttribute()
    // {

    // }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    public function service()
    {
       return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function serviceTime()
    {
       return $this->belongsTo('App\Appointment\Models\ServiceTime');
    }

    public function employee()
    {
       return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
