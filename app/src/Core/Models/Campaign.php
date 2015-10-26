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

class Campaign extends Base
{
	protected $table = 'as_coupon_campaigns';
	
	public $fillable = [
        'name',
        'discount_type',
        'discount',
        'is_reusable',
        'reusable_code',
        'amount',
        'begin_at',
        'expire_at',
        'is_active'
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
            $code = Str::upper(Str::random(10));
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

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getExpireAtAttribute()
    {
        return new \Carbon\Carbon($this->attributes['expire_at']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function coupons()
    {
        return $this->hasMany('App\Core\Models\Coupon', 'campaign_id');
    }
}