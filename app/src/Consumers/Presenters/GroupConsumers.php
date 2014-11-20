<?php namespace App\Consumers\Presenters;

use App\Olut\Presenters\Base;
use View;

class GroupConsumers extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function render($value, $item)
    {
        return View::make('modules.co.groups.presenters.consumers', ['consumers' => $value]);
    }
}
