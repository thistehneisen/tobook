<?php namespace App\FlashDeal\Controllers;

use Input, View, Carbon\Carbon;
use App\Core\Controllers\Base;
use App\FlashDeal\Models\Service;
use App\FlashDeal\Models\FlashDeal;
use App\FlashDeal\Models\FlashDealDate;

class FlashDeals extends Base
{
    use \CRUD;

    protected $viewPath = 'modules.fd.deals';
    protected $crudOptions = [
        'langPrefix' => 'fd.flash_deals',
        'modelClass' => 'App\FlashDeal\Models\FlashDeal',
        'layout'     => 'modules.fd.layout',
        'presenters' => [
            'discounted_price' => 'App\Olut\Presenters\Currency',
            'dates'            => 'App\FlashDeal\Presenters\DatesPresenter',
            'service'          => 'App\FlashDeal\Presenters\ServicePresenter',
        ],
        'indexFields' => [
            'service',
            'discounted_price',
            'quantity',
            'dates'
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
        $item->fill(Input::all());
        $service = Service::findOrFail(Input::get('service_id'));
        $item->service()->associate($service);
        $item->user()->associate($this->user);
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
                    $obj->user()->associate($this->user);

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

    /**
     * Show the detailed information of a flash deal
     *
     * @param int $id
     *
     * @return View
     */
    public function view($id)
    {
        $item = FlashDealDate::with('flashDeal', 'flashDeal.service')->find($id);

        return $this->render('view', [
            'item' => $item
        ]);
    }
}
