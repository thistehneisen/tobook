<?php namespace App\Appointment\Models\Reception;

use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Room;
use App\Appointment\Models\BookingServiceRoom;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ConfirmationReminder;
use App\Core\Models\Coupon;
use App\Core\Models\CouponBooking;

use Exception;

abstract class Receptionist implements ReceptionistInterface
{
    protected $bookingId             = null;
    protected $date                  = null;
    protected $startTime             = null;
    protected $bookingStartTime      = null;
    protected $endTime               = null;
    protected $uuid                  = null;
    protected $serviceId             = null;
    protected $service               = null;
    protected $serviceTimeId         = null;
    protected $serviceTime           = null;
    protected $extraServiceIds       = null;
    protected $extraServices         = null;
    protected $extraServicePrice     = 0;
    protected $extraServiceLength    = 0;
    protected $bookingService        = null;
    protected $bookingServices       = null;
    protected $bookingServiceId      = null;
    protected $selectedService       = null;
    protected $baseLength            = null;
    protected $total                 = null;
    protected $price                 = null;
    protected $employeeId            = null;
    protected $employee              = null;
    protected $plustime              = 0;
    protected $modifyTime            = 0;
    protected $user                  = null;
    protected $isRequestedEmployee   = false;
    protected $clientIP              = null;
    protected $consumer              = null;
    protected $source                = null;
    protected $isReminderSms         = 0;
    protected $reminderSmsAt         = null;
    protected $reminderSmsBefore     = null;
    protected $isReminderEmail       = 0;
    protected $reminderSmsTimeUnit   = null;
    protected $isConfirmationEmail   = 0;
    protected $reminderEmailTimeUnit = null;
    protected $reminderEmailBefore   = null;
    protected $reminderEmailAt       = null;
    protected $isConfirmationSms     = 0;
    protected $status                = null;
    protected $notes                 = null;
    protected $roomId                = null;
    protected $layout                = null;
    protected $coupon                = '';

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
        if (empty($strStartTime)) {
            throw new Exception(trans('as.bookings.error.empty_start_time'), 1);
        }
        // local format date is converted to standard format Y-m-d already
        // see the function setBookingDate
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $this->date, $strStartTime));
        $this->startTime = $startTime;
        $this->bookingStartTime = $startTime;

        return $this;
    }

    public function getStartTime()
    {
        if (!empty($this->bookingServices) && !$this->bookingServices->isEmpty()) {
            if (!empty($this->bookingServiceId)) {
                $previousBookingService = null;
                foreach ($this->bookingServices as $bookingService) {
                    if (intval($bookingService->id) === $this->bookingServiceId) {
                        break;
                    } else {
                        $previousBookingService = $bookingService;
                    }
                }

                if (!empty($previousBookingService) && $this->bookingServiceId !== $previousBookingService->id) {
                    $this->startTime = $previousBookingService->endTime;
                }
            } else {
                $this->startTime =  ($this->bookingServices->last())
                    ? $this->bookingServices->last()->endTime
                    : $this->startTime;
            }
        }

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
        if (empty($serviceId)) {
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
        $this->extraServices  = BookingExtraService::where('tmp_uuid', $this->uuid)->whereNull('deleted_at')->get();

        return $this;
    }

    public function getExtraServices()
    {
        if (empty($this->extraServices)) {
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
        $this->employeeId = $employeeId;
        $this->employee   = Employee::ofUser($this->user->id)->find($employeeId);

        return $this;
    }

    public function getSelectedService()
    {
        if (empty($this->selectedService)) {
            $this->selectedService = ($this->serviceTimeId === 'default')
                ? Service::ofUser($this->user->id)->findOrFail($this->serviceId)
                : ServiceTime::ofUser($this->user->id)->findOrFail($this->serviceTimeId);
        }

        return $this->selectedService;
    }

    public function setBookingServiceId($bookingServiceId)
    {
        $this->bookingServiceId = $bookingServiceId;

        return $this;
    }

    public function setBookingService()
    {
        if (!empty($this->bookingServiceId)) {
            $this->bookingService = BookingService::find($this->bookingServiceId);
        }

        $this->bookingServices = BookingService::where('tmp_uuid', $this->uuid)
            ->whereNull('deleted_at')->orderBy('start_at')->get();

        return $this;
    }

    public function validateEmptyBookingService()
    {
        $this->bookingService = BookingService::where('tmp_uuid', $this->uuid)
            ->whereNull('deleted_at')
            ->orderBy('start_at')->first();

        if (empty($this->bookingService)) {
            throw new Exception(trans('as.bookings.error.missing_services'), 1);
        }
    }

    public function getBookingService()
    {
        return $this->bookingService;
    }

    public function getBookingServices()
    {
        return $this->bookingServices;
    }

    public function setClientIP($ip)
    {
        $this->clientIP = $ip;

        return $this;
    }


    //--------------------------------------------------------------------------
    // SMS REMINDER
    //--------------------------------------------------------------------------
    public function setIsReminderSms($value)
    {
        $this->isReminderSms = $value;

        return $this;
    }

    public function setReminderSmsAt()
    {
        if(!empty($this->startTime)) {
            if($this->reminderSmsTimeUnit == ConfirmationReminder::DAY){
                $this->reminderSmsAt = $this->startTime->copy()->subDays($this->reminderSmsBefore);
            } elseif ($this->reminderSmsTimeUnit == ConfirmationReminder::HOUR) {
                $this->reminderSmsAt = $this->startTime->copy()->subHours($this->reminderSmsBefore);
            }
            
        }

        return $this;
    }

    public function setReminderSmsUnit($value)
    {
        $this->reminderSmsTimeUnit = $value;

        return $this;
    }

    public function setReminderSmsBefore($value)
    {
        $this->reminderSmsBefore = $value;

        return $this;
    }

    public function setIsConfimationSms($value)
    {
        $this->isConfirmationSms = $value;

        return $this;
    }


    //--------------------------------------------------------------------------
    // EMAIL REMINDER
    //--------------------------------------------------------------------------

    public function setIsReminderEmail($value)
    {
        $this->isReminderEmail = $value;
        
        return $this;
    }

    public function setReminderEmailUnit($value)
    {
        $this->reminderEmailTimeUnit = $value;

        return $this;
    }

    public function setReminderEmailAt()
    {
        if(!empty($this->startTime)) {
            if($this->reminderEmailTimeUnit == ConfirmationReminder::DAY){
                $this->reminderEmailAt = $this->startTime->copy()->subDays($this->reminderEmailBefore);
            } elseif ($this->reminderEmailTimeUnit == ConfirmationReminder::HOUR) {
                $this->reminderEmailAt = $this->startTime->copy()->subHours($this->reminderEmailBefore);
            }
            
        }

        return $this;
    }

    public function setReminderEmailBefore($value)
    {
        $this->reminderEmailBefore = $value;

        return $this;
    }

    public function setIsConfirmationEmail($value)
    {
        $this->isConfirmationEmail = $value;

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

    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setCoupon($code)
    {
        $this->coupon = $code;
        return $this;
    }

    public function getCoupon()
    {
        return $this->coupon;
    }

    public function getRoomId()
    {
        return $this->roomId;
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
        if (empty($this->total) || $force) {
            $this->plustime = $this->employee->getPlustime($this->serviceId);

            $this->total = ($this->getBaseLength() + $this->modifyTime + $this->plustime);

            //To prevent Exception: Invalid argument supplied for foreach()
            if (!empty($this->extraServices)) {
                foreach ($this->extraServices as $extraService) {
                    $this->extraServiceLength += $extraService->length;
                }
            }

            $this->total += $this->extraServiceLength;
        }

        return $this->total;
    }

    public function getExtraServicesLength()
    {

        $this->setExtraServices();

        $this->extraServiceLength = 0;
        if (!empty($this->extraServices)) {
            foreach ($this->extraServices as $extraService) {
                $this->extraServiceLength += $extraService->length;
            }
        }

        return $this->extraServiceLength;
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

    /**
     * Check if booking time is valid within possible custom time
     * @see https://github.com/varaa/varaa/issues/740
     */
    public function isNotValidWithCustomTime()
    {
        //Check if the book overllap with custom time
        $isNotValidWithCustomTime = $this->employee->isNotValidWithCustomTime(
            $this->date,
            $this->getStartTime(),
            $this->getEndTime()
        );

        if ($isNotValidWithCustomTime) {
            throw new Exception(trans('as.bookings.error.invalid_with_custom_time'), 1);
        }

        return true;
    }

    public function validateWithExistingBooking()
    {
        //upsert booking service
        if (!empty($this->serviceId)) {
            $bookingServicesCount = BookingService::where('tmp_uuid', $this->uuid)
                ->whereNull('deleted_at')->orderBy('start_at', 'ASC')->count();
            if ($bookingServicesCount == 0) {
                $this->endTime->addMinutes($this->modifyTime);
            }
        }

        if (!empty($this->bookingService) && (
            empty($this->employeeId) || empty($this->date) 
          || empty($this->startTime) || empty($this->endTime)))
        {
            $this->employeeId = $this->bookingService->employee->id;
            $this->date       = $this->bookingService->date;
            $this->startTime  = $this->bookingService->startTime;
            $this->endTime    = $this->bookingService->endTime;
        }

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

    public function validateWithRooms()
    {
        if ($this->service->requireRoom()) {
            $availableRoom = Booking::getAvailableRoom(
                $this->employeeId,
                $this->service,
                $this->uuid,
                $this->date,
                $this->getStartTime(),
                $this->getEndTime()
            );

            if (empty($availableRoom->id)) {
                throw new Exception(trans('as.bookings.error.not_enough_rooms'), 1);
            }

            $this->roomId = $availableRoom->id;
        }

        return true;
    }

    public function validateEmployee()
    {
        if (empty($this->employee)) {
            throw new Exception(trans('as.bookings.error.employee_not_found'), 1);
        }

        return true;
    }

    public function upsertBookingService()
    {
        $this->validateData();
        $this->validateBooking();
        $this->validateEmployee();
        $this->validateWithRooms();
        $this->isNotValidWithCustomTime();

        $this->setBookingService();

        //TODO validate modify time and service time
        $model = (empty($this->bookingService->id)) ? (new BookingService()) : $this->bookingService;

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

        if (!empty($this->getRoomId())) {
            $room = Room::findOrFail($this->getRoomId());
            $bookingServiceRoom = new BookingServiceRoom();
            $bookingServiceRoom->bookingService()->associate($model);
            $bookingServiceRoom->room()->associate($room);
            $bookingServiceRoom->save();
        }

        //to avoid warning
        if (!empty($this->extraServices)) {
            foreach ($this->extraServices as $extraService) {
                $bookingExtraService = new BookingExtraService();
                $bookingExtraService->fill([
                    'date'     => $this->date,
                    'tmp_uuid' => $this->uuid
                ]);

                $bookingExtraService->extraService()->associate($extraService);
                $bookingExtraService->save();
            }
        }
        $this->bookingServiceId = $model->id;

        return $model;
    }

    public function getResponseData()
    {

        $this->setBookingService();

        if (empty($this->price)) {
            $this->computeTotalPrice();
        }

        $this->bookingService = BookingService::find($this->bookingServiceId);

        $extras = [];

        //Find all extra services of all booking services
        foreach ($this->bookingServices as $bookingService) {
            foreach ($bookingService->service->extraServices as $extraService) {
                $extras[] = [
                    'id' => $extraService->id,
                    'name' => $extraService->name
                ];
            }
        }
        $data = [
            'datetime'           => str_date($this->startTime),
            'booking_service_id' => $this->bookingServiceId,
            'booking_id'         => $this->bookingId,
            'start_time'         => $this->bookingStartTime->format('H:i'),
            'price'              => $this->bookingService->selectedService->price,
            'total_price'        => $this->price,
            'total_length'       => $this->getFormTotalLength(),
            'category_id'        => $this->service->category->id,
            'service_id'         => $this->service->id,
            'service_name'       => $this->service->name,
            'service_time'       => $this->bookingService->getFormServiceTime(),
            'service_length'     => $this->bookingService->selectedService->length,
            'plustime'           => $this->plustime,
            'employee_name'      => $this->employee->name,
            'uuid'               => $this->uuid,
            'extras'             => $extras,
        ];

        return $data;
    }

    public function getDeleteResponseData()
    {
        $data = [
            'success'      => true,
            'total_price'  => $this->computeTotalPrice(),
            'total_length' => $this->getFormTotalLength()
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
        $totalPrice = 0;

        $this->setBookingService();

        foreach ($this->bookingServices as $bookingService) {
            $totalPrice  += $bookingService->calculcateTotalPrice();
        }

        $this->price = $totalPrice + $this->extraServicePrice;

        return $this->price;
    }

    public function computeTotalLength()
    {
        $totalLength = 0;

        if (empty($this->bookingServices)) {
            $this->setBookingService();
        }

        foreach ($this->bookingServices as $bookingService) {
            $totalLength  += $bookingService->calculcateTotalLength();
        }

        return $totalLength;
    }

    public function getFormTotalLength()
    {
        $totalLength = $this->computeTotalLength();
        $hourText = (($totalLength / 60) >= 2)
            ? trans('common.short.hours')
            : trans('common.short.hour');

        $ret = ($totalLength >= 60)
            ? sprintf("%d (%s %s)", $totalLength, ($totalLength / 60), $hourText)
            : sprintf("%d", $totalLength);

        return $ret;
    }

    protected function saveCoupon($booking)
    {
        if (empty($this->coupon)) {
            return;
        }

        $coupon = Coupon::where('code', '=', $this->coupon)
            ->where('is_used', '=', 0)->first();
            
        if (empty($coupon->code)) {
            return;
        }

        $couponBooking = new CouponBooking();
        $couponBooking->coupon()->associate($coupon);
        $couponBooking->booking()->associate($booking);

        $coupon->campaign->reusable_usage += 1;
        $coupon->campaign->save();

        if (!$coupon->campaign->is_reusable){
            $coupon->is_used = true;
        } else {
            if (intval($coupon->campaign->amount) === intval($coupon->campaign->reusable_usage)) {
                $coupon->is_used = true;
            }
        }

        $coupon->save();

        return $couponBooking->save();
    }

    abstract public function upsertBooking();
    abstract public function validateData();
    abstract public function validateBooking();

}
