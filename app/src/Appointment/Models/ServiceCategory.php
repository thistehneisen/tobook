<?php namespace App\Appointment\Models;
class ServiceCategory extends \App\Core\Models\Base
{
    protected $table = 'as_service_categories';

    protected $dates = ['deleted_at'];

    public $fillable = ['name', 'description', 'is_show_front'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    public function isDeletable()
    {
        return ($this->services->isEmpty()) ? true : false;
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setIsShowFrontAttribute($value)
    {
        $this->attributes['is_show_front'] = (bool) $value;
    }

    public function getIsShowFrontAttribute()
    {
        return (isset($this->attributes['is_show_front'])) ? (bool) $this->attributes['is_show_front'] : true;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service', 'category_id');
    }
}
