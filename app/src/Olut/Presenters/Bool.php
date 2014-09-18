<?php namespace App\Olut\Presenters;

use View;

class Bool extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function render($value)
    {
        return View::make('olut::presenters.bool', ['value' => (bool) $value]);
    }
}
