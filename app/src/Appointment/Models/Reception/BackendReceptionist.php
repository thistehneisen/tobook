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

class BackendReceptionist extends Receptionist
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

        $booking = (empty($this->bookingId))
            ? new Booking()
            : Booking::find($this->bookingId);

        $this->employee = $this->bookingService->employee;
        $this->extraServices = (!empty($this->bookingId))
            ? $booking->extraServices
            : array();

        $booking->fill([
            'date'        => $this->bookingService->date,
            'start_at'    => $this->bookingService->startTime,
            'end_at'      => $this->bookingService->endTime,
            'total'       => $this->bookingService->calculcateTotalLength(),
            'total_price' => $this->bookingService->calculcateTotalPrice(),
            'status'      => $this->status,
            'notes'       => $this->notes,
            'uuid'        => $this->uuid,
            'modify_time' => $this->bookingService->modify_time,
            'plustime'    => $this->bookingService->getEmployeePlustime(),
            'ip'          => $this->getClientIp(),
            'source'      => $this->getSource()
        ]);

        $booking->consumer()->associate($this->consumer);
        $booking->user()->associate($this->user);
        $booking->employee()->associate($this->employee);
        if($this->status === Booking::STATUS_CANCELLED){
            $booking->delete_reason = 'Cancelled while updating';
            $booking->save();
            $booking->delete();
        } else {
            $booking->save();
        }
        //Users can check this option before or after save a booking service
        $this->bookingService->is_requested_employee = $this->isRequestedEmployee;
        $this->bookingService->booking()->associate($booking);
        $this->bookingService->save();

        //Don't send sms when update booking
        if(empty($this->bookingId)){
            //Only can send sms after insert booking service
            $booking->attach(new SmsObserver(true));//true is backend
            $booking->notify();
        }
        return $booking;
    }
}
