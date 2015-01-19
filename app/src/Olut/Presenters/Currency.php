<?php namespace App\Olut\Presenters;

use Config;

class Currency extends Base
{
    /**
     * Attach the currency sign
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function render($value, $item)
    {
        return $value.Config::get('varaa.currency');
    }
}
