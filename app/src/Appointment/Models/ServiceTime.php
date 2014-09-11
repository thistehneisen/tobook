<?php namespace App\Appointment\Models;

class ServiceTime extends \App\Core\Models\Base
{
    protected $table = 'as_service_times';

    public $fillable = ['service_id', 'price', 'length','before','during', 'after', 'description', 'quantity'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function service()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
