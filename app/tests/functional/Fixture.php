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
        return new User([
            'username' => 'varaa_test',
            'password' => 'varaa_test'
        ]);
    }
}
