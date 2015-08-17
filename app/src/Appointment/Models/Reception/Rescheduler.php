<?php namespace App\Appointment\Models\Reception;

use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use Exception;

class Rescheduler extends Receptionist
{
    private $prefix = 'replicate_';
    protected $timeGap;//different in minutes between the re-arrangement (no date account)
    protected $newTime;
    protected $booking;
    protected $strStartTime;

    public function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;
        $this->booking = Booking::findOrFail($bookingId);

        return $this;
    }

    public function setStartTime($strStartTime)
    {
        if (empty($strStartTime)) {
            throw new Exception(trans('as.bookings.error.empty_start_time'), 1);
        }

        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $this->date, $strStartTime));
        $this->strStartTime = $strStartTime;
        $this->startTime = $startTime;
        $this->bookingStartTime = $startTime;

        return $this;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function calculateTimeGap()
    {
        //The gap can be big, due to booking can be reschedule in different day
        $this->newTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $this->booking->date, $this->strStartTime));
        //calculate the different in minutes between old time and new time, no date consideration
        $this->timeGap = $this->booking->startTime->diffInMinutes($this->newTime);

        return $this->timeGap;
    }

    private function getNewStartEndTime($bookingService)
    {
        $newStartTime = ($this->booking->startTime < $this->newTime)
            ? (new \Carbon\Carbon($this->date . ' ' . $bookingService->start_at))->addMinutes($this->timeGap)
            : (new \Carbon\Carbon($this->date . ' ' . $bookingService->start_at))->subMinutes($this->timeGap);

        $newEndTime = ($this->booking->startTime < $this->newTime)
            ? (new \Carbon\Carbon($this->date . ' ' . $bookingService->end_at))->addMinutes($this->timeGap)
            : (new \Carbon\Carbon($this->date . ' ' . $bookingService->end_at))->subMinutes($this->timeGap);

        return [$newStartTime, $newEndTime];
    }

    public function checkingNewBookingServices()
    {
        $bookingServices = BookingService::where('tmp_uuid', $this->uuid)
            ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->get();

        foreach ($bookingServices as $bookingService) {
            list($newStartTime, $newEndTime) = $this->getNewStartEndTime($bookingService);

            $this->validateWithEmployeeFreetime($newStartTime, $newEndTime);
            $this->validateWithResources($newStartTime, $newEndTime);
            $this->validateWithRooms($bookingService, $newStartTime, $newEndTime);
        }
    }

    public function rescheduleBookingServices()
    {
        $bookingServices = BookingService::where('tmp_uuid', $this->uuid)
            ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->get();

        foreach ($bookingServices as $bookingService) {
            list($newStartTime, $newEndTime) = $this->getNewStartEndTime($bookingService);

            $bookingService->start_at = $newStartTime->toTimeString();
            $bookingService->end_at = $newEndTime->toTimeString();
            $bookingService->date = $this->date;
            $bookingService->employee()->associate($this->employee);
            $bookingService->save();
        }

        $bookingExtraServices = BookingExtraService::where('tmp_uuid', $this->uuid)->whereNull('deleted_at')->get();
        foreach ($bookingExtraServices as $bookingExtraService) {
            $bookingExtraService->date = $this->date;
            $bookingExtraService->save();
        }
    }

    public function validateWithEmployeeFreetime($startTime = null, $endTime = null)
    {
        //Check if the book overllap with employee freetime
        $isOverllapedWithFreetime = $this->employee->isOverllapedWithFreetime(
            $this->date,
            $startTime,
            $endTime
        );

        if ($isOverllapedWithFreetime) {
            throw new \Exception(trans('as.bookings.error.overllapped_with_freetime'), 1);
        }

        return true;
    }

    public function validateWithResources($startTime = null, $endTime = null)
    {
        $areResourcesAvailable = Booking::areResourcesAvailable(
            $this->employeeId,
            $this->service,
            $this->uuid,
            $this->date,
            $startTime,
            $endTime
        );

        if (!$areResourcesAvailable) {
            throw new \Exception(trans('as.bookings.error.not_enough_resources'), 1);
        }

        return true;
    }

    public function validateWithRooms($bookingService = null, $startTime = null, $endTime = null)
    {
        if ($bookingService->service->requireRoom()) {
            $availableRoom = Booking::getAvailableRoom(
                $this->employeeId,
                $bookingService->service,
                $this->uuid,
                $this->date,
                $startTime,
                $endTime
            );

            if (empty($availableRoom->id)) {
                throw new \Exception(trans('as.bookings.error.not_enough_rooms'), 1);
            }

            $this->roomId = $availableRoom->id;
        }

        return true;
    }

    public function validateWithExistingBooking($startTime = null, $endTime = null)
    {
        //Check is there any existed booking with this service time
        $isBookable = Booking::isBookable(
            $this->employeeId,
            $this->date,
            $startTime,
            $endTime,
            $this->uuid
        );

        if (!$isBookable) {
            throw new \Exception(trans('as.bookings.error.add_overlapped_booking'), 1);
        }

        return true;
    }

    public function reschedule()
    {
        $this->endTime = $this->startTime->copy()->addMinutes($this->booking->total);
        $this->validateWithExistingBooking($this->startTime, $this->endTime);
        $this->calculateTimeGap();
        $this->checkingNewBookingServices();
        $this->rescheduleBookingServices();
        $this->booking->start_at = $this->startTime->toTimeString();
        $this->booking->end_at   = $this->endTime->toTimeString();
        $this->booking->date     = $this->date;

        $this->booking->employee()->associate($this->employee);
        $this->booking->save();

        return $this->booking;
    }

    public function upsertBooking()
    {
        throw new \Exception('Not implemented');
    }

    public function validateData()
    {
        throw new \Exception('Not implemented');
    }

    public function validateBooking()
    {
        throw new \Exception('Not implemented');
    }
}
