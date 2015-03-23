<?php namespace App\Appointment\Models;

use App\Core\Models\Base;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class FlashDeal extends Base
{
    protected $table = 'as_flash_deals';

    public $fillable = [
        'discounted_price',
    ];

    protected $rulesets = [
        'saving' => [
            'service_id'       => 'required',
            'discounted_price' => 'required|numeric'
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getDiscountPercentAttribute()
    {
        $servicePrice = $this->service->price;

        return round(($servicePrice - $this->attributes['discounted_price']) * 100 / $servicePrice, 0);
    }

    public function getNormalPriceAttribute()
    {
        return $this->service->price;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    /**
     * Get flash deals of a business
     *
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  App\Core\Models\Business $business
     *
     * @return Illuminate\Support\Collection
     */
    public function scopeOfBusiness($query, $business)
    {
        $id = $business instanceof \App\Core\Models\Business
            ? $business->user_id
            : $business->id;
        return $query->where('user_id', $id);
    }
}
