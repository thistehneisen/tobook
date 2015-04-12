<?php namespace App\Core\Models;

use Confide, App, Log, Input, Config, DB;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use App\Search\SearchableInterface;
use App\Search\ElasticSearchTrait;
use App\Core\Models\Multilanguage;
use App\Core\Traits\MultilanguageTrait;

class Base extends \Eloquent implements SearchableInterface
{
    use ValidatingTrait;
    use SoftDeletingTrait;
    use ElasticSearchTrait;
    use MultilanguageTrait;

    /**
     * By default we'll disable ES index in all models and only enable in some
     * specific models manually
     *
     * @var boolean
     */
    public $isSearchable = false;

    /**
     * Using to create dynamically all multilingual attributes based on this array
     *
     * @var array
     */
    public $multilingualAtrributes = [];

    /**
     *
     * Use to get translation context for multilanguage table
     *
     */
    public static function getContext() {
       return '';
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    /**
     * Return records that belong to the current logged-in user
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfCurrentUser($query)
    {
        $userId = 0;
        $user = Confide::user();
        if (!empty($user)) {
            $userId = $user->id;
        }

        return $this->scopeOfUser($query, $userId);
    }

    /**
     * Return records that belong to the provided user
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param App\Core\Models\User|int          $userId
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfUser($query, $userId)
    {
        $table = $this->getTable();
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        if (method_exists($this, 'user')) {
            return $query->where($table.'.user_id', $userId);
        } elseif (method_exists($this, 'users')) {
            if ($this->users() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                $table = $this->users()->getTable();
            }

            return $query->whereHas('users', function ($query) use ($userId, $table) {
                return $query->where($table.'.user_id', $userId);
            });
        }

        return $query;
    }

    /**
     * Sort records by creation time
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getAttribute($key)
    {
        if(in_array($key, $this->multilingualAtrributes)) {
            $attribute = $this->translate($key, static::getContext() . $this->id, App::getLocale());
            if (empty($attribute)) {
                $attribute = $this->translate($key, static::getContext() . $this->id, Config::get('varaa.default_language'));
            }

            if(!empty($this->attributes[$key])) {
                return (!empty($attribute)) ? $attribute : $this->attributes[$key];
            }

            return (!empty($attribute)) ? $attribute : '';
        }
        return parent::getAttribute($key);
    }

    /**
     * Function to get all multilingual attributes of current model
     *
     * @author hung
     * @return array
     */
    public function getTranslatedData()
    {
        $defaultLanguage = Config::get('varaa.default_language');

        $items = self::where($this->table . '.id', '=', $this->id)
            ->join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . static::getContext() . "', `varaa_" . $this->table . "`.`id`)"))->get();

        $data = [];
        foreach (Config::get('varaa.languages') as $locale){
            foreach ($items as $item) {
                if($locale == $item->lang) {
                    $data[$locale][$item->key] = $item->value;
                }
            }
        }

        if(empty($data[$defaultLanguage])) {
            foreach ($this->multilingualAtrributes as $key) {
                $data[$defaultLanguage][$key] = $this->$key;
            }
        }

        return $data;
    }
}
