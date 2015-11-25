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
use Queue;
use Sms;
use Mail;

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


    public static function sendReminders()
    {
        $now = Carbon::now();

        $minutes = intval($now->copy()->minute);
        $remove = $minutes % 15;
        $compensate = 15 - $remove;


        $datetime = $now->copy()->addMinutes($compensate)
            ->second(0)
            ->toDateTimeString();

        $reminders = self::where('is_reminder_email', '=', true)
            ->orWhere('is_reminder_sms', '=', true)
            ->where('reminder_email_at', '=', $datetime)
            ->orWhere('reminder_sms_at', '=', $datetime)->get();

        // Loop thourgh bookings and add to queue
        foreach ($reminders as $reminder) {
            Log::info("Send reminder to customer", [$reminder->booking->consumer->email, $reminder->booking->end_at]);

            if ($reminder->isReminderEmail) {
                self::sendEmailReminder($reminder->booking);
            }

            if ($reminder->isReminderSms) {
                self::sendSmsReminder($reminder->booking);
            }
            
        }
    }

    public static function sendSmsReminder($booking)
    {
       $from = Config::get('sms.from');
       $content = $booking->getSmsReminderContent();
       Sms::queue($from, $booking->consumer->phone, $content);
    }

    public static function sendEmailReminder($booking)
    {
        $emailSubject = 'Booking reminder';//TODO
        $body         = $booking->getEmailBody();
        $receiver     = $booking->consumer->email;
        $receiverName = $booking->consumer->name;

        // Enque to send email
        Queue::push(function ($job) use ($emailSubject, $body, $receiver, $receiverName) {
            Mail::send('modules.as.emails.reminder', [
                'title' => $emailSubject,
                'body' => nl2br($body)
            ], function ($message) use ($emailSubject, $receiver, $receiverName) {
                $message->subject($emailSubject);
                $message->to($receiver, $receiverName);
            });
            $job->delete();
        });
    }

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

    public function getIsReminderEmailAttribute()
    {
        return (bool) $this->attributes['is_reminder_email'];
    }

    public function getIsReminderSmsAttribute()
    {
        return (bool) $this->attributes['is_reminder_sms'];
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

}