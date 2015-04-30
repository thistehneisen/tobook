<?php namespace App\Appointment\Models;
use Config, Input;
use App\Core\Models\Multilanguage;

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

    /**
     * @see \App\Core\Models\Base
     */
    public $multilingualAtrributes = ['name', 'description'];

    /**
     * Get current context for retreive correct translation in multilanguage table
     *
     * @return string
     */
    public static function getContext()
    {
        return "as_service_categories_";
    }

    public function isDeletable()
    {
        return ($this->services->isEmpty()) ? true : false;
    }


    public function saveMultilanguage($names, $descriptions)
    {
        Multilanguage::saveValues($this->id, static::getContext(), 'name', $names);
        Multilanguage::saveValues($this->id, static::getContext(), 'description', $descriptions);
    }

    /**
     * Quick fix for saving category name
     */
    public function getDefaultData($input)
    {
        $data = $input;
        $defaultLanguage = Config::get('varaa.default_language');

        foreach ($this->multilingualAtrributes as $key) {
            $data[$key] = (!empty($data[$key.'s'][ $defaultLanguage]))
            ? $data[$key.'s'][ $defaultLanguage] : '';
        }

        if(empty($data['name'])) {
            foreach ($data['names'] as $lang => $name) {
                if(!empty($name)) {
                    $data['name'] = $name;
                    break;
                }
            }
        }

        return $data;
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
