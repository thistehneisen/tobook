<?php namespace App\Appointment\Models;

class Booking extends \App\Core\Models\Base
{
    protected $table = 'as_bookings';

    public $fillable = [
        'date',
        'total',
        'start_at',
        'end_at',
        'status',
        'ip',
    ];

    const STATUS_CONFIRM   = 1;
    const STATUS_PENDDING  = 2;
    const STATUS_CANCELLED = 3;

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

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

    public function bookingServices()
    {
        return $this->hasMany('App\Appointment\Models\BookingService');
    }
}
