<?php namespace App\Core;

/**
 * Providing a set of utility functions
 */
class Util
{
    /**
     * Genereate a random 12-character length string likes AZ0123456789
     * Legacy from old source code
     * @return string
     */
    public static function uuid()
    {
        return chr(rand(65,90)) . chr(rand(65,90)) . time();
    }
}
