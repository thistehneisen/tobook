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

    public function getKeywordsAttribute()
    {
        $keywords = [];
        if (!empty($this->attributes['keywords'])) {
            $tokens = explode(',', $this->attributes['keywords']);
            foreach ($tokens as $keyword) {
                $keywords[] = trim($keyword);
            }
        }
        return $keywords;
    }

    public function getIconAttribute()
    {
        $name = $this->attributes['name'];
        $icon = '';
        switch ($name) {
            case 'home':
                $icon = 'home';
                break;
            case 'car':
                $icon = 'car';
                break;
            case 'restaurant':
                $icon = 'cutlery';
                break;
            case 'wellness':
                $icon = 'heart';
                break;
            case 'activities':
                $icon = 'futbol-o';
                break;
            case 'beauty_hair':
                $icon = 'smile-o';
                break;
            default:
                break;
        }
        return "fa-{$icon}";
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function getAll()
    {
        return static::root()->with('children')->get();
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
