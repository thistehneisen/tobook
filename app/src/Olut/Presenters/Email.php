<?php namespace App\Olut\Presenters;

use HTML;

class Email extends Base
{
    /**
     * @{@inheritdoc}
     */
    public static function render($value, $item)
    {
        return $value ? HTML::mailto($value) : '';
    }
}
