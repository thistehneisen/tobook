<?php namespace App\Appointment\Models;

use App\Core\Models\Multilanguage;
use App, DB, Input, Config;

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

    /**
     * Search using database
     *
     * @param string $keyword
     *
     * @return Illuminate\Pagination\Paginator
     */
    public function databaseSearch($keyword, array $options = array())
    {
        $query =  self::join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . MasterCategory::getContext() . "', `varaa_as_master_categories`.`id`)"))
            ->where(function($query){
                $query->where('multilanguage.key', 'name')
                    ->orWhere('multilanguage.key', 'description');
                return $query;
            })->where('value', 'LIKE', '%'.$keyword.'%')
            ->select('as_master_categories.id')->distinct();

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));

        return $query->paginate($perPage);
    }

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getNameAttribute()
    {
        $multilang = Multilanguage::where('lang', '=', App::getLocale())
            ->where('context', '=', self::getContext() . $this->id)
            ->where('key', '=' ,'name')
            ->first();

        return (!empty($multilang->value)) ? $multilang->value : trans('admin.master-cats.translation_not_found');
    }

    public function getDescriptionAttribute()
    {

        $multilang = Multilanguage::where('lang', '=', App::getLocale())
            ->where('context', '=', self::getContext() . $this->id)
            ->where('key', '=' ,'description')
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
