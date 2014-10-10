<?php namespace App\Appointment\Models;

use Request;
use App\Core\Models\User;
use App\Appointment\Models\Observer\SmsObserver;
use Carbon\Carbon;
use Watson\Validating\ValidationException;


class Booking extends \App\Appointment\Models\Base implements \SplSubject
{
    protected $table = 'as_bookings';

    public $fillable = [
        'date',
        'total',
        'total_price',
        'modify_time',
        'plustime',//from service employee
        'start_at',
        'end_at',
        'status',
        'notes',
        'uuid',
        'source',//layout
        'ip',
        'notes'
    ];

    const STATUS_CONFIRM     = 1;
    const STATUS_PENDING     = 2;
    const STATUS_CANCELLED   = 3;
    const STATUS_ARRIVED     = 4;
    const STATUS_PAID        = 5;
    const STATUS_NOT_SHOW_UP = 6;

    //Implement methods in SplSubject
    protected $_observers = [];

    public function attach(\SplObserver $observer)
    {
        $id = spl_object_hash($observer);
        $this->_observers[$id] = $observer;
    }

    public function detach(\SplObserver $observer)
    {
        $id = spl_object_hash($observer);

        if (isset($this->_observers[$id])) {
            unset($this->_observers[$id]);
        }
    }

    public function notify()
    {
        foreach ($this->_observers as $observer) {
            $observer->update($this);
        }
    }

    public function getClass(){
        $prefix = 'booked ';
        return $prefix . $this->getStatusText();
    }

    public function getStatusText(){
        return self::getStatusByValue($this->status);
    }

    public function getStatusTextAttribute(){
        return trans('as.bookings.' . self::getStatusByValue($this->status));
    }

    public function setStatus($text){
        $status = self::getStatus($text);
        $this->status = $status;
    }

    /**
     * Generate service info message for sending mail, sms, cancel booking
     *
     * @return string
     */
    public function getServiceInfo()
    {
        $before = $after = 0;
        if(!empty($this->bookingServices()->first()->serviceTime->id)){
            $serviceTime = $this->bookingServices()->first()->serviceTime;
            $before      = $serviceTime->before;
            $after       = $serviceTime->after;
        } else {
            $service = $this->bookingServices()->first()->service;
            $before  = $service->before;
            $after   = $service->after;
        }
        $start = $this->getStartAt()->addMinutes($before);
        $end   = $this->getEndAt()->subMinutes($after);

        $serviceInfo = "{service}, {date} ({start} - {end})";
        $serviceInfo = str_replace('{service}', $this->bookingServices()->first()->service->name, $serviceInfo);
        $serviceInfo = str_replace('{date}', $this->date, $serviceInfo);
        $serviceInfo = str_replace('{start}', $start->toTimeString(), $serviceInfo);
        $serviceInfo = str_replace('{end}', $end->toTimeString(), $serviceInfo);

        return $serviceInfo;
    }

    public static function getStatuses()
    {
        return [
            'confirmed'   => trans('as.bookings.confirmed'),
            'pending'     => trans('as.bookings.pending'),
            'cancelled'   => trans('as.bookings.cancelled'),
            'arrived'     => trans('as.bookings.arrived'),
            'paid'        => trans('as.bookings.paid'),
            'not_show_up' => trans('as.bookings.not_show_up')
        ];
    }

    public static function getStatus($statusText)
    {
        $map = [
            'confirmed'   => self::STATUS_CONFIRM,
            'pending'     => self::STATUS_PENDING,
            'cancelled'   => self::STATUS_CANCELLED,
            'arrived'     => self::STATUS_ARRIVED,
            'paid'        => self::STATUS_PAID,
            'not_show_up' => self::STATUS_NOT_SHOW_UP,
        ];

        return isset($map[$statusText]) ? $map[$statusText] : null;
    }

