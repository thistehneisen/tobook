<?php namespace App\Core\Models;

class BusinessCommission extends Base
{
    const STATUS_SUSPEND   = 'suspend';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    public $fillable = [
        'status',
        'amount'
    ];

    public $rulesets = [
        'saving' => [
            'status' => 'required',
            'amount' => 'required|numeric',
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

}
