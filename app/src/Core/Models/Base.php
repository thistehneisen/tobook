<?php namespace App\Core\Models;

use App;
use App\Core\Traits\MultilanguageTrait;
use Confide;
use Config;
use DB;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Input;
use Watson\Validating\ValidatingTrait;

class Base extends \Eloquent
{
    use ValidatingTrait;
    use SoftDeletingTrait;
    use MultilanguageTrait;

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
    public static function getContext()
    {
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
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = $this->getArrayableAttributes();

        // If an attribute is a date, we will cast it to a string after converting it
        // to a DateTime / Carbon instance. This is so we will get some consistent
        // formatting while accessing attributes vs. arraying / JSONing a model.
        foreach ($this->getDates() as $key)
        {
            if ( ! isset($attributes[$key])) continue;

            $attributes[$key] = (string) $this->asDateTime($attributes[$key]);
        }

        // We want to spin through all the mutated attributes for this model and call
        // the mutator for the attribute. We cache off every mutated attributes so
        // we don't have to constantly check on attributes that actually change.
        foreach ($this->getMutatedAttributes() as $key)
        {
            if ( ! array_key_exists($key, $attributes)) continue;

            $attributes[$key] = $this->mutateAttributeForArray(
                $key, $attributes[$key]
            );
        }

        // Here we will grab all of the appended, calculated attributes to this model
        // as these attributes are not really in the attributes array, but are run
        // when we need to array or JSON the model for convenience to the coder.
        foreach ($this->getArrayableAppends() as $key)
        {
            $attributes[$key] = $this->mutateAttributeForArray($key, null);
        }

        if( ! empty($this->multilingualAtrributes)) {
            foreach ($this->multilingualAtrributes as $key) {
                $attributes[$key] = $this->getAttribute($key);
            }
        }

        return $attributes;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getAttribute($key)
    {
        if (in_array($key, $this->multilingualAtrributes)) {
            $attribute = $this->translate($key, static::getContext() . $this->id, App::getLocale());
            if (empty($attribute)) {
                $attribute = $this->translate($key, static::getContext() . $this->id, Config::get('varaa.default_language'));
            }

            if (!empty($this->attributes[$key])) {
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
    public function getMultilingualData()
    {
        $defaultLanguage = Config::get('varaa.default_language');

        $items = self::where($this->table . '.id', '=', $this->id)
            ->join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . static::getContext() . "', `varaa_" . $this->table . "`.`id`)"))->get();

        $data = [];
        foreach (Config::get('varaa.languages') as $locale) {
            foreach ($items as $item) {
                if ($locale == $item->lang) {
                    $data[$locale][$item->key] = $item->value;
                }
            }
        }

        if (empty($data[$defaultLanguage])) {
            foreach ($this->multilingualAtrributes as $key) {
                $data[$defaultLanguage][$key] = $this->$key;
            }
        }

        return $data;
    }

    /**
     * Return data for making search index of all languages of given key
     *
     * @param  string $key
     * @return string
     */
    public function getTranslations($key)
    {
        $data = array();

        $items = self::where($this->table . '.id', '=', $this->id)
            ->join('multilanguage', 'multilanguage.context', '=', DB::raw("concat('" . static::getContext() . "', `varaa_" . $this->table . "`.`id`)"))
            ->where('multilanguage.key', '=', $key)
            ->get();

        foreach ($items as $item) {
            if(!empty($item->value)) $data[] = $item->value;
        }

        return (!empty($data)) ? implode(",", $data) : '';
    }

    /**
     * Return all multilingual data based on the decration fields
     *
     * @return string
     */
    public function getAllMultilingualAttributes()
    {
        $data = array();
        foreach ($this->multilingualAtrributes as $attribute) {
            $value = $this->getTranslations($attribute);
            if (!empty($value)) {
                $data[] = $value;
            }
        }

        return (!empty($data)) ? implode(",", $data) : '';
    }

    /**
     * Return the required fiels in rulesets,
     * use to fill default language fields in case it is missing.
     *
     */
    public function getRequiredAttributes()
    {
        $requiredFields = [];

        if (!empty($this->rulesets['saving'])) {
            $savingRules = $this->rulesets['saving'];
            foreach ($savingRules as $field => $ruleset) {
                $rules = explode("|", $ruleset);
                if (in_array('required', $rules)) {
                    $requiredFields[] = $field;
                }
            }
        }

        return $requiredFields;
    }

    /**
     * For saving empty default language required fields
     */
    public function getDefaultData($input)
    {
        $data = $input;
        $defaultLanguage = Config::get('varaa.default_language');

        foreach ($this->multilingualAtrributes as $key) {
            $data[$key] = (!empty($data[$key.'s'][ $defaultLanguage]))
            ? $data[$key.'s'][ $defaultLanguage] : '';
        }

        $requiredFields = $this->getRequiredAttributes();

        foreach ($requiredFields as $field) {
            if (empty($data[$field]) && !empty($data[$field.'s'])) {
                foreach ($data[$field.'s'] as $lang => $name) {
                    if (!empty($name)) {
                        $data[$field] = $name;
                        break;
                    }
                }
            }
        }

        return $data;
    }
}
