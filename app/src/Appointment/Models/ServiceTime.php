<?php namespace App\Appointment\Models;

class ServiceTime extends \App\Core\Models\Base
{
    protected $table = 'as_service_times';

    public $fillable = ['price', 'length','before','during', 'after', 'description'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
