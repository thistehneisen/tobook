<?php namespace App\Olut\Presenters;

use HTML;

class Email extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function render($value)
    {
        return HTML::mailto($value);
    }
}