    public static function getStatusByValue($value)
    {
        $map = [
            static::STATUS_CONFIRM     => 'confirmed',
            static::STATUS_PENDING     => 'pending',
            static::STATUS_CANCELLED   => 'cancelled',
            static::STATUS_ARRIVED     => 'arrived',
            static::STATUS_PAID        => 'paid',
            static::STATUS_NOT_SHOW_UP => 'not_show_up',
        ];

        return isset($map[$value]) ? $map[$value] : null;
    }

    public static function isBookable($employeeId, $bookingDate, $startTime, $endTime, $uuid = null)
    {
        $query = self::where('date', $bookingDate)
            ->where('employee_id', $employeeId)
            ->whereNull('deleted_at')
            ->where('status','!=', self::STATUS_CANCELLED)
            ->where(function ($query) use ($startTime, $endTime) {
                return $query->where(function ($query) use ($startTime, $endTime) {
                    return $query->where('start_at', '>=', $startTime->toTimeString())
                         ->where('start_at', '<', $endTime->toTimeString());
                })->orWhere(function ($query) use ($endTime, $startTime) {
                     return $query->where('end_at', '>', $startTime->toTimeString())
                          ->where('end_at', '<=', $endTime->toTimeString());
                })->orWhere(function ($query) use ($startTime, $endTime) {
                     return $query->where('start_at', '=', $startTime->toTimeString())
                          ->where('end_at', '=', $endTime->toTimeString());
                });
            });

        if(!empty($uuid)){
            $query->where('uuid','!=', $uuid);
        }

        $bookings = $query->get();

        if (!$bookings->isEmpty()) {
            return false;
        }
        return true;
    }

    public static function getLastestBookingEndTime($date, \App\Core\Models\User $user = null)
    {
        if ($user === null) {
            $user = \Confide::user();
        }

        return self::ofUser($user)
            ->where('date', $date)
            ->orderBy('end_at', 'desc')
            ->first();
    }

    public static function getLastestBookingEndTimeInRange($startDate, $endDate)
    {
        $lastestBooking = self::ofCurrentUser()
            ->where('date', '>=', $startDate)
            ->wherE('date', '<=', $endDate)
            ->orderBy('end_at', 'desc')->first();
        return $lastestBooking;
    }

    public function getExtraServices()
    {
        $extraServices = [];
        foreach ($this->extraServices as $extra) {
            $extraServices[] = $extra->extra_service->name;
        }

        return $extraServices;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }

    public function asConsumer()
    {
       return $this->belongsTo('App\Appointment\Models\Consumer');
    }

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function bookingServices()
    {
        return $this->hasMany('App\Appointment\Models\BookingService');
    }

    public function extraServices(){
         return $this->hasMany('App\Appointment\Models\BookingExtraService');
    }

    //--------------------------------------------------------------------------
    // BUSINESS LOGIC
    //--------------------------------------------------------------------------

