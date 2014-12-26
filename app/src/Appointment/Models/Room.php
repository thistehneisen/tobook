<?php namespace App\Appointment\Models;

class Room extends \App\Core\Models\Base
{
    protected $table = 'as_rooms';

    public $fillable = ['name', 'description'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function services()
    {
        return $this->belongsToMany(
            'App\Appointment\Models\Service',
            'as_room_service'
        );
    }
}
