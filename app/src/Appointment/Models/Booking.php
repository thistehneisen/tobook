<?php namespace App\Appointment\Models;

class Booking extends \App\Core\Models\Base implements \SplSubject
{
    protected $table = 'as_bookings';

    public $fillable = [
        'date',
        'total',
        'modify_time',
        'start_at',
        'end_at',
        'status',
        'uuid',
        'ip',
    ];

    const STATUS_CONFIRM   = 1;
    const STATUS_PENDING  = 2;
    const STATUS_CANCELLED = 3;

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

    protected function getStatuses()
    {
        return [
            'confirmed' => trans('as.bookings.confirmed'),
            'pending'   => trans('as.bookings.pending'),
            'cancelled' => trans('as.bookings.cancelled')
        ];
    }

    protected function getStatus($statusText)
    {
        $map = [
            'confirmed' => self::STATUS_CONFIRM,
            'pending'   => self::STATUS_PENDING,
            'cancelled' => self::STATUS_CANCELLED,
        ];

        return isset($map[$statusText]) ? $map[$statusText] : null;
    }

    public static function getStatusByValue($value)
    {
        $map = [
            static::STATUS_CONFIRM   => 'confirmed',
            static::STATUS_PENDING  => 'pending',
            static::STATUS_CANCELLED => 'cancelled',
        ];

        return isset($map[$value]) ? $map[$value] : null;
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
}
