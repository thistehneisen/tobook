<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use Watson\Validating\ValidationException;

class BookingService extends \App\Appointment\Models\Base
{
    protected $table = 'as_booking_services';

    public $fillable = [
        'tmp_uuid',
        'date',
        'start_at',
        'end_at',
        'modify_time',
        'service_time_id',
        'is_reminder_email',
        'is_reminder_sms'
    ];

    private $extraServices;

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
        if(empty($this->extraServices)){
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
        if(empty($this->extraServices))
        {
            $extraServices     = BookingExtraService::where('tmp_uuid', $this->tmp_uuid)->get();
            $extraServiceTime  = 0;
            $extraServicePrice = 0;
            foreach ($extraServices as $extraService) {
                $extraServiceTime  += $extraService->length;
                $extraServicePrice += $extraService->length;
            }
            $this->extraServices['length'] = $extraServiceTime;
            $this->extraServices['price']  = $extraServicePrice;
        }
    }

    public function getExtraServicePrice()
    {
        if(empty($this->extraServices)){
            $this->calculateExtraServices();
        }
        return (int) $this->extraServices['price'];
    }

    public function getExtraServiceTime()
    {
        if(empty($this->extraServices)){
            $this->calculateExtraServices();
        }
        return (int) $this->extraServices['length'];
    }

    public function getEmployeePlustime()
    {
        return $this->employee->getPlustime($this->service->id);
    }

    public function calculateServiceLength()
    {
        $length = (!empty($this->serviceTime->length))
            ? $this->serviceTime->length
            : $this->service->length;

        $length += $this->getEmployeePlustime();

        $length += $this->modify_time;

        return $length;
    }

    public function calculateServicePrice()
    {
        $price = (!empty($this->serviceTime->price))
            ? $this->serviceTime->price
            : $this->service->price;

        return $price;
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    //TODO convert string to Carbon object
    // public function getStartAtAttribute()
    // {

    // }

    // public function getEndAtAttribute()
    // {

    // }

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

    //--------------------------------------------------------------------------
    // BUSINESS LOGIC
    //--------------------------------------------------------------------------

    /**
     * Saves booking service or updates an existing record.
     * <code>App\Appointment\Models\Booking::saveBooking</code> needs to be called with the same uuid
     * to associate the new booking service with a booking record.
     *
     * @param string $uuid
     * @param Employee $employee
     * @param Service $service
     * @param array $input
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
            $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $input['booking_date'], $input['start_time']));
        } catch (InvalidArgumentException $e) {
            throw new ValidationException(trans('as.bookings.error.start_time'));
        }

        // calculate service time
        $serviceTime = null;
        if ($input['service_time'] === 'default') {
            $length = $service->length;
        } else {
            $serviceTime = ServiceTime::find($input['service_time']);
            if (empty($serviceTime)) {
                throw new ValidationException(trans('as.bookings.error.service_time_invalid'));
            }
            $length = $serviceTime->length;
        }

        // calculate service time #2 (modify time / plus time)
        $endTimeDelta = ($length + $input['modify_time'] + $employee->getPlustime($service->id));
        if ($endTimeDelta < 1) {
            throw new ValidationException(trans('as.bookings.error.empty_total_time'));
        }

        // calculate end time (finally!)
        $endTime = with(clone $startTime)->addMinutes($endTimeDelta);
        $endDay = with(clone $startTime)->hour(23)->minute(59)->second(59);
        if ($endTime > $endDay) {
            throw new ValidationException(trans('as.bookings.error.exceed_current_day'));
        }

        // check if the booking overlaps with employee's freetime
        if ($employee->isOverllapedWithFreetime($startTime->toDateString(), $startTime, $endTime)) {
            throw new ValidationException(trans('as.bookings.error.overlapped_with_freetime'));
        }

        // check is there any existing booking with this service time
        if (!Booking::isBookable($employee->id, $startTime->toDateString(), $startTime, $endTime, $uuid)) {
            throw new ValidationException(trans('as.bookings.error.add_overlapped_booking'));
        }

        $bookingService = (empty($existingBookingService)) ? (new BookingService) : $existingBookingService;

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
