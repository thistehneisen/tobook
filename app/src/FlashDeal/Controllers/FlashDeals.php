<?php namespace App\FlashDeal\Controllers;

use Input, View;
use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;
use App\FlashDeal\Models\Service;
use App\FlashDeal\Models\FlashDealDate;

class FlashDeals extends Base
{
    use Crud;

    protected $viewPath = 'modules.fd.deals';
    protected $langPrefix = 'fd.flash_deals';
    protected $modelClass = 'App\FlashDeal\Models\FlashDeal';
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
        $item->fill(Input::all());
        $service = Service::findOrFail(Input::get('service_id'));
        $item->service()->associate($service);
        $item->saveOrFail();

        // Add flash deal dates
        if (Input::has('date')) {
            $date = Input::get('date');
            $time = Input::get('time');

            $dates = [];
            foreach ($date as $key => $value) {
                if (isset($time[$key])) {
                    $dates[] = new FlashDealDate([
                        'date' => $value,
                        'time' => $time[$key]
                    ]);
                }
            }

            if (!empty($dates)) {
                $item->dates()->saveMany($dates);
            }
        }

        return $item;
    }
}
