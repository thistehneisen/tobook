<?php namespace App\Appointment\Models\Reception;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\Consumer;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;
use Exception;

class FrontendReceptionist extends Receptionist
{
    public function validateData()
    {
        $this->validateBookingTotal();
        $this->validateBookingTime();
    }

    public function validateBooking()
    {
        $this->validateWithEmployeeFreetime();
        $this->validateWithExistingBooking();
        $this->validateWithResources();
    }

    public function upsertBooking()
    {
        $this->setBookingService();

        $booking = new Booking();

        $booking->fill([
            'date'        => $this->bookingService->date,
            'start_at'    => $this->bookingService->startTime,
            'end_at'      => $this->bookingService->endTime,
            'total'       => $this->bookingService->calculcateTotalLength(),
            'total_price' => $this->bookingService->calculcateTotalPrice(),
            'uuid'        => $this->bookingService->tmp_uuid,
            'modify_time' => $this->bookingService->modify_time,
            'plustime'    => $this->bookingService->getEmployeePlustime(),
            'notes'       => $this->notes,
            'status'      => Booking::STATUS_CONFIRM,
            'ip'          => $this->getClientIp(),
            'source'      => $this->getSource()
        ]);

        $booking->consumer()->associate($this->consumer);
        $booking->user()->associate($this->user);
        $booking->employee()->associate($this->bookingService->employee);
        $booking->save();

        $this->bookingService->booking()->associate($booking);
        $this->bookingService->is_requested_employee = $this->isRequestedEmployee;
        $this->bookingService->save();

        $this->setExtraServices();

        foreach ($this->extraServices as $extraService) {
            $extraService->booking()->associate($booking);
            $extraService->save();
        }

        //Send notification email and SMSs
        $booking->attach(new EmailObserver());
        $booking->attach(new SmsObserver());
        $booking->notify();

        return $booking;
    }
}
