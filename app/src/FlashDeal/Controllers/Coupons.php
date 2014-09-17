<?php namespace App\FlashDeal\Controllers;

use Input, View;
use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;
use App\FlashDeal\Models\Service;

class Coupons extends Base
{
    use Crud;

    protected $viewPath = 'modules.fd.coupons';
    protected $langPrefix = 'fd.coupons';
    protected $modelClass = 'App\FlashDeal\Models\Coupon';
    protected $crudLayout = 'modules.fd.layout';


    /**
     * @{@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $services = Service::ofCurrentUser()->get();
        View::share('services', $services);
    }

    /**
     * {@inheritdoc}
     */
    protected function upsertHandler($item)
    {
        $service = Service::findOrFail(Input::get('service_id'));

        $item->fill(Input::all());
        $item->service()->associate($service);
        $item->user()->associate($this->user);
        $item->saveOrFail();

        return $item;
    }
}
