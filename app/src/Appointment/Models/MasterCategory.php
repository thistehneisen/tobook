<?php namespace App\Appointment\Models;

use App;
use App\Core\Models\Multilanguage;
use App\Core\Traits\MultilanguageTrait;
use Config;
use DB;
use Input;
use Str;

class MasterCategory extends \App\Core\Models\Base
{
    use MultilanguageTrait;

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
            ->where(function ($query) {
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
        $name = $this->translate('name', self::getContext() . $this->id, App::getLocale());

        if (empty($name)) {
            $name = $this->translate('name', self::getContext() . $this->id, Config::get('varaa.default_language'));
        }

        return (!empty($name)) ? $name : trans('admin.master-cats.translation_not_found');
    }

    public function getDescriptionAttribute()
    {
        $description = $this->translate('description', self::getContext() . $this->id, App::getLocale());

        if (empty($description)) {
            $description = $this->translate('description', self::getContext() . $this->id, Config::get('varaa.default_language'));
        }

        return (!empty($description)) ? $description : trans('admin.master-cats.translation_not_found');
    }

    /**
     * Return URL of asset image to be used as background
     *
     * @return string
     */
    public function getBackgroundImageAttribute()
    {
        $filename = Str::slug($this->getOriginal('name'));

        return asset_path("core/img/bg/{$filename}.jpg");
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * Get all master categories including their treatment types
     *
     * @return Illuminate\Support\Collection
     */
    public static function getAll()
    {
        $all = static::with('treatments')->orderBy('order', 'asc')->get();

        return $all;
    }

    /**
     * Delete this model and its translations
     */
    public function delete()
    {
        $keys = ['name', 'description'];
        foreach ($keys as $key) {
            Multilanguage::remove(null, self::getContext() . $this->id, null, $key);
        }
        parent::delete();
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

    public function treatments()
    {
        return $this->hasMany('App\Appointment\Models\TreatmentType', 'master_category_id');
    }
}
