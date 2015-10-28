<?php namespace App\Core\Models;

use App;
use App\Core\Models\Campaign;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Discount\DiscountBusiness;
use App\Core\Models\Relations\BusinessBusinessCategory;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Collection;
use Input;
use Settings;
use Str;
use Util;

class Coupon extends Base
{
	protected $table = 'as_coupons';
    
	public $fillable = [
        'code',
        'is_used',
    ];


    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function computePrice($code, $price)
    {
        # Calculate discount price from coupon and from other rules
        if (!empty($code) && (boolean) Settings::get('coupon')) {
            $coupon = self::where('code', '=', $code)
                ->where('is_used', '=', 0)->with('campaign')->first();

            $now = Carbon::now();

            // Datetime vs date?
            $expireAt = new Carbon($coupon->campaign->expired_at);
            $beginAt  = new Carbon($coupon->campaign->begin_at);

            if($now->gt($expireAt) || $now->lt($beginAt)) {
                return $price;
            }

            if ($coupon->campaign->discount_type === Campaign::DISCOUNT_TYPE_PERCENTAGE) {
                $price = $price - ($price * ($coupon->campaign->discount / 100));
            } else if ($coupon->campaign->discount_type === Campaign::DISCOUNT_TYPE_CASH) {
                # What if total price is negative?
                $price -= $coupon->campaign->discount;
            }
        }
        return $price;
    }


    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getIsUsedAttribute()
    {
        return ((boolean) $this->attributes['is_used']) ? 'used' : 'not_used';
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function campaign()
    {
        return $this->belongsTo('App\Core\Models\Campaign');
    }
}