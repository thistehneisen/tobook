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

    public function getNiceOriginalNameAttribute()
    {
        return str_replace('_', ' ', $this->attributes['name']);
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
        $map = [
           'home'        => 'fa-home',
           'car'         => 'fa-car',
           'restaurant'  => 'fa-cutlery',
           'wellness'    => 'fa-heart',
           'activities'  => 'fa-futbol-o',
           'beauty_hair' => 'fa-smile-o',
        ];

        return isset($map[$this->attributes['name']])
            ? $map[$this->attributes['name']]
            : '';
    }

    public function getNewIconAttribute()
    {
        $map = [
            'home'        => 'home',
            'car'         => 'auto',
            'restaurant'  => 'fitness',
            'wellness'    => 'wellness',
            'activities'  => 'activities',
            'beauty_hair' => 'beauty',
        ];

        return isset($map[$this->attributes['name']])
            ? $map[$this->attributes['name']]
            : '';
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function getAll()
    {
        return static::root()
            ->orderBy('name')
            ->with(['children' => function ($query) {
                return $query->orderBy('name');
            }])
            ->get();
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
