<?php namespace App\Appointment\Models;

class ServiceTime extends \App\Core\Models\Base
{
    protected $table = 'as_service_times';

    public $fillable = ['price', 'length','before','during', 'after', 'description'];


    public function setLength()
    {
        $this->length = (int) $this->after + $this->during + $this->before;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
