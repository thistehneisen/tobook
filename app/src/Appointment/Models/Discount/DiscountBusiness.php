<?php namespace App\Appointment\Models\Discount;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;
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
            $tomorrow = Carbon::tomorrow();
            $dates = [$tomorrow, $tomorrow->copy()->addDay()];

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
        $now = Carbon::now();
        $startOfDate = $date->copy()->hour(8)->minute(0);
        $endOfDate   = $date->copy()->hour(20)->minute(59);

        $now = $this->compensateNightlyHours($now, $date);

        if (!empty($discount) && ($discount->is_active)) {
            if($now->diffInMinutes($endOfDate)   <= ($discount->before * 60)
            || $now->diffInMinutes($startOfDate) <= ($discount->before * 60)) {
                $hasDiscount = true;
            }
        }
        return $hasDiscount;
    }
}
