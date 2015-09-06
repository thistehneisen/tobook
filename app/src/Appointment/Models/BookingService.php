<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use App\Cart\CartDetailInterface;
use App\Cart\CartDetail;
use Watson\Validating\ValidationException;

class BookingService extends Base implements CartDetailInterface
{
    protected $table = 'as_booking_services';

    public $fillable = [
        'tmp_uuid',
        'date',
        'start_at',
        'end_at',
        'modify_time',
        'service_time_id',
        'is_requested_employee',
        'is_reminder_email',
        'is_reminder_sms'
    ];

    private $extraServices;

    private $employeePlustime = null;

    public function getCartStartAt()
    {
        $service = (!empty($this->serviceTime->id))
            ? $this->serviceTime
            : $this->service;
        $startTime = $this->getStartAt();
        $startTime->addMinutes($service->before);

        return $startTime;
    }

    public function getCartEndAt()
    {
        $service = (!empty($this->serviceTime))
            ? $this->serviceTime
            : $this->service;
        //Total include extra services and plus time
        $total = 0;
        $plustime = $this->employee->getPlustime($service->id);
        if (empty($this->extraServices)) {
            $this->calculateExtraServices();
        }
        $extraServiceTime = $this->extraServices['length'];
        $total = $extraServiceTime + $service->during + $plustime;
        $startTime = $this->getCartStartAt();
        $endTime = with(clone $startTime)->addMinutes($total);

        return $endTime;
    }

    public function calculateExtraServices()
    {
        if (empty($this->extraServices)) {
            $extraServices     = BookingExtraService::where('tmp_uuid', $this->tmp_uuid)->whereNull('deleted_at')->get();
            $extraServiceTime  = 0;
            $extraServicePrice = 0;
            foreach ($extraServices as $extraService) {
                $extraServiceTime  += $extraService->extraService->length;
                $extraServicePrice += $extraService->extraService->price;
            }
            $this->extraServices['length'] = $extraServiceTime;
            $this->extraServices['price']  = $extraServicePrice;
        }
    }

    public function getExtraServicePrice()
    {
        if (empty($this->extraServices)) {
            $this->calculateExtraServices();
        }

        return (int) $this->extraServices['price'];
    }

    public function getExtraServiceTime()
    {
        if (empty($this->extraServices)) {
            $this->calculateExtraServices();
        }

        return (int) $this->extraServices['length'];
    }

    public function getEmployeePlustime()
    {
        if ($this->employeePlustime === null) {
            $this->employeePlustime = (int) $this->employee->getPlustime($this->service->id);
        }

        return $this->employeePlustime;
    }

    /**
     * Reuturn booking service time for data attribute in booking form BE
     */
    public function getFormServiceTime()
    {
        return (!empty($this->serviceTime->id)) ? $this->serviceTime->id : 'default';
    }

    public function calculateServiceLength()
    {
        $length = $this->getServiceBaseLength();
        $length += $this->getEmployeePlustime();
        $length += $this->modify_time;

        return $length;
    }

    public function getServiceBaseLength()
    {
        $length = (!empty($this->serviceTime->length))
            ? $this->serviceTime->length
            : $this->service->length;

        return $length;
    }

    /**
     * Total Length = Service length|ServiceTime length
     *              + Employee plustime
     *              + Total ExtraService length
     * @return int
     * @author hung@varaa.com
     */
    public function calculcateTotalLength($plain = false, $accountExtraService = true)
    {
        $service = (!empty($this->serviceTime->id))
                    ? $this->serviceTime
                    : $this->service;

        $length = ($plain)
            ? $service->during
            : $service->length;

        //Add employee service plustime
        $length += $this->getEmployeePlustime();

        //Add extra service time
        if ($accountExtraService) {
            $length += $this->getExtraServiceTime();
        }

        return $length;
    }

    /**
     * Calculate total price of Service|ServiceTime and ExtraService
     *
     * @return float
     * @author hung@varaa.com
     */
    public function calculcateTotalPrice($accountExtraService = true)
    {
        $price  = $this->calculateServicePrice();

        if ($accountExtraService) {
            $price += $this->getExtraServicePrice();
        }

        return $price;
    }

    public function calculateServicePrice()
    {
        $service = (!empty($this->serviceTime))
            ? $this->serviceTime
            : $this->service;

        $price = $service->getDiscountPrice($this->startTime, $this->startTime);
        return $price;
    }

