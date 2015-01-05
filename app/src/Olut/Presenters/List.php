<?php namespace App\Olut\Presenters;

use View;

class List extends Base
{
    /**
     * @{@inheritdoc}
     */
    public static function render($value, $item)
    {
        return View::make('olut::presenters.list', ['items' => $value]);
    }
}
