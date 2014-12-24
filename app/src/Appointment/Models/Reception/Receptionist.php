<?php namespace App\Appointment\Models\Reception;

use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\BookingExtraService;
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
    protected $extraServiceIds     = null;
    protected $extraServices       = null;
    protected $extraServicePrice   = 0;
    protected $extraServiceLength  = 0;
    protected $bookingService      = null;
    protected $selectedService     = null;
    protected $baseLength          = null;
    protected $total               = null;
    protected $price               = null;
    protected $employeeId          = null;
    protected $employee            = null;
    protected $plustime            = 0;
    protected $modifyTime          = 0;
    protected $user                = null;
    protected $isRequestedEmployee = false;
    protected $clientIP            = null;
    protected $consumer            = null;
    protected $source              = null;
    protected $status              = null;
    protected $notes               = null;

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

    public function getBookingDate()
    {
        return $this->date;
    }

    public function setStartTime($strStartTime)
    {
        if(empty($strStartTime)) {
            throw new Exception(trans('as.bookings.error.empty_start_time'), 1);
        }

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
        if(empty($serviceId)) {
            throw new Exception(trans('as.bookings.error.service_empty'), 1);
        }

        $this->serviceId = $serviceId;
        $this->service   = Service::ofUser($this->user->id)->find($this->serviceId);
        return $this;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function setServiceTimeId($serviceTimeId)
    {
        $this->serviceTimeId = $serviceTimeId;
        $this->serviceTime   = ($this->serviceTimeId !== 'default')
            ? ServiceTime::ofUser($this->user->id)->find($this->serviceTimeId)
            : null;
        return $this;
    }

    public function getServiceTimeId()
    {
        return $this->serviceTimeId;
    }

    public function setExtraServiceIds($extraServiceIds)
    {

        $this->extraServiceIds = $extraServiceIds;

        if (!empty($this->extraServiceIds)) {
            $this->extraServices = ExtraService::whereIn('id', $extraServiceIds)->get();
        }

        return $this;
    }

    public function setExtraServices()
    {
        $this->extraServices  = BookingExtraService::where('tmp_uuid', $this->uuid)->get();
        return $this;
    }

    public function getExtraServices()
    {
        if(empty($this->extraServices)) {
            $this->setExtraServices();
        }

        return $this->extraServices;
    }

    public function setModifyTime($modifyTime)
    {
        $this->modifyTime = $modifyTime;

        return $this;
    }

    public function setEmployeeId($employeeId)
    {
        $this->employee = Employee::ofUser($this->user->id)->find($employeeId);

        return $this;
    }

    public function getSelectedService()
    {
        if(empty($this->selectedService)) {
            $this->selectedService = ($this->serviceTimeId === 'default')
                ? Service::ofUser($this->user->id)->findOrFail($this->serviceId)
                : ServiceTime::ofUser($this->user->id)->findOrFail($this->serviceTimeId);
        }
        return $this->selectedService;
    }

    public function setBookingService($notNull = false)
    {
        $this->bookingService = (empty($this->bookingId))
            ? BookingService::where('tmp_uuid', $this->uuid)->first()
            : BookingService::where('booking_id', $this->bookingId)->first();

        if(empty($this->bookingService) && $notNull){
            throw new Exception(trans('as.bookings.missing_services'), 1);
        }

        return $this;
    }

    public function getBookingService()
    {
        return $this->bookingService;
    }

    public function setClientIP($ip)
    {
        $this->clientIP = $ip;
        return $this;
    }

    public function getClientIP()
    {
        return $this->clientIP;
    }

    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
        return $this;
    }

    public function getConsumer()
    {
        return $this->consumer;
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = Booking::getStatus($status);
        return $this;
    }

    public function getBaseLength()
    {
        $this->baseLength = $this->getSelectedService()->length;

        return $this->baseLength;
    }

    public function getLength($force = false)
    {
        if(empty($this->total) || $force) {
            $this->plustime = $this->employee->getPlustime($this->serviceId);

            $this->total = ($this->getBaseLength() + $this->modifyTime + $this->plustime);

            if (is_array($this->extraServices)) {
                foreach ($this->extraServices as $extraService) {
                    $this->extraServiceLength = $extraService->length;
                }
            }

            $this->total += $this->extraServiceLength;
        }
        return $this->total;
    }

    public function getEndTime()
    {
        $this->endTime = $this->getStartTime()->copy()->addMinutes($this->getLength());

        return $this->endTime;
    }

    public function validateBookingTotal()
    {
        if ($this->getLength() < 1) {
            throw new Exception(trans('as.bookings.error.empty_total_time'), 1);
        }
        return true;
    }

    public function validateBookingTime()
    {
        $endDay = $this->getStartTime()->copy()->endOfDay();

        if ($this->getStartTime()->lt(Carbon::now())) {
            throw new Exception(trans('as.bookings.error.past_booking'), 1);
        }

        //Check if the overbook end time exceed the current working day.
        if ($this->getEndTime()->gt($endDay)) {
            throw new Exception(trans('as.bookings.error.exceed_current_day'), 1);
        }

        return true;
    }

    public function validateWithEmployeeFreetime()
    {
        //Check if the book overllap with employee freetime
        $isOverllapedWithFreetime = $this->employee->isOverllapedWithFreetime(
            $this->date,
            $this->getStartTime(),
            $this->getEndTime()
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
            $this->uuid,
            $this->date,
            $this->startTime,
            $this->endTime
        );

        if (!$areResourcesAvailable) {
            throw new Exception(trans('as.bookings.error.not_enough_resources'), 1);
        }

        return true;
    }

    public function validateEmployee()
    {
        if(empty($this->employee)) {
            throw new Exception(trans('as.bookings.error.employee_not_found'), 1);
        }
        return true;
    }

    public function upsertBookingService()
    {
        $this->validateData();
        $this->validateBooking();
        $this->validateEmployee();

        $this->setBookingService();

        //TODO validate modify time and service time
        $model = (empty($this->bookingService->id)) ? (new BookingService) : $this->bookingService;

        //Using uuid for retrieve it later when insert real booking
        $model->fill([
            'tmp_uuid'              => $this->uuid,
            'date'                  => $this->date,
            'modify_time'           => $this->modifyTime,
            'start_at'              => $this->getStartTime()->toTimeString(),
            'end_at'                => $this->getEndTime()->toTimeString(),
            'is_requested_employee' => $this->getIsRequestedEmployee()
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

        if(is_array($this->extraServices)) {
            foreach ($this->extraServices as $extraService) {
                $bookingExtraService = new BookingExtraService;
                $bookingExtraService->fill([
                    'date'     => $this->date->toDateTimeString(),
                    'tmp_uuid' => $this->uuid
                ]);

                $bookingExtraService->extraService()->associate($extraService);
                $bookingExtraService->save();
            }
        }

        return $model;
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
        $this->isRequestedEmployee = $isRequestedEmployee;

        return $this;
    }

    public function getIsRequestedEmployee()
    {
        return $this->isRequestedEmployee;
    }

    public function computeTotalPrice()
    {
        //to avoid warning
        if (is_array($this->extraServices)) {
            foreach ($this->extraServices as $extraService) {
                $this->extraServicePrice  += $extraService->price;
            }
        }

        $this->price = $this->getSelectedService()->price + $this->extraServicePrice;

        return $this->price;
    }

    abstract function upsertBooking();
    abstract public function validateData();
    abstract public function validateBooking();

}
