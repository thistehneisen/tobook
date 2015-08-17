<?php namespace App\Appointment\Listeners;

use App, View, Mail, Log;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;

class EmployeeCalendarInvidationListener
{
    /**
     * Send calendar inviation to employee
     *
     * @param App\Appointment\Models\Booking $booking
     *
     * @return void
     */
    public function handle($booking)
    {
        //If booking is not valid
        if (!($booking instanceof Booking)) {
            return;
        }

        $emailSubject   = $booking->user->asOptions['confirm_subject_employee'];
        $body           = $booking->getEmailBody();
        $icsFile        = $booking->generateIcsFile();
        $email['title'] = $emailSubject;
        $email['body']  = nl2br($body);
        $employee       = $booking->firstBookingService()->employee;

        if ($employee->is_received_calendar_invitation) {
            Mail::send('modules.as.emails.confirm', $email, function ($message) use ($employee, $icsFile, $emailSubject) {
                $message->to($employee->email, $employee->name)->subject($emailSubject);
                $message->attach($icsFile, array('mime' => "text/calendar"));
            });
        }
        unlink($icsFile);
    }
}
