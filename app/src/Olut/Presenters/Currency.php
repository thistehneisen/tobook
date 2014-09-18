<?php namespace App\Olut\Presenters;

class Currency extends Base
{
    /**
     * Attach the currency sign
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function render($value)
    {
        return $value.'&euro;';
    }
}
