<?php namespace App\Appointment\Models;

class BookingService extends \App\Core\Models\Base
{
    protected $table = 'as_booking_services';

    public $fillable = ['tmp_uuid','date', 'start_at', 'end_at', 'is_reminder_email', 'is_reminder_sms'];
    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function booking(){
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    public function service(){
       return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function serviceTime(){
       return $this->belongsTo('App\Appointment\Models\ServiceTime');
    }

    public function employee(){
       return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function user(){
        return $this->belongsTo('App\Core\Models\User');
    }
}
