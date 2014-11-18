<?php namespace App\Appointment\Models\Slot;
use Config, Util;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Slot\Base;

class Frontend extends Base implements Strategy
{
    protected function getValue($key)
    {
        $map = [
            'active'            => 'active',
            'inactive'          => 'inactive',
            'freetime_head'     => 'freetime freetime-head',
            'freetime_body'     => 'freetime freetime-body',
            'resource_inactive' => 'resource fancybox inactive',
            'custom_active'     => 'custom active',
            'custom_inactive'   => 'custom inactive',
            'booked_head'       => ' slot-booked-head',
            'booked_body'       => ' slot-booked-body',
        ];

        return (isset($map[$key])) ? $map[$key] : '';
    }
}
