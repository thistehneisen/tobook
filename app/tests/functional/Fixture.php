<?php namespace Test\Functional;

use App\Core\Models\User;

class Fixture
{
    /**
     * Return a fixture user for all tests
     *
     * @return App\Core\Models\User
     */
    public static function user()
    {
        return User::find(70);
    }
}
