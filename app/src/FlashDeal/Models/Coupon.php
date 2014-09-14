<?php namespace App\FlashDeal\Models;

use App\Core\Models\Base;

class Coupon extends Base
{
    protected $table = 'fd_coupons';
    public $fillable = [
        'discounted_price',
        'valid_date',
        'quantity',
    ];
    protected $rulesets = [
        'saving' => [
            'valid_date'       => 'required',
            'discounted_price' => 'required|numeric',
            'quantity'         => 'required|numeric',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\FlashDeal\Models\Service');
    }
}
