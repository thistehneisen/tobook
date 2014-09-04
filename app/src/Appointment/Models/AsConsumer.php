<?php namespace App\Appointment\Models;

class AsConsumer extends \App\Core\Models\Base
{
    protected $table = 'as_consumers';

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function consumer()
    {
       return $this->belongsTo('App\Consumers\Models\Consumer');
    }
}
