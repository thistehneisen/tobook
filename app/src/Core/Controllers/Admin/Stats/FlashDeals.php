<?php namespace App\Core\Controllers\Admin\Stats;

use Carbon\Carbon, Input;
use App\Core\Controllers\Admin\Base;
use App\FlashDeal\Stats\FlashDeal;

class FlashDeals extends Base
{
    protected $viewPath = 'admin.stats';

    /**
     * The main page of flash deal statistics
     *
     * @return View
     */
    public function index()
    {
        try {
            $from = new Carbon(Input::get('from'));
            $to = new Carbon(Input::get('to'));
        } catch (\InvalidArgumentException $ex) {
            // Cannot get data from query string, use default
            $to = Carbon::today();
            $from = $to->copy()->subDays(60);
        }

        $stat = new FlashDeal();

        return $this->render('flash_deals', [
            'dataset' => $stat->getTotalSoldByDays($from, $to),
            'sold'    => $stat->getTotalSoldByBusinesses($from, $to)
        ]);
    }
}
