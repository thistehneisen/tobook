<?php namespace App\Appointment\Models;

class ServiceCategory extends BaseModel
{
    protected $table = 'as_service_categories';

    public function isShowFront()
    {
       return  ($this->is_show_front) ? 'On' : 'Off';
    }
}
