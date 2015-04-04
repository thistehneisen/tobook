<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use App\Appointment\Models\Reception\BackendReceptionist;

class FlashDeal extends \App\Appointment\Models\Base
{
    protected $table = 'as_flash_deals';

    public $fillable = [
        'date',
        'start_at',
        'end_at',
        'discount_percentage',
        'status'
    ];

    protected $rulesets = [
        'saving' => [
            'discount_percentage' => 'required|numeric',
        ]
    ];

    const STATUS_ACTIVE      = 1;
    const STATUS_CLAIMED     = 2;
    const STATUS_DELETED     = 3;

    /**
     * Function to check if we can create a new flash deal with this service in certain date/time.
     * It needs to check each services, since different service may require different resources
     */
    public static function canMakeFlashDeal($user, $employeeId, $serviceId, $bookingDate, $startTime, $length)
    {
        $exception = [];
        try{
            $receptionist = new BackendReceptionist();
            $receptionist->setBookingId(0)
                ->setUUID(null)
                ->setUser($user)
                ->setBookingDate($bookingDate)
                ->setStartTime($startTime)
                ->setServiceId($serviceId)
                ->setModifyTime(0)
                ->setEmployeeId($employeeId)
                ->setServiceTimeId('default')
                ->setBookingServiceId(0)
                ->setIsRequestedEmployee(null);

            $receptionist->validateBooking();

        } catch(\Exception $ex){
            $exception[] = $ex->getMessage();
        }
        return (empty($exception)) ? true : false;
    }

    /**
     * Delete all flash deals of an employee based on specific date, time
     *
     * @author hung
     * @param int $employeeId
     * @param string $date
     * @param Carbon $startTime
     * @param Carbon $endTime
     *
     * @return void
     */
    public static function deleteFlashDeal($employeeId, $date, $startTime, $endTime)
    {
        $query = self::where('date', $date)
            ->where('employee_id', $employeeId)
            ->whereNull('deleted_at')
            ->where('status', self::STATUS_ACTIVE);

        $query = $query->where(function ($query) use ($startTime, $endTime) {
            return $query->where(function ($query) use ($startTime, $endTime) {
                return $query->where('start_at', '>=', $startTime->toTimeString())
                     ->where('start_at', '<', $endTime->toTimeString());
            })->orWhere(function ($query) use ($endTime, $startTime) {
                 return $query->where('end_at', '>', $startTime->toTimeString())
                      ->where('end_at', '<=', $endTime->toTimeString());
            })->orWhere(function ($query) use ($startTime) {
                 return $query->where('start_at', '<', $startTime->toTimeString())
                      ->where('end_at', '>', $startTime->toTimeString());
            })->orWhere(function ($query) use ($startTime, $endTime) {
                 return $query->where('start_at', '=', $startTime->toTimeString())
                      ->where('end_at', '=', $endTime->toTimeString());
            });
        });

        $flashDeals = $query->get();

        foreach ($flashDeals as $flashDeal) {
            $flashDeal->status = self::STATUS_DELETED;
            $flashDeal->save();
            $flashDeal->delete();
        }
    }

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

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function employee()
    {
       return $this->belongsTo('App\Appointment\Models\Employee');
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
