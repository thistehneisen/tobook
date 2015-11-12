<?php namespace App\Core\Models;

use App;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Discount\DiscountBusiness;
use App\Core\Models\Relations\BusinessBusinessCategory;
use Carbon\Carbon;
use Config;
use Illuminate\Support\Collection;
use Input;
use Settings, Str, Util;
use Validator;
use DB;

class Campaign extends Base
{
	protected $table = 'as_coupon_campaigns';
	
    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    const DISCOUNT_TYPE_CASH       = 'amount';

	public $fillable = [
        'name',
        'discount_type',
        'discount',
        'is_reusable',
        'reusable_code',
        'reusable_usage',
        'amount',
        'begin_at',
        'expire_at',
        'is_active',
    ];

    public $rulesets = [
        'saving' => [
            'name' => 'required',
            'discount' => 'required',
            'amount' => 'required',
            'discount_type' => 'required',
            'expire_at' => 'required',
        ]
    ];


    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------

    public function generateCodes($amount = 0)
    {  
        if ($amount < 1) return [];
        
        $codes = [];

        while($amount) {
            $code = Str::upper(Str::random(16));
            if( ! in_array($code, $codes)) {
                $codes[] = $code;
                $amount--;
            }
        }

        return $codes;
    }

    public function makeCoupons()
    {
        $amount = $this->amount;

        $codes = $this->generateCodes($amount);

        foreach ($codes as $code) {
           $this->makeCoupon($code);
        }
    }

    public function makeCoupon($code)
    {
        $coupon = new Coupon;
        $coupon->fill(['code' => $code]);
        $coupon->campaign()->associate($this)->save();
    }

    public function getResuableCodeValidator()
    {
        $field = [
            'reusable_code' => Input::get('reusable_code'),
        ];

        $validator = [
            'reusable_code' => ['required'],
        ];

        return Validator::make($field, $validator);
    }

    public function getBarChartData()
    {
        $bookings = Booking::join('as_coupon_booking', 'as_coupon_booking.booking_id', '=', 'as_bookings.id')
            ->join('as_coupons', 'as_coupons.id','=','as_coupon_booking.coupon_id')
            ->where('as_coupons.campaign_id', '=', $this->id)
            ->select('as_bookings.created_at', DB::raw('count(varaa_as_coupon_booking.coupon_id) as couponCount'))
            ->groupBy(DB::raw('DATE(varaa_as_bookings.created_at)'))->get();

        $data = [];

        foreach ($bookings as $booking) {
            $data[] = [
                'date' => $booking->created_at->toDateString(),
                'used' => $booking->couponCount
            ];
        }

        return json_encode($data);
    }

    public function getPieChartData()
    {
        $total = $this->coupons()->where('is_used', '=', 0)->count();
        $used = $this->coupons()->where('is_used', '=', 1)->count();

        return json_encode([$total, $used]);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getExpireAtAttribute()
    {
        return new Carbon($this->attributes['expire_at']);
    }

    public function getBeginAtAttribute()
    {
        return new Carbon($this->attributes['begin_at']);
    }

    public function getIsReusableAttribute()
    {
        return (boolean) $this->attributes['is_reusable'];
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function coupons()
    {
        return $this->hasMany('App\Core\Models\Coupon', 'campaign_id');
    }
}