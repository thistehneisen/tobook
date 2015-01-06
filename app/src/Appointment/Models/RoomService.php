<?php namespace App\Appointment\Models;

class RoomService extends \Eloquent
{
    public $timestamps = false;
    protected $table = 'as_room_service';

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }

    public function room()
    {
        return $this->belongsTo('App\Appointment\Models\Room');
    }
}
