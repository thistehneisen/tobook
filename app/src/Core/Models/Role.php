<?php namespace App\Core\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    const ADMIN    = 'Admin';
    const USER     = 'User';
    const CONSUMER = 'Consumer';

    /**
     * Helper method to quickly get role Admin
     *
     * @return App\Core\Models\Role
     */
    public static function admin()
    {
        return static::getRole(static::ADMIN);
    }

    /**
     * Helper method to quickly get role User
     *
     * @return App\Core\Models\Role
     */
    public static function user()
    {
        return static::getRole(static::USER);
    }

    /**
     * Helper method to quickly get role Consumer
     *
     * @return App\Core\Models\Role
     */
    public static function consumer()
    {
        return static::getRole(static::CONSUMER);
    }

    /**
     * Get a role by its name
     *
     * @param string $name
     *
     * @return App\Core\Models\Role
     */
    public static function getRole($name)
    {
        return static::where('name', $name)->first();
    }
}
