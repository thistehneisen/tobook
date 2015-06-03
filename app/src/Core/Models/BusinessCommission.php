<?php namespace App\Core\Models;

class BusinessCommission extends Base
{
    const STATUS_INITIAL   = 'initial';
    const STATUS_SUSPEND   = 'suspend';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    public $fillable = [
        'status',
        'commission',
        'constant_commission',
        'new_consumer_commission',
        'deposit_rate',
        'total_price',
        'consumer_status'
    ];

    public $rulesets = [
        'saving' => [
            'status'                  => 'required',
            'commission'              => 'required|numeric',
            'constant_commission'     => 'numeric',
            'new_consumer_commission' => 'numeric',
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getAmountAttribute()
    {
        return number_format($this->attributes['amount'], 2);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

}