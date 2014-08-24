<?php namespace App\Appointment\Models;

class Employee extends \App\Core\Models\Base
{
    protected $table = 'as_employees';

    public $fillable = ['name', 'email', 'phone', 'avatar', 'description', 'is_subscribed_email', 'is_subscribed_sms', 'is_active'];

    protected $rulesets = [
        'saving' => [
            'name'  => 'required',
            'email' => 'required|email',
            'phone' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsActive($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getIsActive()
    {
        return (bool) $this->attributes['is_active'];
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
