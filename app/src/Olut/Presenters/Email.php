<?php namespace App\Olut\Presenters;

use HTML;

class Email extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function render($value, $item)
    {
        return HTML::mailto($value);
    }
}
