<?php namespace App\Olut\Presenters;

class Base
{
    /**
     * Do nothing special, just print out the plain value
     *
     * @param mixed $value
     * @param Illuminate\Database\Eloquent\Model $item Model instance
     *
     * @return mixed
     */
    public static function render($value, $item)
    {
        // In case we have an array, join all elements
        if (is_array($value)) {
            $value = implode(', ', array_flatten($value));
        }

        return (string) $value;
    }
}
