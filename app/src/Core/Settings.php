<?php namespace App\Core;

use App;

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
            return $default;
        }

        return $instance->settings[$key];
    }

    /**
     * Get a group of settings having the same prefix
     *
     * @param  string $name Group's name
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
}
