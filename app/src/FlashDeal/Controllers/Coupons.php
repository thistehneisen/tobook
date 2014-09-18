<?php namespace App\FlashDeal\Controllers;

use Input, View;
use App\Core\Controllers\Base;
use App\FlashDeal\Models\Service;

class Coupons extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.fd.coupons';

    protected $crudOptions = [
        'langPrefix' => 'fd.coupons',
        'modelClass' => 'App\FlashDeal\Models\Coupon',
        'layout'     => 'modules.fd.layout',
        'presenters' => [
            'discounted_price' => 'App\Olut\Presenters\Currency',
            'service'          => 'App\FlashDeal\Presenters\ServicePresenter',
        ],
        'indexFields' => [
            'service',
            'discounted_price',
            'quantity',
            'valid_date',
        ]
    ];

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
