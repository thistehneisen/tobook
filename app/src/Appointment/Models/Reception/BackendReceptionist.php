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

    /**
     * Using for update booking service times where user delete one of them
     */
    public function updateBookingServicesTime()
    {
        $first = BookingService::where('tmp_uuid', $this->uuid)
            ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->first();

        if(!empty($first)) {
            $first->start_at = $this->startTime->toTimeString();
            $first->end_at = $first->startTime->copy()->addMinutes($first->calculcateTotalLength())->toTimeString();
            $first->save();

            $bookingServices = BookingService::where('tmp_uuid', $this->uuid)
                ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->get();

            $previousBookingService = [];
            $lastBookingService = null;
            //Update the sequence of booking service
            //The next start time = the previous end time
            foreach ($bookingServices as $bookingService) {
                if(empty($previousBookingService)){
                    $previousBookingService[] = $bookingService;
                    continue;
                } else {
                    $lastBookingService = $previousBookingService[count($previousBookingService) - 1];
                }

                if(!empty($lastBookingService)){
                    $bookingService->start_at = $lastBookingService->endTime->toTimeString();
                    $bookingService->end_at = $lastBookingService->endTime
                        ->copy()->addMinutes($bookingService->calculcateTotalLength())->toTimeString();
                    $bookingService->save();
                }
            }
        }
    }

    public function calculateMultipleBookingServices()
    {
        $startTime = $endTime = $plustime = $totalPrice = $totalLength = null;

        foreach ($this->bookingServices as $bookingService) {
            $totalLength += $bookingService->calculcateTotalLength();
            $totalPrice  += $bookingService->calculcateTotalPrice();
            $plustime    += $bookingService->getEmployeePlustime();
        }
        $totalLength += $this->modifyTime;

        $date             = $this->bookingServices->first()->date;
        $this->startTime  = $startTime = $this->bookingServices->first()->startTime;
        $this->endTime    = $endTime   = $this->bookingServices->last()->endTime->copy()->addMinutes($this->modifyTime);
        $this->date       = $this->bookingServices->first()->startTime->toDateString();
        $this->employeeId = $this->bookingServices->first()->employee_id;

        if($endTime <= $startTime) {
            throw new \Exception(trans('as.bookings.error.empty_total_time'), 1);
        }
        return array($date, $startTime, $endTime, $plustime, $totalLength, $totalPrice);
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

        list($date, $startTime, $endTime, $plustime, $totalLength, $totalPrice) = $this->calculateMultipleBookingServices();

        $this->validateWithExistingBooking();

        $booking->fill([
            'date'        => $date,
            'start_at'    => $startTime->toTimeString(),
            'end_at'      => $endTime->toTimeString(),
            'total'       => $totalLength,
            'total_price' => $totalPrice,
            'status'      => $this->status,
            'notes'       => $this->notes,
            'uuid'        => $this->uuid,
            'modify_time' => $this->modifyTime,
            'plustime'    => $plustime,
            'ip'          => $this->getClientIp(),
            'source'      => $this->getSource()
        ]);

        $booking->consumer()->associate($this->consumer);
        $booking->user()->associate($this->user);
        $booking->employee()->associate($this->employee);

        if ((int) $this->status === Booking::STATUS_CANCELLED){
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
