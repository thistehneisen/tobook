<?php namespace App\Appointment\Models\Reception;

use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
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
        $this->validateWithRooms();
    }

    public function calculateMultipleBookingServices()
    {
        $startTime = $endTime = $modifyTime = $plustime = $totalPrice = $totalLength = null;

        foreach ($this->bookingServices as $bookingService) {
            $totalLength = $bookingService->calculcateTotalLength();
            $totalPrice  = $bookingService->calculcateTotalPrice();
            $modifyTime  = $bookingService->modify_time;
            $plustime    = $bookingService->getEmployeePlustime();
        }

        $date      = $this->bookingServices->first()->date;
        $startTime = $this->bookingServices->first()->startTime;
        $endTime   = $this->bookingServices->last()->endTime;

        return array($date, $startTime, $endTime, $modifyTime, $plustime, $totalLength, $totalPrice);
    }

    public function upsertBooking()
    {
        $this->setBookingService();
        $this->validateEmptyBookingService();

        $booking = (empty($this->bookingId))
            ? new Booking()
            : Booking::find($this->bookingId);

        $this->employee = $this->bookingService->employee;
        $this->extraServices = (!empty($this->bookingId))
            ? $booking->extraServices
            : array();

        list($date, $startTime, $endTime, $modifyTime, $plustime, $totalLength, $totalPrice) = $this->calculateMultipleBookingServices();

        $booking->fill([
            'date'        => $date,
            'start_at'    => $startTime->toTimeString(),
            'end_at'      => $endTime->toTimeString(),
            'total'       => $totalLength,
            'total_price' => $totalPrice,
            'status'      => $this->status,
            'notes'       => $this->notes,
            'uuid'        => $this->uuid,
            'modify_time' => $modifyTime,
            'plustime'    => $plustime,
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
        foreach ($this->bookingServices as $bookingService) {
            $bookingService->is_requested_employee = $this->isRequestedEmployee;
            $bookingService->booking()->associate($booking);
            $bookingService->save();
        }

        //Don't send sms when update booking
        if(empty($this->bookingId)){
            //Only can send sms after insert booking service
            $booking->attach(new SmsObserver(true));//true is backend
            $booking->notify();
        }
        return $booking;
    }
}
