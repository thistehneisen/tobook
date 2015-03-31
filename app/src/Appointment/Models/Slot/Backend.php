<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\FlashDeal;
use App\Appointment\Models\Slot\Base;

class Backend extends Base implements Strategy
{
    public function flashDealClass()
    {
        // get booking only certain date
        if (empty($this->flashDealCache[$this->date])) {
            $this->flashDealCache[$this->date] = FlashDeal::where('date', $this->date)
                ->where('employee_id', $this->employee->id)
                ->whereNull('deleted_at')->get();
        }

        foreach ($this->flashDealCache[$this->date] as $flashDeal) {
            if ($flashDeal->date === $this->date) {
                $subMinutes = 15;//15 is duration of single slot
                $start      = $flashDeal->getStartAt();
                $end        = $flashDeal->getEndAt()->subMinutes($subMinutes);

                if(($start->minute % 15) > 0)
                {
                    $complement = $start->minute % 15;
                    $start->subMinutes($complement);
                }

                if(($end->minute % 15) > 0)
                {
                    $complement = 15 - ($end->minute % 15);
                    $end->addMinutes($complement);
                }

                if ($this->rowTime >= $start && $this->rowTime <= $end) {
                    $this->class .= $this->getValue('flashdeal');
                }
            }
        }
        return $this->class;
    }
}
