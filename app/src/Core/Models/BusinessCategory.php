<?php namespace App\Core\Models;

class BusinessCategory extends Base
{
    public $fillable = ['name', 'keywords', 'parent_id'];

    public $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function setNameAttribute($value)
    {
        $value = preg_replace('/\s+/', '_', trim($value));
        $this->attributes['name'] = snake_case($value);
    }

    public function getNameAttribute()
    {
        return trans('user.profile.business_categories.'.$this->attributes['name']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function users()
    {
        return $this->belongsToMany('App\Core\Models\User');
    }

    public function parent()
    {
        return $this->belongsTo('App\Core\Models\BusinessCategory', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Core\Models\BusinessCategory', 'parent_id');
    }
    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
