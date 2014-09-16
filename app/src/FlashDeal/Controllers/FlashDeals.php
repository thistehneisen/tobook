<?php namespace App\FlashDeal\Controllers;

use Input, View, Carbon\Carbon;
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
            // Remember to check existing date
            $existing = $item->dates;
            $map = [];
            foreach ($existing as $date) {
                $map[$date->expire->toDateTimeString()] = $date;
            }

            $date = Input::get('date');
            $time = Input::get('time');

            $dates = [];
            foreach ($date as $key => $value) {
                if (isset($time[$key])) {
                    $expire = new Carbon($value.' '.$time[$key]);

                    // If this date time has existed in database, skip
                    if (isset($map[$expire->toDateTimeString()])) {
                        continue;
                    }

                    $obj = new FlashDealDate;
                    $obj->fill([
                        'expire'  => $expire,
                        'remains' => Input::get('quantity')
                    ]);

                    $dates[] = $obj;
                }
            }

            if (!empty($dates)) {
                try {
                    $item->dates()->saveMany($dates);
                } catch (\Exception $ex) {
                    // Silently fail if there're duplicated dates in database
                }
            }
        }

        return $item;
    }
}
