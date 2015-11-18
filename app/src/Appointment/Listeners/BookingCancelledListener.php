<?php namespace App\Appointment\Listeners;

use App, View, Mail, Log;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;

class BookingCancelledListener
{
   
    public function handle($booking)
    {
        //If booking is not valid
        if (!($booking instanceof Booking)) {
            return;
        }

        $emailSubject   = trans('as.bookings.cancel_email_title');
        $body           = $booking->getCancelInfo();

        $email['title'] = $emailSubject;
        $email['body']  = nl2br($body);
        $employee       = $booking->firstBookingService()->employee;
        $consumer       = $booking->consumer;
    
        Mail::send('modules.as.emails.cancel', $email, function ($message) use ($employee, $emailSubject) {
            $message->to($employee->email, $employee->name)->subject($emailSubject);
        });

        Mail::send('modules.as.emails.cancel', $email, function ($message) use ($consumer, $emailSubject) {
            $message->to($consumer->email, $consumer->name)->subject($emailSubject);
        });
        
    }
}
