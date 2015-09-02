<?php namespace App\Appointment\Models;

use Config, Util, Validator;
use Carbon\Carbon;

class Discount extends \App\Appointment\Models\Base
{
    protected $table = 'as_discounts';

      public $fillable = [
        'start_at',
        'end_at',
        'weekday',
        'period',
        'discount'
    ];

    protected $rulesets = [
        'saving' => [
            'start_at' => 'required',
            'end_at'   => 'required',
            'weekday'  => 'required',
            'period'   => 'required',
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
