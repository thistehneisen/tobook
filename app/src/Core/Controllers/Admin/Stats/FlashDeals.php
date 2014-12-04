<?php namespace App\Core\Controllers\Admin\Stats;

use Carbon\Carbon, Input;
use App\Core\Controllers\Admin\Base;
use App\FlashDeal\Stats\FlashDeal;

class FlashDeals extends Base
{
    protected $viewPath = 'admin.stats';

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

        return $this->render('flash_deals', [
            'dataset' => with(new FlashDeal())->getTotalSoldByDays($from, $to)
        ]);
    }
}
