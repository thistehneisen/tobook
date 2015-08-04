<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Slot\Base;

class Frontend extends Base implements Strategy
{
    public function determineClass($date, $hour, $minute, $employee, $service = null){
        $bookingDate = with(new Carbon($date))->hour($hour)->minute($minute);
        if($bookingDate < Carbon::now()) {
            $this->class =  $this->getValue('inactive');
        } else {
            $this->class = parent::determineClass($date, $hour, $minute, $employee, $service);
        }
        return $this->class;
    }

    protected function getValue($key)
    {
        $map = [
            'active'                => 'active',
            'inactive'              => 'inactive',
            'freetime_head'         => ' freetime freetime-head',
            'freetime_body'         => ' freetime freetime-body',
            'freetime_working_head' => ' freetime freetime-head freetime-working',
            'freetime_working_body' => ' freetime freetime-body freetime-working',
            'resource_inactive'     => 'resource fancybox inactive',
            'room_inactive'         => 'room fancybox inactive',
            'custom_active'         => 'custom active',
            'custom_inactive'       => 'custom inactive',
            'booked_head'           => ' slot-booked-head',
            'booked_body'           => ' slot-booked-body',
        ];

        return (isset($map[$key])) ? $map[$key] : '';
    }
}
