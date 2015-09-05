<?php namespace App\Appointment\Models\Discount;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;
use Carbon\Carbon;

trait DiscountPrice {

    /**
     * Calculate discount price based on booking time
     *
     * @date booking date
     * @time booking time
     * @return double price
     */
    public function getDiscountPrice($date, $time)
    {
        if($this instanceof \App\Appointment\Models\ServiceTime) {
            $this->user = $this->service->user;
        }

        $startTime = ($time instanceof Carbon)
            ? $time
            : Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s:00', $date->toDateString(), $time));

        $weekdays  = [
            Carbon::MONDAY    => 'mon',
            Carbon::TUESDAY   => 'tue',
            Carbon::WEDNESDAY => 'wed',
            Carbon::THURSDAY  => 'thu',
            Carbon::FRIDAY    => 'fri',
            Carbon::SATURDAY  => 'sat',
            Carbon::SUNDAY    => 'sun'
        ];
        $weekday   = $weekdays[$startTime->dayOfWeek];

        $now       = Carbon::now();
        $formatted = sprintf('%02d:%02d:00', $startTime->hour, $startTime->minute);
        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where(function($query) use($weekday, $formatted) {
                $query->where('start_at', '<=', $formatted)
                ->where('end_at', '>=', $formatted);
            })->where('weekday', '=', $weekday)->first();

        $discountLastMinute = DiscountLastMinute::find($this->user->id);
        $price = $this->price;

        if (!empty($discount)) {
            $price = (double) $this->price * (1 - ((double) $discount->discount / 100));
        }

        if (!empty($discountLastMinute)) {
            if($discountLastMinute->is_active
                && $now->diffInHours($startTime) <= $discountLastMinute->before) {
                $price = (double)  $this->price * (1 - ((double) $discountLastMinute->discount / 100));
            }
        }

        return $price;
    }

}
