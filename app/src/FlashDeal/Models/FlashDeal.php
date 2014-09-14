<?php namespace App\FlashDeal\Models;

use Carbon\Carbon;
use App\Core\Models\Base;

class FlashDeal extends Base
{
    protected $table = 'fd_flash_deals';
    public $fillable = [
        'discounted_price',
        'quantity',
    ];
    protected $rulesets = [
        'saving' => [
            'service_id'       => 'required',
            'discounted_price' => 'required|numeric',
            'quantity'         => 'required|numeric',
        ]
    ];

    public function getDiscountPercentAttribute()
    {
        $servicePrice = $this->service->price;
        return round(($servicePrice - $this->attributes['discounted_price']) * 100 / $servicePrice, 2);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\FlashDeal\Models\Service');
    }

    public function dates()
    {
        return $this->hasMany('App\FlashDeal\Models\FlashDealDate');
    }

    public function scopeActive($query)
    {
        return $query->where('quantity', '>', 0)
            ->whereHas('dates', function($q) {
                $now = Carbon::now();
                return $q->where('date', '<=', $now->toDateString())
                    ->where('time', '<', $now->toTimeString());
            });
    }
}
