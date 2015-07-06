<?php namespace App\Core;

use App;
use Config;
use App\Core\Models\Multilanguage;

class Settings
{
    protected static $instance;
    public $settings = [];
    public $groups = [];

    public function __construct()
    {
        // Load all settings at once
        $all = App::make('App\Core\Models\Setting')->all();
        foreach ($all as $item) {
            $this->settings[$item->key] = $item->value;
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function get($key, $default = null)
    {
        $instance = self::getInstance();
        if (!isset($instance->settings[$key])) {
            // Get default value from configuration file
            if ($default === null) {
                $default = Config::get('varaa.settings.'.$key.'.default', null);
            }

            return $default;
        }

        return $instance->settings[$key];
    }

    /**
     * Get a group of settings having the same prefix
     *
     * @param string $name Group's name
     *
     * @return array
     */
    public static function group($name, $default = [])
    {
        $instance = self::getInstance();
        if (isset($instance->groups[$name])) {
            return $instance->groups[$name];
        }

        // Get settings by group
        $group = [];
        foreach ($instance->settings as $key => $value) {
            $pattern = $name.'_';
            if (strpos($key, $pattern) === 0) {
                $group[str_replace($pattern, '', $key)] = $value;
            }
        }

        $instance->groups[$name] = $group;

        if (empty($group)) {
            return $default;
        }

        return $group;
    }

    /**
     * Get value of a multi-lingual setting
     *
     * @param  string $key
     * @param  string $lang
     * @param  mixed  $default
     *
     * @return mixed
     */
    public static function getByLanguage($key, $lang = null)
    {
        $query = Multilanguage::ofContext('settings')
            ->ofKey($key);

        if ($lang !== null) {
            return $query->ofLang($lang)->pluck('value');
        }

        return $query->get();
    }

    public static function saveMultilingual($key, array $input)
    {
        foreach ($input as $lang => $content) {
            $row = Multilanguage::ofContext('settings')
                ->ofKey($key)
                ->ofLang($lang)
                ->first();

            if ($row === null) {
                $row = new Multilanguage();
            }

            $row->fill([
                'key' => $key,
                'lang' => $lang,
                'value' => $content,
                'context' => 'settings',
            ]);

            $row->save();
        }
    }

    //--------------------------------------------------------------------------
    // For Booking Terms
    //--------------------------------------------------------------------------
    public static function getBookingTerms($lang = null)
    {
        return static::getByLanguage('booking_terms', $lang);
    }

    public static function saveBookingTerms(array $input)
    {
        return static::saveMultilingual('booking_terms', $input);
    }
}
