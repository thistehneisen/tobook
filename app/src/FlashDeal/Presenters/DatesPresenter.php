<?php namespace App\FlashDeal\Presenters;

use App\Olut\Presenters\Base;
use View;

class DatesPresenter extends Base
{
    public static function render($value, $item)
    {
        return View::make('modules.fd.presenters.dates', [
            'dates' => $value
        ]);
    }
}
