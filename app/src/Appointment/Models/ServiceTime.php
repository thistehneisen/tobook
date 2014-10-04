<?php namespace App\Appointment\Models;

use Config;

class ServiceTime extends \App\Core\Models\Base
{
    protected $table = 'as_service_times';

    public $fillable = ['price', 'length','before','during', 'after', 'description'];


    public function setLength()
    {
        $this->length = (int) $this->after + $this->during + $this->before;
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------

    public function getFormattedPriceAttribute()
    {
        return number_format($this->attributes['price'])
            .Config::get('varaa.currency');
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
