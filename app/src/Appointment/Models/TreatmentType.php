<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use App, DB;
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
}
