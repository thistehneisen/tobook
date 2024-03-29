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

            if (empty($coupon)) {
                return $price;
            }

            if ($coupon->campaign->amount === $coupon->campaign->reusable_usage
                && $coupon->campaign->is_reusable)
            {
                return price;
            }
            
            $now = Carbon::now();

            // Datetime vs date?
            $expireAt = new Carbon($coupon->campaign->expired_at);
            $beginAt  = new Carbon($coupon->campaign->begin_at);

            if($now->gt($expireAt) || $now->lt($beginAt)) {
                return $price;
            }

            if (strval($coupon->campaign->discount_type) === Campaign::DISCOUNT_TYPE_PERCENTAGE) {
                $price = $price - ($price * ($coupon->campaign->discount / 100));
            } else if (strval($coupon->campaign->discount_type) === Campaign::DISCOUNT_TYPE_CASH) {
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

    public function getDiscountAmountAttribute()
    {
        $price = 0;
        
        if ($this->campaign->discount_type === \App\Core\Models\Campaign::DISCOUNT_TYPE_CASH)
        {
            return $this->campaign->discount;
        }

        if (!empty($this->couponBooking->booking->bookingServices()->first())) {
            $service = $this->couponBooking->booking->bookingServices()->first()->selectedService;
            $price = $service->price;
        }
        return $price * $this->campaign->discount/100;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function campaign()
    {
        return $this->belongsTo('App\Core\Models\Campaign');
    }

    public function couponBookings()
    {
        return $this->hasMany('App\Core\Models\CouponBooking');
    }

    public function couponBooking()
    {
        return $this->hasOne('App\Core\Models\CouponBooking');
    }
}