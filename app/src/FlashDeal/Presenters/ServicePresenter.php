<?php namespace App\FlashDeal\Presenters;

use App\Olut\Presenters\Base;
use View;

class ServicePresenter extends Base
{
    /**
     * @{@inheritdoc}
     */
    public function render($service, $item)
    {
        return $service->name." ({$service->price}&euro;)";
    }
}
