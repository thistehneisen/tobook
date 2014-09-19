<?php namespace App\Appointment\Models;
class CustomTime extends \App\Appointment\Models\Base
{
    protected $table = 'as_custom_times';

    public $fillable = ['name', 'start_at', 'end_at', 'is_day_off'];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
