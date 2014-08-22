<?php namespace App\Appointment\Models;

class ServiceCategory extends BaseModel
{
    protected $table = 'as_service_categories';

    public $fillable = ['name', 'description', 'is_show_front', 'user_id'];

    protected $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    public function setIsShowFrontAttribute($value)
    {
        $this->attributes['is_show_front'] = (bool) $value;
    }

    public function getIsShowFrontAttribute($value)
    {
        return (bool) $this->attributes['is_show_front'];
    }
}
