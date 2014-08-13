<?php
require __DIR__.'/bootstrap/autoload.php';

/**
 * Stand between Laravel and other modules to share common information such as
 * database configuration
 *
 * @author An Cao <an@varaa.com>
 */
class Bridge
{
    private static $instance;
    private $app;

    public static function __callStatic($method, $args)
    {
        if (static::$instance === null) {
            static::$instance = new Bridge;
        }

        return call_user_func_array([static::$instance, $method], $args);
    }

    protected function __construct()
    {
        $this->app = require_once __DIR__.'/bootstrap/start.php';
    }

    /**
     * Fetch configuration data from Laravel
     *
     * @param string $key 
     *
     * @return mixed
     */
    protected function config($key)
    {
        return $this->app['config']->get($key);
    }

    /**
     * Shortcut to get DB config
     *
     * @return array
     */
    protected function dbConfig()
    {
        return $this->config('database.connections.mysql');
    }
}
