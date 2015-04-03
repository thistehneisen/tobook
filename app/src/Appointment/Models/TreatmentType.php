<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use App, DB, Input, Config;
use Illuminate\Support\Collection;
use App\Core\Models\Multilanguage;
use App\Appointment\Models\Reception\BackendReceptionist;

class TreatmentType extends \App\Appointment\Models\Base
{
    protected $table = 'as_treatment_types';

    public $fillable = [
        'name',
        'description',
    ];

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
        $query =  self::join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . TreatmentType::getContext() . "', `varaa_as_treatment_types`.`id`)"))
            ->where(function($query){
                $query->where('multilanguage.key', 'name')
                    ->orWhere('multilanguage.key', 'description');
                return $query;
            })->where('value', 'LIKE', '%'.$keyword.'%')
            ->select('as_treatment_types.id')->distinct();

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));

        return $query->paginate($perPage);
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
        return "as_treatment_types_";
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

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service');
    }

    public function masterCategory()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
