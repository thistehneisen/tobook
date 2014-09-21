<?php namespace App\Appointment\Models;

class ResourceService extends \Eloquent
{
    public $timestamps = false;
    protected $table = 'as_resource_service';

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function resource()
    {
        return $this->belongsTo('App\Appointment\Models\Resource');
    }
}
