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
        $to = Carbon::today();
        $from = $to->copy()->subDays(60);

        try {
            if (Input::has('from') && Input::has('to')) {
                $from = new Carbon(Input::get('from'));
                $to = new Carbon(Input::get('to'));
            }
        } catch (\Exception $ex) {
            // Invalid format or something else, skip
        }

        $stat = new FlashDeal();

        return $this->render('flash_deals', [
            'from'    => $from,
            'to'      => $to,
            'dataset' => $stat->getTotalSoldByDays($from, $to),
            'sold'    => $stat->getTotalSoldByBusinesses($from, $to)
        ]);
    }
}
