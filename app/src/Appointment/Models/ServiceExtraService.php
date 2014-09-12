<?php namespace App\Appointment\Models;

class ServiceExtraService extends \App\Core\Models\Base
{
    public $timestamps = false;
    protected $table = 'as_extra_service_service';

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function extraService()
    {
        return $this->belongsTo('App\Appointment\Models\ExtraService');
    }
}
