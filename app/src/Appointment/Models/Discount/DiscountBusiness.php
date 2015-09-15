<?php namespace App\Appointment\Models\Discount;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;
use App\Appointment\Planner\Virtual;
use Carbon\Carbon;

trait DiscountBusiness {

    use DiscountPrice;

    public function getHasDiscountAttribute()
    {
        $hasDiscount = false;

        // if the business has any day with discount greater than zero
        $discount = Discount::where('user_id', '=', $this->user->id)
            ->where('discount', '>', 0)->first();

        if (!empty($discount)){
            $hasDiscount = true;
        }

        if (!$hasDiscount) {
            $dates = [Carbon::now(), Carbon::tomorrow()];

            $discountLastMinute = DiscountLastMinute::find($this->user->id);

            foreach ($dates as $date) {
                $hasDiscount = $this->hasLastMinuteDiscount($date, $discountLastMinute);

                if($hasDiscount) break;
            }
        }

        return $hasDiscount;
    }

    private function hasLastMinuteDiscount($date, $discount)
    {
        $hasDiscount = false;

        if (empty($discount) || (!$discount->is_active)) {
            return $hasDiscount;
        }

        $virtual     = new Virtual();
        $startOfDate = $date->copy()->hour(8)->minute(0);
        $endOfDate   = $date->copy()->hour(20)->minute(59);

        $now = Carbon::now();
        $now = $this->compensateNightlyHours($now, $date);

        $timeslots = $virtual->getTimeslots($this->user, $date);

        foreach ($timeslots as $timeslot) {
            list($hour, $minute) = array_map('intval', explode(':', $timeslot));
            $slot = $date->copy()->hour($hour)->minute($minute);
            if($now->diffInMinutes($slot) <= ($discount->before * 60)) {
                $hasDiscount = true;
            }
        }

        return $hasDiscount;
    }
}
