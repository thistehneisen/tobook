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

    /**
     * Get SMTP and other email config
     *
     * @return array
     */
    protected function emailConfig()
    {
        return [
            'host'     => $this->config('mail.host'),
            'port'     => $this->config('mail.port'),
            'username' => $this->config('mail.username'),
            'password' => $this->config('mail.password'),
            'from'     => $this->config('mail.from')
        ];
    }

    /**
     * Login with username and password. Support old and new users
     *
     * @param string $username
     * @param string $password
     *
     * @return App\Core\Models\User|boolean
     */
    protected function login($username, $password)
    {
        $this->app->boot();
        $input = array(
            'username' => $username,
            'email'    => $username,
            'password' => $password,
        );

        $user = \App\Core\Models\User::oldLogin($input['username'], $input['password']);
        if ($user || Confide::logAttempt($input, Config::get('confide::signup_confirm'))) {
            return Confide::user();
        }

        return false;
    }

    protected function hasOwnerId()
    {
        @session_start();
        return isset($_SESSION['owner_id']);
    }

    /**
     * Return the current locale of system
     *
     * @return string
     */
    protected function getLocale()
    {
        @session_start();
        return isset($_SESSION['varaa_locale'])
            ? $_SESSION['varaa_locale']
            : $this->config('app.locale');
    }
}
