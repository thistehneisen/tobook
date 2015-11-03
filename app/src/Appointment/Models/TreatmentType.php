<?php namespace App\Appointment\Models;

use App;
use App\Core\Models\Multilanguage;
use Config;
use DB;
use Input;
use Str;

class TreatmentType extends \App\Core\Models\Base
{

    use App\Search\ElasticSearchTrait;

    protected $table = 'as_treatment_types';

    public $fillable = [
        'order',
        'name',
        'description',
    ];

   

    /**
     * @see \App\Core\Models\Base
     */
    public $multilingualAtrributes = ['name', 'description'];

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
    public function search($keyword, array $options = array())
    {
        $query =  self::join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . TreatmentType::getContext() . "', `varaa_as_treatment_types`.`id`)"))
            ->where(function ($query) {
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
    /**
     * Return the URL of this treatment type
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('business.treatment', [
            'id'   => $this->id,
            'slug' => Str::slug($this->getOriginal('name')),
        ]);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------

    public function services()
    {
        return $this->hasMany('App\Appointment\Models\Service');
    }

    public function keywords()
    {
        return $this->hasMany('App\Appointment\Models\KeywordMap');
    }

    public function masterCategory()
    {
        return $this->belongsTo('App\Appointment\Models\Service');
    }
}
