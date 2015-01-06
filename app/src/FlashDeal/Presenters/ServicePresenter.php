<?php namespace App\FlashDeal\Presenters;

use App\Olut\Presenters\Base;

class ServicePresenter extends Base
{
    /**
     * @{@inheritdoc}
     */
    public static function render($service, $item)
    {
        if ($service) {
            return $service->name." ({$service->price}&euro;)";
        }

        return '<em>'.trans('fd.services.deleted').'</em>';
    }
}
