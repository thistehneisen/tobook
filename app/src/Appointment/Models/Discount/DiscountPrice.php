<?php namespace App\Appointment\Models\Discount;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;
use Carbon\Carbon;

trait DiscountPrice {

    /**
     * Get associative array of Carbon weekday and theirs abbreviation in English
     *
     * @return array()
     */
    public function getWeekdaysArray()
    {
        $weekdays  = [
            Carbon::MONDAY    => 'mon',
            Carbon::TUESDAY   => 'tue',
            Carbon::WEDNESDAY => 'wed',
            Carbon::THURSDAY  => 'thu',
            Carbon::FRIDAY    => 'fri',
            Carbon::SATURDAY  => 'sat',
            Carbon::SUNDAY    => 'sun'
        ];

        return $weekdays;
    }

    /**
     * Get weekday abbreviation in English by Carbon weekday int value
     *
     * @param int $weekday
     * @return string
     */
    public function getWeekdayAbbr($weekday)
    {
        $weekdays = $this->getWeekDaysArray();

        return $weekdays[$weekday];
    }

    /**
     * Add more hours to time because we skip nightly time from 20:00 to next day 8:00
     *
     * @param Carbon $time
     * @param Carbon $date
     * @return Carbon
     */
    public function compensateNightlyHours(Carbon $time, Carbon $date)
    {
        $startOfToday = $time->copy()->hour(0)->minute(0);
        $endOfStart   = $date->copy()->hour(23)->minute(59);

        if ($startOfToday->diffInDays($endOfStart) === 0) {
            if ($time->hour < 8) {
                $time->addHours((8 - $time->hour));
            }
        }

        if ($startOfToday->diffInDays($endOfStart) === 1) {
            if ($time->hour < 8) {
                $time->addHours((8 - $time->hour) + 12);
            } elseif ($time->hour > 20) {
                $time->addHours((24 - $time->hour) + 8);
            } else{
                $time->addHours(12);
            }
        }

        if ($startOfToday->diffInDays($endOfStart) === 2) {
            if ($time->hour < 8) {
                $time->addHours((8 - $time->hour) + 24);
            } elseif ($time->hour > 20) {
                $time->addHours((24 - $time->hour) + 8 + 12);
            } else {
                $time->addHours(24);
            }
        }

        return $time;
    }

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

        $isDiscountIncluded = ($this instanceof \App\Appointment\Models\ServiceTime)
            ? $this->service->is_discount_included
            : $this->is_discount_included;

        if($isDiscountIncluded === false) {
            return $this->price;
        }

        $startTime = ($time instanceof Carbon)
            ? Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $date->toDateString(), $time->toTimeString()))
            : Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s:00', $date->toDateString(), $time));

        $now     = Carbon::now();
        $weekday = $this->getWeekdayAbbr($date->dayOfWeek);

        $formatted = $startTime->toTimeString();

        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where(function($query) use($weekday, $formatted) {
                $query->where('start_at', '<=', $formatted)
                ->where('end_at', '>=', $formatted);
            })->where('weekday', '=', $weekday)->where('is_active', '=', 1)->first();

        $discountLastMinute = DiscountLastMinute::find($this->user->id);
        $price = $this->price;

        if (!empty($discount)) {
            $price = (double) $this->price * (1 - ((double) $discount->discount / 100));
        }

        $now = $this->compensateNightlyHours($now, $date);

        if (!empty($discountLastMinute) && $discountLastMinute->is_active) {
            if ($now->diffInMinutes($startTime) <= ($discountLastMinute->before * 60)) {
                $price = (double)  $this->price * (1 - ((double) $discountLastMinute->discount / 100));
            }
        }

        return $price;
    }

    /**
     * Check if the given date has any possible discount
     *
     * @param Carbon $date
     * @return booelan
     */
    public function hasDiscount($date)
    {
        $hasDiscount = false;
        $now = Carbon::now();

        if($this instanceof \App\Appointment\Models\ServiceTime) {
            $this->user = $this->service->user;
        }
        $startOfDate = $date->copy()->hour(8)->minute(0);
        $endOfDate   = $date->copy()->hour(20)->minute(59);

        if ($endOfDate->lt($now)) {
            return $hasDiscount;
        }

        $weekday = $this->getWeekdayAbbr($date->dayOfWeek);

        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where('weekday', '=', $weekday)
            ->where('is_active', '=', 1)
            ->where('discount', '>', 0)->first();

        if (!empty($discount)){
            $hasDiscount = true;
        }

        $discountLastMinute = DiscountLastMinute::find($this->user->id);

        $now = $this->compensateNightlyHours($now, $date);

        if (!empty($discountLastMinute) && ($discountLastMinute->is_active)) {
            if($now->diffInMinutes($endOfDate)   <= ($discountLastMinute->before * 60)
            || $now->diffInMinutes($startOfDate) <= ($discountLastMinute->before * 60)) {
                $hasDiscount = true;
            }
        }

        return $hasDiscount;
    }

}
