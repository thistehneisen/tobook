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
        // In case we have an array, join all elements
        if (is_array($value)) {
            $value = implode(', ', array_flatten($value));
        }

        return (string) $value;
    }
}
