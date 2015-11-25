<?php namespace App\Appointment\Models;

use App\Core\Models\User;
use Carbon\Carbon;
use Config;
use DB;
use Request;
use Settings;
use Util;
use Watson\Validating\ValidationException;
use App;
use Log;

class ConfirmationReminder extends \Eloquent
{
	protected $table = 'as_booking_confirmation_reminders';
    protected $primaryKey = 'booking_id';

    const HOUR = 'hour';
    const DAY  = 'day';

    public $fillable = [
        'is_reminder_sms',
        'reminder_sms_before',
        'reminder_sms_at',
        'is_reminder_email',
        'reminder_email_before',
        'reminder_email_at',
        'is_confirmation_email',
        'is_confirmation_sms',
        'reminder_email_time_unit',
        'reminder_sms_time_unit',
    ];


    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getIsConfirmationEmailAttribute()
    {
        return (bool) $this->attributes['is_confirmation_email'];
    }

    public function getIsConfirmationSmsAttribute()
    {
        return (bool) $this->attributes['is_confirmation_sms'];
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

}