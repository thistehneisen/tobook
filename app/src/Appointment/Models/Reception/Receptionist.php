<?php namespace App\Appointment\Models\Reception;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Resource;

abstract class Receptionist implements IReceptionist
{
    protected $id                  = null;
    protected $date                = null;
    protected $startTime           = null;
    protected $endTime             = null;
    protected $uuid                = null;
    protected $serviceId           = null;
    protected $serviceTimeId       = null;
    protected $selectedService     = null;
    protected $baseLength          = null;
    protected $total               = null;
    protected $employeeId          = null;
    protected $employee            = null;
    protected $uuid                = null
    protected $isRequestedEmployee = false;

    public function setBookingId(int $bookingId)
    {
        $this->id = $bookingId;
        return $this;
    }

    public function getBookingId()
    {
        return $this->id;
    }

    public function setBookingDate(string $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getBookingDate()
    {
        return $this->date;
    }

    public function setStartTime(string $strStartTime)
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $this->date, $strStartTime));
        $this->startTime = $startTime;
        return $this;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setUUID(string $uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function setServiceId(int $serviceId)
    {
        $this->serviceId = $serviceId;
        return $this;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function setServiceTimeId(int $serviceTimeId)
    {
        $this->serviceTimeId = $serviceTimeId;
        return $this;
    }

    public function getServiceTimeId()
    {
        return $this->serviceTimeId;
    }

    public function setBaseLength()
    {
        $this->selectedService = ($this->serviceTimeId === 'default')
            ? Service::ofCurrentUser()->find($$this->serviceId)
            : ServiceTime::find($this->serviceTimeId);

        $this->baseLength = $this->selectedService->length;

        return $this;
    }

    public function setTotal()
    {
        $plustime = $this->employee->getPlustime($this->serviceId);
        $this->total = ($this->baseLength + $this->modifyTime + $plustime);
        return $this->total;
    }

    public function validateBookingTotal()
    {
        if (empty($this->selectedService)) {
            $this->setBaseLength();
        }

        $this->setTotal();

        if ($this->total < 1) {
            throw new Exception(trans('as.bookings.error.empty_total_time'), 1);
        }
        return true;
    }

    public function validateBookingEndTime()
    {
        if(empty($this->total)) {
            $this->setTotal();
        }

        $this->endTime = $this->startTime->copy()->addMinutes($this->total);
        $endDay        = $this->startTime->copy()->endOfDay();

        if($this->startTime->lt(Carbon::now())) {
            throw new Exception(trans('as.bookings.error.past_booking'), 1);
        }

        //Check if the overbook end time exceed the current working day.
        if($this->endTime->gt($endDay)) {
            throw new Exception(trans('as.bookings.error.exceed_current_day'), 1);
        }

        return true;
    }

    public function validateWithEmployeeFreetime()
    {
        //Check if the book overllap with employee freetime
        $isOverllapedWithFreetime = $employee->isOverllapedWithFreetime(
            $this->date,
            $this->startTime,
            $this->endTime
        )
        ;
        if ($isOverllapedWithFreetime) {
            throw new Exception(trans('as.bookings.error.overllapped_with_freetime'), 1);
        }
        return true;
    }

    public function validateWithExistingBooking()
    {
        //Check is there any existed booking with this service time
        $isBookable = Booking::isBookable(
            $this->employeeId,
            $this->date,
            $this->startTime,
            $this->endTime,
            $this->uuid
        );

        if (!$isBookable) {
            throw new Exception(trans('as.bookings.error.add_overlapped_booking'), 1);
        }
        return true;
    }

    public function validateWithResources()
    {
        $areResourcesAvailable = Booking::areResourcesAvailable(
            $this->employeeId,
            $this->service,
            $this->date,
            $this->startTime,
            $this->endTime
        );

        if(!$areResourcesAvailable) {
            throw new Exception(trans('as.bookings.error.not_enough_resources'), 1);
        }
        return true;
    }

    public function setIsRequestedEmployee(bool $isRequestedEmployee)
    {
        $this->setRequestedEmployee = $isRequestedEmployee;
        return $this;
    }

    public function getIsRequestedEmployee()
    {
        return $this->isRequestedEmployee;
    }


    public abstract function computeLength();

    public abstract function validateData();
    public abstract function validateBooking();

}