    public function recalculateEndtime()
    {
        $total        = $this->calculcateTotalLength();
        $this->end_at = $this->startTime->copy()->addMinutes($total)->toTimeString();
        $this->save();
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getStartTimeAttribute()
    {
        return new \Carbon\Carbon($this->date . ' ' . $this->start_at);
    }

    public function getEndTimeAttribute()
    {
        return new \Carbon\Carbon($this->date . ' ' . $this->end_at);
    }

    public function getSelectedServiceAttribute()
    {
        $service = (!empty($this->serviceTime->id))
            ? $this->serviceTime
            : $this->service;

        return $service;
    }

    public function getPlainStartTimeAttribute()
    {
        return $this->startTime->addMinutes($this->selectedService->before);
    }

    public function getPlainEndTimeAttribute()
    {
        return $this->plainStartTime->addMinutes($this->calculcateTotalLength(true));
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    public function service()
    {
       return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function serviceTime()
    {
       return $this->belongsTo('App\Appointment\Models\ServiceTime');
    }

    public function employee()
    {
       return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function rooms()
    {
        return $this->belongsToMany(
            'App\Appointment\Models\Room',
            'as_booking_service_rooms'
        );
    }

    //--------------------------------------------------------------------------
    // CART DETAIL
    //--------------------------------------------------------------------------
    /**
     * @{@inheritdoc}
     */
    public function getCartDetailName()
    {
        return sprintf('%s (%d %s)',
            $this->service->name,
            $this->service->length,
            trans('common.minutes'));
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailOriginal()
    {
        $price = $this->getCartDetailPrice();

        return (object) [
            'datetime'      => $this->date,
            'price'         => $price,
            'service_name'  => $this->service->name,
            'employee_name' => $this->employee->name,
            'start_at'      => $this->getCartStartAt()->format('H:i'),
            'end_at'        => $this->getCartEndAt()->format('H:i'),
            'uuid'          => $this->tmp_uuid,
            'instance'      => $this
        ];
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailQuantity()
    {
        return 1;
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailPrice()
    {
        $this->calculateExtraServices();
        $price = (!empty($this->serviceTime->id))
            ? $this->serviceTime->price
            : $this->service->price;

        $price += $this->getExtraServicePrice();

        return $price;
    }

    /**
     * Remove the temporary booking
     *
     * @param CartDetail $cartDetail
     *
     * @return void
     */
    public function unlockCartDetail(CartDetail $cartDetail)
    {
        // delete the booking service if it's not attached to any booking
        if (is_null($this->booking)) {
            $this->delete();
        } else {
            // otherwise check if this is a PENDING booking and source = 'inhouse'
            // then delete the attached booking
            if ((int) $this->booking->status === Booking::STATUS_PENDING
                    && $this->booking->source === 'inhouse') {
                $this->booking->delete_reason = 'Cart is set to ABANDONED';
                $this->booking->save();
                $this->booking->delete();
                $this->delete();
            }
        }
    }

    //--------------------------------------------------------------------------
    // BUSINESS LOGIC
    //--------------------------------------------------------------------------

    /**
     * Save booking service or updates an existing record.
     * <code>App\Appointment\Models\Booking::saveBooking</code> needs to be called with the same uuid
     * to associate the new booking service with a booking record.
     *
     * @param string         $uuid
     * @param Employee       $employee
     * @param Service        $service
     * @param array          $input
     * @param BookingService $existingBookingService
     *
     * @return BookingService
     *
     * @throw \Watson\Validating\ValidationException
     */
    public static function saveBookingService($uuid, Employee $employee, Service $service, array $input, BookingService $existingBookingService = null)
    {
        $input = array_merge([
            'booking_date' => '',
            'modify_time' => 0,
            'service_time' => 'default',
            'start_time' => '',
        ], $input);

        // validate input #1
        $input['modify_time'] = intval($input['modify_time']);

        // validate input #2
        try {
            $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $input['booking_date'], substr($input['start_time'], 0, 5)));
        } catch (InvalidArgumentException $e) {
            throw new ValidationException(trans('as.bookings.error.start_time'));
        }

        // calculate service time
        $serviceTime = null;
        $length = 0;
        if ($input['service_time'] === 'default') {
            $length += $service->length;
        } else {
            $serviceTime = ServiceTime::find($input['service_time']);
            if (empty($serviceTime)) {
                throw new ValidationException(trans('as.bookings.error.service_time_invalid'));
            }
            $length += $serviceTime->length;
        }

        // calculate service time #2 (modify time / plus time)
        $length += $input['modify_time'] + $employee->getPlustime($service->id);
        if ($length < 1) {
            throw new ValidationException(trans('as.bookings.error.empty_total_time'));
        }

        // calculate service time #3 (existing extra services)
        $existingBookingExtraServiceLength = 0;
        foreach (BookingExtraService::where('tmp_uuid', '=', $uuid)->get() as $existingBookingExtraService) {
            // TODO check for extra services of this $service only (support multiple services per booking)
            $existingBookingExtraServiceLength += $existingBookingExtraService->extraService->length;
        }

        // calculate end time (finally!)
        $endTime = with(clone $startTime)->addMinutes($length);
        $endTimeForOverlappingCheck = with(clone $endTime)->addMinutes($existingBookingExtraServiceLength);
        $endDay = with(clone $startTime)->hour(23)->minute(59)->second(59);
        if ($endTimeForOverlappingCheck > $endDay) {
            throw new ValidationException(trans('as.bookings.error.exceed_current_day'));
        }

        // check if the booking overlaps with employee's freetime
        if ($employee->isOverllapedWithFreetime($startTime->toDateString(), $startTime, $endTimeForOverlappingCheck)) {
            throw new ValidationException(trans('as.bookings.error.overlapped_with_freetime'));
        }

        // check is there any existing booking with this service time
        if (!Booking::isBookable($employee->id, $startTime->toDateString(), $startTime, $endTimeForOverlappingCheck, $uuid)) {
            throw new ValidationException(trans('as.bookings.error.add_overlapped_booking'));
        }

        $areResourcesAvailable = Booking::areResourcesAvailable($employee->id, $service, $uuid, $startTime->toDateString(), $startTime, $endTimeForOverlappingCheck);

        if (!$areResourcesAvailable) {
            throw new ValidationException(trans('as.bookings.error.not_enough_resources'));
        }

        $bookingService = (empty($existingBookingService)) ? (new BookingService()) : $existingBookingService;

        $bookingService->fill([
            'tmp_uuid' => $uuid,
            'date' => $startTime->toDateString(),
            'modify_time' => $input['modify_time'],
            'start_at' => $startTime->toTimeString(),
            'end_at' => $endTime->toTimeString(),
        ]);

        // there is no method opposite with associate
        $bookingService->service_time_id = null;
        if (!empty($serviceTime)) {
            $bookingService->serviceTime()->associate($serviceTime);
        }

        $bookingService->service()->associate($service);
        $bookingService->employee()->associate($employee);
        $bookingService->user()->associate($employee->user);
        $bookingService->saveOrFail();

        return $bookingService;
    }
}
