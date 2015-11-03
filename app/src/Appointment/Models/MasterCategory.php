<?php namespace App\Appointment\Models;

use App;
use App\Core\Models\Multilanguage;
use Config;
use DB;
use Input;
use Str;

class MasterCategory extends \App\Core\Models\Base
{
    protected $table = 'as_master_categories';

    protected $dates = ['deleted_at'];

    public $fillable = ['name', 'description', 'order'];

    use App\Search\ElasticSearchTrait;
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
    public function getIconUrlAttribute()
    {
        $map = [
            'home'        => 'home',
            'car'         => 'auto',
            'restaurant'  => 'fitness',
            'wellness'    => 'wellness',
            'activities'  => 'activities',
            'beauty_hair' => 'beauty',
            'frizetava'   => 'hair',
            'manikirs'    => 'nails',
            'masaza'      => 'massage',
            'kosmetologs' => 'comestic',
            'spa'         => 'spa',
            'solarijs'    => 'solarium',
        ];

        $icon = isset($map[$this->slug])
            ? $map[$this->slug]
            : 'hair';

        return asset_path('core/img/new/icons/'.$icon.'.png');
    }

    public function getImageUrlAttribute()
    {
        $map = [
            'hiukset'          => 'hair',
            'karvanpoistot'    => 'hairremoval',
            'hieronnat'        => 'massage',
            'jalkahoidot'      => 'feet',
            'kasvohoidot'      => 'face',
            'vartalohoidot'    => 'body',
            'kynnet'           => 'nails',
            'ripset-kulmat'    => 'eyelash',
            // Swedish
            'harvard'          => 'hair',
            'fotvard'          => 'feet',
            'massage'          => 'massage',
            'ansiktsvard'      => 'face',
            'harborttagning'   => 'hairremoval',
            'nagelvard'        => 'nails',
            'ogonbryn-fransar' => 'eyelash',
            'kroppsvard'       => 'body',
        ];

        $icon = isset($map[$this->slug])
            ? $map[$this->slug]
            : 'hair';

        return asset_path('core/img/front/'.$icon.'.png');
    }

    /**
     * Return URL of asset image to be used as background
     *
     * @return string
     */
    public function getBackgroundImageAttribute()
    {
        $filename = Str::slug($this->getOriginal('name'));

        $map = [
            'hiukset' => 'kampaamopalvelut',
        ];

        $filename = isset($map[$filename])
            ? $map[$filename]
            : $filename;

        return asset_path("core/img/bg/{$filename}.jpg");
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->getOriginal('name'));
    }

    /**
     * Return the URL of this master category
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return route('business.master_category', [
            'id' => $this->id,
            'slug' => $this->slug,
        ]);
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

    public function keywords()
    {
        return $this->hasMany('App\Appointment\Models\KeywordMap');
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
