<?php namespace App\Core\Models;

use App\Appointment\Models\Booking;
use Carbon\Carbon;
use Log;
use Settings;
use Config, App;

class BusinessCommission extends Base
{
    protected $table = 'business_commissions'; 
    
    const STATUS_INITIAL   = 'initial';
    const STATUS_SUSPEND   = 'suspend';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_FULL    = 'full';
    const PAYMENT_DEPOSIT = 'deposit';
    const PAYMENT_VENUE   = 'venue';

    public $fillable = [
        'status',
        'booking_status',
        'commission',
        'constant_commission',
        'new_consumer_commission',
        'deposit_rate',
        'total_price',
        'consumer_status',
        'payment_type',
    ];

    public $rulesets = [
        'saving' => [
            'status'                  => 'required',
            'booking_status'          => 'required',
            'commission'              => 'required|numeric',
            'constant_commission'     => 'numeric',
            'new_consumer_commission' => 'numeric',
        ]
    ];

    public static function releaseCommission(Carbon $cutoff) {
        Log::info('Started to unlock commissions items');

        $commissions = self::where('business_commissions.booking_status','!=', Booking::STATUS_PAID)
            ->where('business_commissions.booking_status', '!=', Booking::STATUS_CONFIRM)
            ->where('created_at', '<=', $cutoff)
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        Log::info('Found ' . $commissions->count() . ' commissions');

        // Go through all cart details and release them
        foreach ($commissions as $commission) {
            $commission->delete();
        }

        Log::info('Release commissions are done');
    }

    public static function updateCommission($booking, $paymentType = '') {
        $commission = self::where('booking_id', '=', $booking->id)->first();

        if (!empty($commission)) {
            try{
                $commissionRate   = Settings::get('commission_rate');
                $commissionAmount = $booking->total_price * $commissionRate;

                Log::info('Deposit: ', [$booking->deposit]);

                if (App::environment() === 'tobook' || Config::get('varaa.commission_style') === 'tobook') {
                    if ($booking->deposit > 0) {
                        Log::info('Update deposit commission');
                        $commissionAmount  = $booking->deposit * $commissionRate;
                    }
                }

                $commission->commission     = $commissionAmount;
                $commission->booking_status = $booking->status;
                $commission->payment_type   = $paymentType;

                if ($paymentType === self::PAYMENT_VENUE) {
                    if (App::environment() === 'tobook') {
                        $commission->commission = 0;
                    } else {
                        //payment pending = total price - commission price
                        //to depict business owe we -$commisionAmount
                        //we need to add total price to commission amount
                        $commission->commission = $booking->total_price + $commissionAmount;
                    }
                }

                $commission->save();
            } catch(\Exception $ex){
                Log::info('Update Commission Exception : ' . $ex->getMessage());
            }
        }
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getAmountAttribute()
    {
        return number_format($this->attributes['amount'], 2);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function booking()
    {
        return $this->belongsTo('App\Appointment\Models\Booking');
    }

    public function employee()
    {
        return $this->belongsTo('App\Appointment\Models\Employee');
    }

}
