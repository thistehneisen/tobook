<?php namespace App\Appointment\Models\Slot;
use Config, Util;

class Next extends Base implements Strategy
{
    protected function getValue($key)
    {
        $map = [
            'active'          => 15,
            'inactive'        => 0,
            'freetime'        => 0,
            'custom_active'   => 15,
            'custom_inactive' => 0,
            'booked_head'     => 0,
            'booked_body'     => 0,
        ];

        return (isset($map[$key])) ? $map[$key] : '';
    }
}
