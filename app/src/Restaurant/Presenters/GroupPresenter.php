<?php namespace App\Restaurant\Presenters;

use App\Olut\Presenters\Base;
use View;

class GroupPresenter extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function render($value, $item)
    {
        return implode(', ', $value->lists('name'));
    }
}
