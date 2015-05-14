<?php namespace App\Core\Models;

class CommissionLog extends \AppModel
{
    const ACTION_ADD = 'add';
    const ACTION_SUBTRACT = 'subtract';

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
        return $this->belongsTo('App\Core\Models\Booking');
    }

}
