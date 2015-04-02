<?php namespace App\Appointment\Models;

use App\Core\Models\Multilanguage;
use App, DB;

class MasterCategory extends \App\Core\Models\Base
{
    protected $table = 'as_master_categories';

    protected $dates = ['deleted_at'];

    public $fillable = ['name', 'description', 'order'];

    public function saveMultilanguage($names, $descriptions)
    {
        Multilanguage::saveValues($this->id, static::getContext(), 'name', $names);
        Multilanguage::saveValues($this->id, static::getContext(), 'description', $descriptions);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getNameAttribute()
    {
        $multilang = Multilanguage::where('multilanguage.lang', '=', App::getLocale())
            ->where('multilanguage.key', '=' ,'name')
            ->where('multilanguage.context', '=', self::getContext() . $this->id)
            ->first();

        return (!empty($multilang->value)) ? $multilang->value : trans('admin.master-cats.translation_not_found');
    }

    public function getDescriptionAttribute()
    {

        $multilang = Multilanguage::where('multilanguage.lang', '=', App::getLocale())
            ->where('multilanguage.key', '=' ,'description')
            ->where('multilanguage.context', '=', self::getContext() . $this->id)
            ->first();
        return (!empty($multilang->value)) ? $multilang->value : trans('admin.master-cats.translation_not_found');
    }

    /**
     * Get current context for retreive correct translation in multilanguage table
     *
     * @return string
     */
    public static function getContext()
    {
        return "as_master_categories_";
    }

    public function isDeletable()
    {
        return ($this->services->isEmpty()) ? true : false;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service', 'master_category_id');
    }
}
