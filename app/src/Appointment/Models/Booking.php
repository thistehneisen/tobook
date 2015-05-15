<?php namespace App\Appointment\Models;

use Request, Carbon\Carbon, NAT, Util, Config, DB;
use Settings;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Observer\SmsObserver;
use Watson\Validating\ValidationException;

class Booking extends \App\Appointment\Models\Base implements \SplSubject
{
    protected $table = 'as_bookings';

    public $fillable = [
        'date',
        'total',
        'total_price',
        'deposit', // deposit payment feature
        'modify_time',
        'plustime',//from service employee
        'start_at',
        'end_at',
        'status',
        'notes',
        'uuid',
        'source',//layout
        'ip',
        'notes',
        // Why this booking is deleted. Possible reason: asked by customer,
        // abandoned and auto-deleted by a task runner
        'delete_reason',
    ];

    const STATUS_CONFIRM     = 1;
    const STATUS_PENDING     = 2;
    const STATUS_CANCELLED   = 3;
    const STATUS_ARRIVED     = 4;
    const STATUS_PAID        = 5;
    const STATUS_NOT_SHOW_UP = 6;

    // Minutes to step between two possible booking time. For examples, bookable
    // time could be 8:00, 8:15, 8:30, etc. with default step of 15.
    const STEP = 15;

    //Implement methods in SplSubject
    protected $_observers = [];

    //Cache object for list of bookings
    protected static $bookings;
    protected $resources = [];

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

    public function getClass()
    {
        $prefix = 'booked ';

        return $prefix . $this->getStatusText();
    }

    public function getStatusText()
    {
        return self::getStatusByValue($this->status);
    }

    public function getStatusTextAttribute()
    {
        return trans('as.bookings.' . self::getStatusByValue($this->status));
    }

    public function setStatus($text)
    {
        $status = self::getStatus($text);
        $this->status = $status;
    }

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();
        static::saving(function ($booking) {
            $booking->updateNAT();
        });

