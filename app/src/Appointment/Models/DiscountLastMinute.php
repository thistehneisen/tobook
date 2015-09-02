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

    public static function createFormData(&$data)
    {
        for ($i=5; $i <= 100 ; $i+=5) {
            $data['discount'][$i] = sprintf('%d %%', $i);
        }

        for ($i=1; $i <= 24 ; $i++) {
            $data['before'][$i] = sprintf('%d %s', $i, trans('as.options.discount.business-hours'));
        }
        return $data;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
