<?php namespace App\Appointment\Models\Reception;

use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use Exception;

abstract class Receptionist implements ReceptionistInterface
{
    protected $bookingId           = null;
    protected $date                = null;
    protected $startTime           = null;
    protected $endTime             = null;
    protected $uuid                = null;
    protected $serviceId           = null;
    protected $service             = null;
    protected $serviceTimeId       = null;
    protected $serviceTime         = null;
    protected $bookingService      = null;
    protected $selectedService     = null;
    protected $baseLength          = null;
    protected $total               = null;
    protected $price               = null;
    protected $employeeId          = null;
    protected $employee            = null;
    protected $plustime            = null;
    protected $modifyTime          = null;
    protected $user                = null;
    protected $isRequestedEmployee = false;

    public function setBookingId($bookingId)
    {
        $this->bookingId = $bookingId;

        return $this;
    }

    public function getBookingId()
    {
        return $this->bookingId;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function setBookingDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function findBookingService()
    {
        $this->bookingService = (empty($this->bookingId))
            ? BookingService::where('tmp_uuid', $this->uuid)->first()
            : BookingService::where('booking_id', $this->bookingId)->first();

        return $this->bookingService;
    }

    public function getBookingDate()
    {
        return $this->date;
    }

    public function setStartTime($strStartTime)
    {
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $this->date, $strStartTime));
        $this->startTime = $startTime;

        return $this;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setUUID($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUUID()
    {
        return $this->uuid;
    }

    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function setServiceTimeId($serviceTimeId)
    {
        $this->serviceTimeId = $serviceTimeId;

        return $this;
    }

    public function getServiceTimeId()
    {
        return $this->serviceTimeId;
    }

    public function setModifyTime(\int $modifyTime)
    {
        $this->modifyTime = $modifyTime;

        return $this;
    }

    public function setEmployeeId($employeeId)
    {
        $this->employee = Employee::ofCurrentUser()->find($employeeId);

        return $this;
    }

    public function setSelectedService()
    {
        $this->selectedService = ($this->serviceTimeId === 'default')
            ? Service::ofCurrentUser()->find($this->serviceId)
            : ServiceTime::ofCurrentUser()->find($this->serviceTimeId);

        return $this;
    }

    public function setBaseLength()
    {
        if(empty($this->selectedService)) {
            $this->setSelectedService();
        }

        $this->baseLength = $this->selectedService->length;

        return $this;
    }

    public function computeLength()
    {
        if(empty($this->baseLength)) {
            $this->setBaseLength();
        }

        $this->plustime = $this->employee->getPlustime($this->serviceId);
        $this->total    = ($this->baseLength + $this->modifyTime + $this->plustime);
        return $this->total;
    }

    public function validateBookingTotal()
    {
        if (empty($this->selectedService)) {
            $this->setBaseLength();
        }

        $this->computeLength();

        if ($this->total < 1) {
            throw new Exception(trans('as.bookings.error.empty_total_time'), 1);
        }

        return true;
    }

    public function validateBookingEndTime()
    {
        if (empty($this->total)) {
            $this->setTotal();
        }

        $this->endTime = $this->startTime->copy()->addMinutes($this->total);
        $endDay        = $this->startTime->copy()->endOfDay();

        if ($this->startTime->lt(Carbon::now())) {
            throw new Exception(trans('as.bookings.error.past_booking'), 1);
        }

        //Check if the overbook end time exceed the current working day.
        if ($this->endTime->gt($endDay)) {
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
        );

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

        if (!$areResourcesAvailable) {
            throw new Exception(trans('as.bookings.error.not_enough_resources'), 1);
        }

        return true;
    }

    public function upsertBookingService()
    {
        //TODO validate modify time and service time
        $model = (empty($this->bookingService)) ? (new BookingService) : $this->bookingService;

        //Using uuid for retrieve it later when insert real booking
        $model->fill([
            'tmp_uuid'              => $this->uuid,
            'date'                  => $this->bookingDate,
            'modify_time'           => $this->modifyTime,
            'start_at'              => $this->date,
            'end_at'                => $endTime,
            'is_requested_employee' => $this->isRequestedEmployee
        ]);

        //there is no method opposite with associate
        $model->service_time_id = null;

        if (!empty($this->serviceTime)) {
            $model->serviceTime()->associate($this->serviceTime);
        }
        $model->service()->associate($this->service);
        $model->user()->associate($this->user);
        $model->employee()->associate($this->employee);
        $model->save();
    }

    public function getResponseData()
    {
        if(empty($this->price)) {
            $this->computeTotalPrice();
        }

        $data = [
            'datetime'      => $this->startTime->toDateTimeString(),
            'price'         => $this->price,
            'service_name'  => $this->service->name,
            'modify_time'   => $this->modifyTime,
            'plustime'      => $this->plustime,
            'employee_name' => $this->employee->name,
            'uuid'          => $this->uuid
        ];
        return $data;
    }

    public function setIsRequestedEmployee($isRequestedEmployee)
    {
        $this->setRequestedEmployee = $isRequestedEmployee;

        return $this;
    }

    public function getIsRequestedEmployee()
    {
        return $this->isRequestedEmployee;
    }

    abstract public function computeTotalPrice();
    abstract public function validateData();
    abstract public function validateBooking();

}
