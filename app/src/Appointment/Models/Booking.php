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
    const STATUS_PENDDING  = 2;
    const STATUS_CANCELLED = 3;

    //Implement methods in SplSubject
    protected $_observers = [];

    public function attach(\SplObserver $observer) {
        $id = spl_object_hash($observer);
        $this->_observers[$id] = $observer;
    }

    public function detach(\SplObserver $observer) {
        $id = spl_object_hash($observer);

        if (isset($this->_observers[$id])) {
            unset($this->_observers[$id]);
        }
    }

    public function notify() {
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
        switch ($statusText) {
            case 'confirmed':
                return self::STATUS_CONFIRM;
            case 'pending':
                return self::STATUS_PENDDING;
            case 'cancelled':
                return self::STATUS_CANCELLED;
            default:
                break;
        }
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
