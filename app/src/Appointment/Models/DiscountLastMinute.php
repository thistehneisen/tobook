<?php namespace App\Appointment\Models;

use Config, Util, Validator;
use Carbon\Carbon;

class DiscountLastMinute extends \App\Appointment\Models\Base
{
    protected $table = 'as_last_minute_discounts';

    public $fillable = [
        'is_active',
        'before',
        'discount'
    ];

    protected $rulesets = [
        'saving' => [
            'is_active'=> 'required',
            'before'   => 'required',
            'discount' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
