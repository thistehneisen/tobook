<?php namespace App\Appointment\Models;

class Service extends \App\Core\Models\Base
{
    protected $table = 'as_services';

    public $fillable = ['name', 'price','length','before','during', 'after', 'description', 'is_active'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = (bool) $value;
    }

    public function getIsActiveAttribute()
    {
        return (bool) $this->attributes['is_active'];
    }

    public function setLength()
    {
        $this->length = (int) $this->after + $this->during + $this->before;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Appointment\Models\ServiceCategory');
    }

    public function employees()
    {
        return $this->belongsToMany('App\Appointment\Models\Employee', 'as_employee_service')->withPivot('plustime');
    }

    public function serviceTimes()
    {
        return $this->hasMany('App\Appointment\Models\ServiceTime');
    }

    public function resources()
    {
        return $this->belongsToMany('App\Appointment\Models\Resource', 'as_service_resources');
    }

    /**
     * @see http://laravel.com/docs/eloquent#many-to-many
     */
    public function extraServices(){
        return $this->belongsToMany('App\Appointment\Models\ExtraService', 'as_service_extra_services', 'service_id', 'extra_service_id');
    }
}
