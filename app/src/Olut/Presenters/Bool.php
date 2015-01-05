<?php namespace App\Olut\Presenters;

use View;

class Bool extends Base
{
    /**
     * @{@inheritdoc}
     */
    public static function render($value, $item)
    {
        return View::make('olut::presenters.bool', ['value' => (bool) $value]);
    }
}
