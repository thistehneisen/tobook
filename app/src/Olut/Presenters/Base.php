<?php namespace App\Olut\Presenters;

class Base
{
    /**
     * Do nothing special, just print out the plain value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function render($value)
    {
        return $value;
    }
}
