<?php namespace App\Olut\Presenters;

use Settings;

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
        return $value.Settings::get('currency');
    }
}
