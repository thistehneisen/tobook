<?php namespace App\Appointment\Models;

class Resource extends \App\Core\Models\Base
{
    protected $table = 'as_resources';

    public $fillable = ['name', 'quantity', 'description'];

    protected $rulesets = [
        'saving' => [
            'name'     => 'required',
            'quantity' => 'numeric'
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
            'as_resource_service'
        );
    }
}