    /**
     * Saves booking or updates an existing record. New booking services and/or extra services
     * will be looked up and saved together with the booking record.
     *
     * @param string $uuid
     * @param User $user
     * @param Consumer $consumer
     * @param array $input
     * @param Booking $existingBooking
     *
     * @return Booking
     *
     * @throw \Watson\Validating\ValidationException
     */
    public static function saveBooking($uuid, User $user, Consumer $consumer, array $input, Booking $existingBooking = null)
    {
        $input = array_merge([
            'ip' => '',
            'notes' => '',
            'status' => 'confirmed',
        ], $input);

        // validate input #1
        if (empty($input['ip'])) {
            if (!empty($existingBooking)) {
                $input['ip'] = $existingBooking->ip;
            } else {
                $input['ip'] = Request::getClientIp();
            }
        }

        // validate input #2
        $input['status'] = self::getStatus($input['status']);
        if ($input['status'] === self::STATUS_CANCELLED) {
            if (empty($existingBooking)) {
                throw new ValidationException(trans('as.bookings.booking_missing'));
            }

            $existingBooking->deleteOrFail();
            return $existingBooking;
        }

        // get booking services, existing and new
        $existingBookingServices = [];
        if (!empty($existingBooking)) {
            foreach ($existingBooking->bookingServices as $existingBookingService) {
                $existingBookingServices[] = $existingBookingService;
            }
        }
        $uuidBookingServices = BookingService::where('tmp_uuid', $uuid)->get();
        $newBookingServices = [];
        foreach ($uuidBookingServices as $bookingService) {
            if (empty($bookingService->booking_id)) {
                $newBookingServices[] = $bookingService;
            }
        }
        $bookingServices = array_merge($existingBookingServices, $newBookingServices);
        if (empty($bookingServices)) {
            throw new ValidationException(trans('as.bookings.missing_services'));
        }

        // calculate length / price with data from services
        $totalLength = 0;
        $totalPrice = 0;
        $totalModifyTime = 0;
        $totalPlusTime = 0;
        $startTime = null;
        foreach ($bookingServices as $bookingService) {
            $totalLength += $bookingService->calculateServiceLength();
            $totalPrice += $bookingService->calculateServicePrice();

            // TODO: remove fields modify_time and plustime from booking table
            $totalModifyTime += $bookingService->modify_time;
            $totalPlusTime += $bookingService->plustime;

            $bookingServiceStartTime = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $bookingService->date, $bookingService->start_at));
            if ($startTime === null OR $bookingServiceStartTime->diffInMinutes($startTime, false) > 0) {
                $startTime = clone $bookingServiceStartTime;
            }
        }

        // get extra services, existing and new
        $existingExtraServices = [];
        if (!empty($existingBooking)) {
            foreach ($existingBooking->extraServices as $existingExtraService) {
                $existingExtraServices[] = $existingExtraService;
            }
        }
        $uuidExtraServices = BookingExtraService::where('tmp_uuid', $uuid)->get();
        $newExtraServices = [];
        foreach ($uuidExtraServices as $extraService) {
            if (empty($extraService->booking_id)) {
                $newExtraServices[] = $extraService;
            }
        }
        $extraServices = array_merge($existingExtraServices, $newExtraServices);

        // recalculate length / price with data from extra services, if any
        foreach ($extraServices as $extraService) {
            $totalLength += $extraService->length;
            $totalPrice += $extraService->price;
        }

        // calculate end time
        $endTime = with(clone $startTime)->addMinutes($totalLength);

        if (!empty($existingBooking)) {
            $booking = $existingBooking;
        } else {
            $booking = new Booking();
        }

        $booking->fill([
            'date' => $startTime->toDateString(),
            'start_at' => $startTime->toTimeString(),
            'end_at' => $endTime->toTimeString(),
            'total' => $totalLength,
            'total_price' => $totalPrice,
            'status' => $input['status'],
            'notes' => $input['notes'],
            'uuid' => $uuid,
            'modify_time' => $totalModifyTime,
            'plustime' => $totalPlusTime,
            'ip' => $input['ip'],
        ]);

        $booking->consumer()->associate($consumer);
        $booking->employee()->associate($bookingService->employee);
        $booking->user()->associate($user);
        $booking->saveOrFail();

        foreach ($newBookingServices as $bookingService) {
            // TODO: reset tmp_uuid?
            $bookingService->booking()->associate($booking);
            $bookingService->saveOrFail();
        }

        foreach ($newExtraServices as $extraService) {
            // TODO: reset tmp_uuid?
            $extraService->booking()->associate($booking);
            $extraService->saveOrFail();
        }

        if (empty($existingBooking)) {
            // send sms for new booking
            $booking->attach(new SmsObserver(true));
            $booking->notify();
        }

        return $booking;
    }

    /**
     * Updates booking data. Useful after deletions of booking services and/or
     * extra services.
     *
     * @param Booking $booking
     *
     * @return Booking $booking
     *
     * @throw \Watson\Validating\ValidationException
     */
    public static function updateBooking(Booking $booking)
    {
        // just call Booking::saveBooking with existing data and let it recalculate everything
        return self::saveBooking($booking->uuid, $booking->consumer, [
            'ip' => $booking->ip,
            'notes' => $booking->notes,
            'status' => self::getStatusByValue($booking->status),
        ], $booking);
    }
}
