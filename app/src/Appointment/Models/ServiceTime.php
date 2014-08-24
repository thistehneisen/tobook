<?php namespace App\Appointment\Models;

class Service extends \App\Core\Models\Base
{
    protected $table = 'as_services';

    public $fillable = ['name', 'description', 'quantity'];

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
}
