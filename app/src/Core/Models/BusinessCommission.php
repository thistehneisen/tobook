<?php namespace App\Core\Models;

use App\Appointment\Models\Booking;
use Carbon\Carbon;
use Log;

class BusinessCommission extends Base
{
    const STATUS_INITIAL   = 'initial';
    const STATUS_SUSPEND   = 'suspend';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    public $fillable = [
        'status',
        'booking_status',
        'commission',
        'constant_commission',
        'new_consumer_commission',
        'deposit_rate',
        'total_price',
        'consumer_status'
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

        $commissions = static::where('booking_status', '=', Booking::STATUS_PENDING)
            ->where('created_at', '<=', $cutoff)
            ->orderBy('id', 'desc')
            ->get();

        Log::info('Found ' . $commissions->count() . ' commissions');

        // Go through all cart details and release them
        foreach ($commissions as $commission) {
            $commission->delete();
        }

        Log::info('Release commissions are done');
    }

    public static function updateStatus($booking, $action = '') {
        $commission = static::where('booking_id', '=', $booking->id)->first();

        if (!empty($commission)) {
            try{
                $commission->booking_status = $booking->status;

                if ($action === 'venue') {
                    $commission->commission = 0;
                }

                $commission->save();
            } catch(\Exception $ex){
                Log::info('Exception : ' . $ex->getMessage());
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
