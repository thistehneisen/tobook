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

    /**
     * This is real start time of an booking
     * because in frontend we don't show the `before` time
     */
    public function getStartTime()
    {
        return $this->startTime->copy()->subMinutes($this->getSelectedService()->before);
    }

    /**
     * This is real end time of an booking
     *  because in frontend we don't show the `after` time
     */
    public function getEndTime()
    {
        $this->endTime = $this->getStartTime()->copy()->addMinutes($this->getLength());
        return $this->endTime;
    }

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
        $this->setBookingService(true);

        $booking = new Booking();

        $booking->fill([
            'date'        => $this->bookingService->date,
            'start_at'    => $this->bookingService->startTime->toTimeString(),
            'end_at'      => $this->bookingService->endTime->toTimeString(),
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

        if($this->getSource() !== 'inhouse') {
            $booking->consumer()->associate($this->consumer);
        }

        $booking->user()->associate($this->user);
        $booking->employee()->associate($this->bookingService->employee);
        $booking->save();

        $this->bookingService->booking()->associate($booking);
        $this->bookingService->is_requested_employee = $this->getIsRequestedEmployee();
        $this->bookingService->save();

        $this->setExtraServices();

        foreach ($this->extraServices as $extraService) {
            $extraService->booking()->associate($booking);
            $extraService->save();
        }

        return $booking;
    }
}
