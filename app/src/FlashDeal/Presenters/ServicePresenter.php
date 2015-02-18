<?php namespace App\FlashDeal\Presenters;

use App\Olut\Presenters\Base;
use Config, Settings;

class ServicePresenter extends Base
{
    /**
     * @{@inheritdoc}
     */
    public static function render($service, $item)
    {
        if ($service) {
            return sprintf('%s (%s%s)', $service->name, $service->price, Settings::get('currency'));
        }

        return '<em>'.trans('fd.services.deleted').'</em>';
    }
}
