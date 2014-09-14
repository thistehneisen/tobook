<?php namespace App\FlashDeal\Models;

use Carbon\Carbon;
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
            'service_id'       => 'required',
            'valid_date'       => 'required',
            'discounted_price' => 'required|numeric',
            'quantity'         => 'required|numeric',
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getDiscountPercentAttribute()
    {
        $servicePrice = $this->service->price;
        return round(($servicePrice - $this->attributes['discounted_price']) * 100 / $servicePrice, 2);
    }

    public function getValidDateAttribute()
    {
        return new Carbon($this->attributes['valid_date']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\FlashDeal\Models\Service');
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    public function scopeActive($query)
    {
        return $query->where('valid_date', '>=', Carbon::today());
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_date', '<', Carbon::today());
    }
}
