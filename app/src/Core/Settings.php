<?php namespace App\Core;

use App;

class Settings
{
    protected static $instance;
    public $settings = [];

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
}
