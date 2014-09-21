<?php
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
            'username' => 'hiusakatemia',
            'password' => 'mannerheim6'
        ]);
    }
}