        static::deleting(function ($booking) {
            // When deleting a booking, we'll restore the available NAT slots
            NAT::restoreBookedTime($booking);
        });
    }

    /**
     * If status of this booking changed to CONFIRM, we'll remove the available
     * slots
     *
     * @return void
     */
    public function updateNAT()
    {
        // If this booking is cancelled, we need to restore its available slots
        if ($this->status === static::STATUS_CANCELLED) {
            NAT::restoreBookedTime($this);
        }

        if ($this->getOriginal('status') !== static::STATUS_CONFIRM
            && $this->status === static::STATUS_CONFIRM) {
            NAT::removeBookedTime($this);
        }
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    public function scopeHasStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getStartTimeAttribute()
    {
        if ($this->start_at instanceof Carbon) {
            return $this->start_at;
        }

        return new \Carbon\Carbon($this->date . ' ' . $this->start_at);
    }

    public function getEndTimeAttribute()
    {
        if ($this->start_at instanceof Carbon) {
            return $this->end_at;
        }

        return new \Carbon\Carbon($this->date . ' ' . $this->end_at);
    }

    /**
     * Return a FontAwesome icon to display in backend calendar
     *
     * @return string
     */
    public function getSourceIconAttribute()
    {
        // No icon for bookings made from backend calendar
        if ($this->source === 'backend') {
            return NULL;
        }

        // For bookings make from our consumer portal
        if ($this->source === 'inhouse') {
            return 'fa-user-plus';
        }

        // For bookings from layout 1/2/3 embeded in customer's website
        return 'fa-globe';
    }

    /**
     * Get the commision status used in /en/xandar/users/{id}}/commissions-counter
     * @see getBookingsByEmployeeStatus
     * @return string $status
     */
    public function getCommisionStatusAttribute()
    {
         $map = [
            static::STATUS_CONFIRM     => 'confirmed',
            static::STATUS_PENDING     => 'pending',
            static::STATUS_CANCELLED   => 'cancelled',
            static::STATUS_ARRIVED     => 'arrived',
            static::STATUS_PAID        => 'paid',
            static::STATUS_NOT_SHOW_UP => 'not_show_up',
        ];

        $status = isset($map[$this->booking_status]) ? $map[$this->booking_status] : null;

        if($this->booking_status === static::STATUS_CONFIRM && ($this->deposit > 0)){
            $status = 'deposit';
        }
        return $status;
    }

    /**
     * Only get 9 words to display on table
     * @return string ingress
     */
    public function getIngressAttribute()
    {
        $ingress = explode(' ', $this->notes);
        if(count($ingress) > 9) {
            $ingress = array_slice($ingress, 9);
            $ingress[] = '...';
        }
        return implode($ingress, ' ');
    }

    /**
     * Generate service info message for sending mail, sms, cancel booking
     *
     * @return string
     */
    public function getServiceInfo()
    {
        $before = $after = 0;
        if (!empty($this->bookingServices()->first()->serviceTime->id)) {
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

        $allServices[] = $this->bookingServices()->first()->service->name;
        $allServices = array_merge($allServices, $this->getExtraServices());

        $serviceDescription = !empty($allServices)
            ? implode(' + ', $allServices)
            : '';

        $serviceInfo = "{service} - {employee}, {date} ({start} - {end})";
        $serviceInfo = str_replace('{service}', $serviceDescription, $serviceInfo);
        $serviceInfo = str_replace('{employee}', $this->employee->name, $serviceInfo);
        $serviceInfo = str_replace('{date}', $this->date, $serviceInfo);
        $serviceInfo = str_replace('{start}', $start->toTimeString(), $serviceInfo);
        $serviceInfo = str_replace('{end}', $end->toTimeString(), $serviceInfo);

        return $serviceInfo;
    }

    public function getServicesDescription()
    {
        $description = '';
        if (!empty($this->bookingServices()->first()->serviceTime->id)) {
            $serviceTime = $this->bookingServices()->first()->serviceTime;
            $description = $serviceTime->description;
        } else {
            $service = $this->bookingServices()->first()->service;
            $description = $service->description;
        }
        return $description;
    }

    /**
     * Get deposit amount of an booking
     * @see https://github.com/varaa/varaa/issues/491
     * @return float
     */
    public function depositAmount()
    {
        $rate = Settings::get('deposit_rate');
        $deposit = 0;
        if (!empty($rate)) {
            $services = $this->bookingServices();
            foreach ($services as $service) {
                $deposit += $service->price * $rate;
            }
        }
        return $deposit;
    }

    public function getFormTotalLength()
    {
        $hourText = (($this->total / 60) >= 2)
            ? trans('common.short.hours')
            : trans('common.short.hour');

        $ret = ($this->total >= 60)
            ? sprintf("%d (%s %s)", $this->total, ($this->total / 60), $hourText)
            : sprintf("%d", $this->total);

        return $ret;
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

    /**
     * Check if user can place a booking on certain employee, date, and time
     * but only execute one query
     *
     * @param int    $employeeId
     * @param string $bookingDate
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @param string $uuid
     *
     * @return boolean
     */
    public static function isBookable($employeeId, $bookingDate, Carbon $startTime, Carbon $endTime, $uuid = null)
    {
        $bookings = self::getOverlappedBookings($employeeId, $bookingDate, $startTime, $endTime, $uuid);

        if (!$bookings->isEmpty()) {
            return false;
        }

        return true;
    }

    /**
     * Get list of overlapped booking
     * Using in add freetime from
     *
     * @param int    $employeeId
     * @param string $bookingDate
     * @param Carbon $startTime
     * @param Carbon $endTime
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getOverlappedBookings($employeeId, $bookingDate, Carbon $startTime, Carbon $endTime, $uuid = null)
    {
        $bookings = null;

        if ($bookingDate instanceof Carbon) {
            $bookingDate = $bookingDate->toDateString();
        }

        $query = self::where('date', $bookingDate)
            ->where('employee_id', $employeeId)
            ->whereNull('deleted_at')
            ->where('status','!=', self::STATUS_CANCELLED);

        $query = self::applyDuplicateFilter($query, $startTime, $endTime);

        if (!empty($uuid)) {
            $query->where('uuid','!=', $uuid);
        }

        $bookings = $query->get();

        return $bookings;
    }

    /**
     * Check all resources are avialble for a certain booking
     * @return boolean
     */
    public static function areResourcesAvailable($employeeId, $service, $uuid, $bookingDate, Carbon $startTime, Carbon $endTime)
    {
        $resourceIds = [];
        if (!empty($service)) {
            $resourceIds = $service->resources->lists('id');
        }
        if (empty($resourceIds)) {
            return true;
        }

        $query = self::where('as_bookings.date', $bookingDate)
            ->whereNull('as_bookings.deleted_at')
            ->where('as_bookings.status','!=', self::STATUS_CANCELLED);

        //for inhouse layout
        if (!empty($uuid)) {
            $query->where('as_bookings.uuid', '!=', $uuid);
        }

        $query = self::applyDuplicateFilter($query, $startTime, $endTime);

        $result = $query->join('as_booking_services', 'as_booking_services.booking_id', '=','as_bookings.id')
            ->join('as_services', 'as_services.id', '=','as_booking_services.service_id')
            ->join('as_resource_service', 'as_resource_service.service_id', '=', 'as_services.id')
            ->join('as_resources', 'as_resource_service.resource_id', '=', 'as_resources.id')
            ->whereIn('as_resource_service.resource_id', $resourceIds)
            ->select(array('as_resource_service.resource_id as resource_id','as_resources.quantity', DB::raw('COUNT(varaa_as_resource_service.resource_id) as occupied')))
            ->groupBy('as_resource_service.resource_id')
            ->get();

        if ($result->isEmpty()) {
            return true;
        }

        //If resources are not enough, return false
        foreach ($result as $item) {
            if ($item->occupied >= $item->quantity) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get available rooms for booking
     * @return array
     */
    public static function getAvailableRoom($employeeId, $service, $uuid, $bookingDate, Carbon $startTime, Carbon $endTime)
    {
        $query = self::where('as_bookings.date', $bookingDate)
            ->whereNull('as_bookings.deleted_at')
            ->where('as_bookings.status','!=', self::STATUS_CANCELLED);

        //for inhouse layout
        if (!empty($uuid)) {
            $query->where('as_bookings.uuid', '!=', $uuid);
        }

        $query = self::applyDuplicateFilter($query, $startTime, $endTime);

        $query = $query->join('as_booking_services', 'as_booking_services.booking_id', '=','as_bookings.id')
            ->join('as_services', 'as_services.id', '=','as_booking_services.service_id')
            ->join('as_room_service', 'as_room_service.service_id', '=', 'as_services.id')
            ->join('as_booking_service_rooms', 'as_booking_service_rooms.booking_service_id', '=','as_booking_services.id')
            ->whereIn('as_booking_service_rooms.room_id',  $service->rooms()->lists('id'))
            ->select('as_booking_service_rooms.room_id AS room')
            ->lists('room');

        $availableRoom = (!empty($query))
            ? $service->rooms()->whereNotIn('room_id', $query)->first()
            : $service->rooms()->first();

        return $availableRoom;
    }

    public function isShowModifyPopup()
    {
        return ($this->source ==='inhouse' && $this->getConsumerName() === '')
            ? false
            : true;
    }

    /**
     * Return a query with conditions for checking duplicate booking
     * @return Illuminate\Database\Query\Builder
     */
    public static function applyDuplicateFilter($query, $startTime, $endTime)
    {
        return $query->where(function ($query) use ($startTime, $endTime) {
            return $query->where(function ($query) use ($startTime, $endTime) {
                return $query->where('as_bookings.start_at', '>=', $startTime->toTimeString())
                     ->where('as_bookings.start_at', '<', $endTime->toTimeString());
            })->orWhere(function ($query) use ($endTime, $startTime) {
                 return $query->where('as_bookings.end_at', '>', $startTime->toTimeString())
                      ->where('as_bookings.end_at', '<=', $endTime->toTimeString());
            })->orWhere(function ($query) use ($startTime) {
                 return $query->where('as_bookings.start_at', '<', $startTime->toTimeString())
                      ->where('as_bookings.end_at', '>', $startTime->toTimeString());
            })->orWhere(function ($query) use ($startTime, $endTime) {
                 return $query->where('as_bookings.start_at', '=', $startTime->toTimeString())
                      ->where('as_bookings.end_at', '=', $endTime->toTimeString());
            });
        });
    }

    /**
     * Check if user can place a booking on certain employee, date, and time
     * but only execute one query
     *
     * @param int    $employeeId
     * @param string $date
     * @param Carbon $startTime
     * @param Carbon $endTime
     * @param string $uuid
     *
     * @return boolean
     */
    public static function canBook($employeeId, $date, Carbon $startTime, Carbon $endTime, $uuid = null)
    {
        if (empty(static::$bookings[$date])) {
            $bookings = self::where('date', $date)
                ->whereNull('deleted_at')
                ->where('status','!=', self::STATUS_CANCELLED)->get();
            static::$bookings[$date] = $bookings;
        }

        $bookings = static::$bookings[$date];

        foreach ($bookings as $booking) {
            if($booking->getStartAt() >= $startTime
                && $booking->getStartAt() < $endTime
                && $booking->employee->id == $employeeId
                && $booking->uuid != $uuid){
                return false;
            } elseif($booking->getEndAt() > $startTime
                && $booking->getEndAt() <= $endTime
                && $booking->employee->id == $employeeId
                && $booking->uuid != $uuid){
                return false;
            } elseif($booking->getStartAt() == $startTime
                && $booking->getEndAt() == $endTime
                && $booking->employee->id == $employeeId
                && $booking->uuid != $uuid){
                return false;
            }
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
            ->where('date', '<=', $endDate)
            ->orderBy('date', 'desc')
            ->orderBy('end_at', 'desc')
            ->first();

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

    /**
     * Return the tooltip content for booking in backend carlendar
     * Writing html in model is evil, but sometimes we have to sacrifice
     * the beauty for functionalities.
     * @return string
     */
    public function getCalendarTooltip()
    {
        $startTime          = $this->getStartAt()->format('H:i');
        $endTime            = $this->getEndAt()->format('H:i');
        $consumerName       = !empty($this->consumer->name) ? $this->consumer->name : '';
        $notes              = empty($this->notes) ? '' : '<br><br><em>'.$this->notes.'</em>';
        $serviceDescription = $this->getServiceDescription(true);
        $tooltip = sprintf(
            '%s - %s <br> %s <br> %s %s',
            $startTime, $endTime, $consumerName, $serviceDescription, $notes
        );

        return $tooltip;
    }

    /**
     * Return thea all booking service information
     * @param  boolean $showLineBreak
     * @return string
     */
    public function getServiceDescription($showLineBreak = false)
    {
        $allServices = [];

        //Is there any chance for an empty service name?
        foreach ($this->bookingServices as $bookingService) {
            $allServices[] = !empty($bookingService->service->name)
                ? $bookingService->service->name
                : '<em>' . trans('as.services.no_name') . '</em>';
        }
        $allServices = array_merge($allServices, $this->getExtraServices());

        $serviceDescription = !empty($allServices)
            ? '(' . implode(' + ', $allServices) . ')'
            : '';

        if (!empty($bookingService->serviceTime->id)) {
            $serviceDescription .= ($showLineBreak)
                ? '<br>' . $bookingService->serviceTime->description
                : ' ' . $bookingService->serviceTime->description;
        }

        foreach ($this->bookingServices as $bookingService) {
            $room = $bookingService->rooms()->first();

            if (!empty($room)) {
                $serviceDescription .= '<br>' . $room->name;
            }
        }

        return $serviceDescription;
    }

    /**
     * Return the 1st booking service, just for convience
     * @return App\Appointment\Models\BookingService
     */
    public function firstBookingService()
    {
        return $this->bookingServices()->first();
    }

    public function getConsumerName()
    {
        $consumerName = !empty($this->consumer->name) ? $this->consumer->name : '';

        return $consumerName;
    }

    /**
     * Get array resources which is used by booking service
     * Use this for less query to the database
     * @return array
     */
    public function getBookingResources($keyOnly = false)
    {
        if (empty($this->resources)) {
            if (empty($this->firstBookingService()) || empty($this->firstBookingService()->service)) {
                return [];
            }
            $service = $this->firstBookingService()->service;
            $resources = ResourceService::join('as_resources', 'as_resources.id','=', 'as_resource_service.resource_id')
                ->where('as_resource_service.service_id', $service->id)
                ->select('as_resources.name', 'as_resources.id')->get();
            foreach ($resources as $resource) {
                $this->resources[$resource->id] = $resource->name;
            }
        }

        if ($keyOnly) {
            return array_keys($this->resources);
        }

        return $this->resources;
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

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function bookingServices()
    {
        return $this->hasMany('App\Appointment\Models\BookingService');
    }

    public function extraServices()
    {
         return $this->hasMany('App\Appointment\Models\BookingExtraService');
    }

    //--------------------------------------------------------------------------
    // BUSINESS LOGIC
    //--------------------------------------------------------------------------

    /**
     * Save booking or update an existing record. New booking services and/or extra services
     * will be looked up and saved together with the booking record.
     *
     * @param string   $uuid
     * @param User     $user
     * @param Consumer $consumer
     * @param array    $input
     * @param Booking  $existingBooking
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
                throw new ValidationException(trans('as.bookings.error.id_not_found'));
            }
        }

        // get booking services, existing and new
        $existingBookingServices = [];
        if (!empty($existingBooking)) {
            foreach ($existingBooking->bookingServices as $existingBookingService) {
                $existingBookingServices[] = $existingBookingService;
            }
        }
        $uuidBookingServices = BookingService::where('tmp_uuid', $uuid)->get();
        $newBookingServices  = [];
        foreach ($uuidBookingServices as $bookingService) {
            if (empty($bookingService->booking_id)) {
                $newBookingServices[] = $bookingService;
            }
        }
        $bookingServices = array_merge($existingBookingServices, $newBookingServices);
        if (empty($bookingServices)) {
            throw new ValidationException(trans('as.bookings.error.service_empty'));
        }

        // calculate length / price with data from services
        $totalLength     = 0;
        $totalPrice      = 0;
        $totalModifyTime = 0;
        $totalPlusTime   = 0;
        $startTime       = null;
        foreach ($bookingServices as $bookingService) {
            $totalLength += $bookingService->calculateServiceLength();
            $totalPrice  += $bookingService->calculateServicePrice();

            // TODO: remove fields modify_time and plustime from booking table
            $totalModifyTime += $bookingService->modify_time;
            $totalPlusTime   += $bookingService->plustime;

            $bookingServiceStartTime = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $bookingService->date, $bookingService->start_at));
            if ($startTime === null || $bookingServiceStartTime->diffInMinutes($startTime, false) > 0) {
                $startTime = clone $bookingServiceStartTime;
            }
        }

        // get extra services, existing and new
        $existingBookingExtraServices = [];
        if (!empty($existingBooking)) {
            foreach ($existingBooking->extraServices as $existingExtraService) {
                $existingBookingExtraServices[] = $existingExtraService;
            }
        }
        $uuidBookingExtraServices = BookingExtraService::where('tmp_uuid', $uuid)->get();
        $newBookingExtraServices = [];
        foreach ($uuidBookingExtraServices as $bookingExtraService) {
            if (empty($bookingExtraService->booking_id)) {
                $newBookingExtraServices[] = $bookingExtraService;
            }
        };
        $bookingExtraServices = array_merge($existingBookingExtraServices, $newBookingExtraServices);

        // recalculate length / price with data from extra services, if any
        foreach ($bookingExtraServices as $bookingExtraService) {
            $totalLength += $bookingExtraService->extraService->length;
            $totalPrice  += $bookingExtraService->extraService->price;
        }

        // calculate end time
        $endTime = with(clone $startTime)->addMinutes($totalLength);

        if (!empty($existingBooking)) {
            $booking = $existingBooking;
        } else {
            $booking = new Booking();
        }

        $booking->fill([
            'date'        => $startTime->toDateString(),
            'start_at'    => $startTime->toTimeString(),
            'end_at'      => $endTime->toTimeString(),
            'total'       => $totalLength,
            'total_price' => $totalPrice,
            'status'      => $input['status'],
            'notes'       => $input['notes'],
            'uuid'        => $uuid,
            'modify_time' => $totalModifyTime,
            'plustime'    => $totalPlusTime,
            'ip'          => $input['ip'],
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

        foreach ($newBookingExtraServices as $bookingExtraService) {
            // TODO: reset tmp_uuid?
            $bookingExtraService->booking()->associate($booking);
            $bookingExtraService->saveOrFail();
        }

        if (empty($existingBooking)) {
            // send sms for new booking
            $booking->attach(new SmsObserver(true));
            $booking->notify();
        }

        return $booking;
    }

    /**
     * Return icons for indicating requested employee of booking resources
     * @return string
     */
    public function getIcons()
    {
        $ouput = '';
        if (!empty($this->firstBookingService())) {
            if ($this->firstBookingService()->is_requested_employee) {
                $ouput .= '<i class="fa fa-check-square-o"></i>&nbsp;';
            }
            if (!empty($this->firstBookingService()->service) && $this->firstBookingService()->service->requireRoom()) {
                $ouput .= '<i class="fa fa-square-o"></i>&nbsp;';
            }
            if (!empty($this->getBookingResources())) {
                $ouput .= '<i class="fa fa-cubes"></i>&nbsp;';
            }
        }

        return trim($ouput);
    }
    /**
     * Update booking data. Useful method after deletions of booking services and/or
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
        $consumer = Consumer::find($booking->consumer_id);

        // just call Booking::saveBooking with existing data and let it recalculate everything
        return self::saveBooking($booking->uuid, $booking->user, $consumer, [
            'ip' => $booking->ip,
            'notes' => $booking->notes,
            'status' => self::getStatusByValue($booking->status),
        ], $booking);
    }

    /**
     * Reschedule a booking and all of its services, extra services with another employee, at a different time
     *
     * @param Booking  $booking
     * @param Employee $employee
     * @param array    $input
     *
     * @return Booking
     *
     * @throw \Watson\Validating\ValidationException
     */
    public static function rescheduleBooking(Booking $booking, Employee $employee, array $input)
    {
        $input = array_merge([
            'booking_date' => '',
            'start_time' => '',
        ], $input);

        if ($booking->bookingServices()->count() != 1) {
            // do not continue with unsupported booking as we only work with one service for now
            throw new ValidationException(trans('as.bookings.reschedule_single_only'));
        }

        \DB::transaction(function () use ($booking, $employee, $input) {
            // prepare extra service ids to keep track
            $extraServiceIds = [];
            foreach ($booking->extraServices as $bookingExtraService) {
                $extraServiceIds[] = $bookingExtraService->extraService->id;
                $bookingExtraService->delete();
            }

            // start re-booking services and extra services
            foreach ($booking->bookingServices as $bookingService) {
                $service = $bookingService->service;
                $bookingServiceInput = array_merge($input, [
                    'modify_time' => $bookingService->modify_time,
                    'service_time' => (!empty($bookingService->service_time_id) ? $bookingService->serviceTime->id : 'default'),
                ]);
                $bookingService->delete();

                $bookingService = BookingService::saveBookingService($booking->uuid, $employee, $service, $bookingServiceInput);

                foreach ($service->extraServices as $serviceExtraService) {
                    foreach (array_keys($extraServiceIds) as $key) {
                        // traverse by key because there may be more than one booking for one extra service
                        // also, we need to delete the id after successfully book it
                        if ($serviceExtraService->id == $extraServiceIds[$key]) {
                            BookingExtraService::addExtraService($booking->uuid, $employee, $bookingService, $serviceExtraService);
                            unset($extraServiceIds[$key]);
                        }
                    }
                }
            }

            // there are unbooked extra services, throw error
            if (!empty($extraServiceIds)) {
                throw new ValidationException(trans('as.bookings.error.reschedule_unbooked_extra'));
            }
        });

        // call update booking to associate those newly booked services / extra services with the booking itself
        $booking = Booking::find($booking->id);

        return self::updateBooking($booking);
    }

    /**
     * Generate a random 16-character string for Booking UUID
     *
     * @return string
     */
    public static function uuid()
    {
        while (true) {
            $uuid = Util::uuid();
            if (is_null(self::where('uuid', '=', $uuid)->first())) {
                return $uuid;
            }
        }
    }

    /**
     * Calculate the total commissions of paid bookings of a user
     *
     * @param App\Core\Models\User $user
     *
     * @return double
     */
    public static function calculateCommissions(User $user)
    {
        $total = static::hasStatus(static::STATUS_PAID)
            ->ofUser($user)
            ->sum('total');

        // 2-decimal place
        return round($total * (double) Settings::get('commission_rate'), 2);
    }

    public function getEmailBody()
    {
        $body  = $this->user->asOptions['confirm_tokens_employee'];
        $body  = str_replace('{Services}', $this->getServiceInfo(), $body);
        $body  = str_replace('{Name}',$this->consumer->name, $body);
        $body  = str_replace('{BookingID}', $this->uuid, $body);
        $body  = str_replace('{Phone}', $this->consumer->phone, $body);
        $body  = str_replace('{Email}', $this->consumer->email, $body);
        $body  = str_replace('{Notes}', $this->notes, $body);

        return $body;
    }

    public function generateIcsFile()
    {
        date_default_timezone_set(Config::get('app.timezone'));
        $calendar = new \Eluceo\iCal\Component\Calendar(Settings::get('site_name'));
        $event = new \Eluceo\iCal\Component\Event();
        $event->setDtStart($this->startTime)
            ->setDtEnd($this->endtime)
            ->setSummary($this->getServiceInfo());
        $event->setUseTimezone(true);

        //add event to canlendar
        $calendar->addComponent($event);

        $filename = public_path() . '/tmp/' . 'isc_' . $this->startTime->format('YmdHis');
        file_put_contents($filename, $calendar->render());

        return $filename;
    }

    public static function getBookingsByEmployeeStatus($userId, $status, $employeeId, $perPage, $start, $end)
    {
        $query = self::where('as_bookings.created_at', '>', $start)
            ->where('as_bookings.created_at', '<', $end)
            ->whereNull('as_bookings.deleted_at')
            ->where('as_bookings.status','!=', self::STATUS_CANCELLED)
            ->where('as_bookings.status','!=', self::STATUS_PENDING)
            ->where('as_bookings.status','!=', self::STATUS_NOT_SHOW_UP)
            ->where('as_bookings.user_id', '=', $userId)
            ->where('as_employees.status', '=', $status);

        if(!empty($employeeId)) {
            $query = $query->where('as_employees.id', '=', $employeeId);
        }

        $result = $query->join('as_employees', 'as_employees.id', '=','as_bookings.employee_id')
            ->select(['as_bookings.*', 'as_bookings.id as booking_id', 'as_bookings.status as booking_status', 'as_employees.*', 'as_employees.status as employee_status'])
            ->paginate($perPage);

        return $result;
    }

    public static function countCommissionNeedToPay($userId, $status, $employeeId, $perPage, $start, $end)
    {
        $query = self::where('as_bookings.created_at', '>', $start)
            ->where('as_bookings.created_at', '<', $end)
            ->whereNull('as_bookings.deleted_at')
            ->where('as_bookings.status','!=', self::STATUS_CANCELLED)
            ->where('as_bookings.status','!=', self::STATUS_PENDING)
            ->where('as_bookings.status','!=', self::STATUS_NOT_SHOW_UP)
            ->where('as_bookings.user_id', '=', $userId)
            ->where('as_employees.status', '=', $status);

        if(!empty($employeeId)) {
            $query = $query->where('as_employees.id', '=', $employeeId);
        }

        $result = $query->join('as_employees', 'as_employees.id', '=','as_bookings.employee_id')
            ->select(DB::raw('SUM(varaa_as_bookings.total_price) as total'))
            ->first();

        return $result;
    }

}
