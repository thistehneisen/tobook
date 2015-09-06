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

        $isDiscountIncluded = ($this instanceof \App\Appointment\Models\ServiceTime)
            ? $this->service->is_discount_included
            : $this->is_discount_included;

        if($isDiscountIncluded === false) {
            return $this->price;
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
            })->where('weekday', '=', $weekday)->where('is_active', '=', 1)->first();

        $discountLastMinute = DiscountLastMinute::find($this->user->id);
        $price = $this->price;

        if (!empty($discount)) {
            $price = (double) $this->price * (1 - ((double) $discount->discount / 100));
        }
        $startOfToday = $now->copy()->hour(0)->minute(0);
        $startOfStart = $startTime->copy()->hour(23)->minute(59);

        if ($startOfToday->diffInDays($startOfStart) === 0) {
            if ($now->hour < 8) {
                $now->addHours((8 - $now->hour));
            }
        }

        if ($startOfToday->diffInDays($startOfStart) === 1) {
            if ($now->hour < 8) {
                $now->addHours((8 - $now->hour) + 12);
            } elseif ($now->hour > 20) {
                $now->addHours((24 - $now->hour) + 8);
            } else{
                $now->addHours(12);
            }
        }

        if ($startOfToday->diffInDays($startOfStart) === 2) {
            if ($now->hour < 8) {
                $now->addHours((8 - $now->hour) + 24);
            } elseif ($now->hour > 20) {
                $now->addHours((24 - $now->hour) + 8 + 12);
            } else {
                $now->addHours(24);
            }
        }

        if (!empty($discountLastMinute) && $discountLastMinute->is_active) {
            if ($now->diffInHours($startTime) <= $discountLastMinute->before) {
                $price = (double)  $this->price * (1 - ((double) $discountLastMinute->discount / 100));
            }
        }

        return $price;
    }

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

        $weekdays = [
            Carbon::MONDAY    => 'mon',
            Carbon::TUESDAY   => 'tue',
            Carbon::WEDNESDAY => 'wed',
            Carbon::THURSDAY  => 'thu',
            Carbon::FRIDAY    => 'fri',
            Carbon::SATURDAY  => 'sat',
            Carbon::SUNDAY    => 'sun'
        ];

        $weekday = $weekdays[$date->dayOfWeek];
        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where('weekday', '=', $weekday)
            ->where('is_active', '=', 1)
            ->where('discount', '>', 0)->first();

        if (!empty($discount)){
            $hasDiscount = true;
        }

        $discountLastMinute = DiscountLastMinute::find($this->user->id);

        $startOfToday = $now->copy()->hour(0)->minute(0);
        $startOfStart = $date->copy()->hour(23)->minute(59);

        if ($startOfToday->diffInDays($startOfStart) === 0) {
            if ($now->hour < 8) {
                $now->addHours((8 - $now->hour));
            }
        }

        if ($startOfToday->diffInDays($startOfStart) === 1) {
            if ($now->hour < 8) {
                $now->addHours((8 - $now->hour) + 12);
            } elseif ($now->hour > 20) {
                $now->addHours((24 - $now->hour) + 8);
            } else{
                $now->addHours(12);
            }
        }

        if ($startOfToday->diffInDays($startOfStart) === 2) {
            if ($now->hour < 8) {
                $now->addHours((8 - $now->hour) + 24);
            } elseif ($now->hour > 20) {
                $now->addHours((24 - $now->hour) + 8 + 12);
            } else {
                $now->addHours(24);
            }
        }

        if (!empty($discountLastMinute) && ($discountLastMinute->is_active)) {
            if($now->diffInHours($endOfDate)   <= $discountLastMinute->before
            || $now->diffInHours($startOfDate) <= $discountLastMinute->before) {
                $hasDiscount = true;
            }
        }

        return $hasDiscount;
    }

}
