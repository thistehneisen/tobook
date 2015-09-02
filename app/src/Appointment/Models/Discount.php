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

    public static function createFormData(&$data)
    {
        $data['discount'][0] = trans('as.options.discount.full-price');
        for ($i=5; $i <= 100 ; $i+=5) {
            $data['discount'][$i] = sprintf('%d %%', $i);
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
