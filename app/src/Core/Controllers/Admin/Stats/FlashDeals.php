<?php namespace App\Core\Controllers\Admin\Stats;

use Carbon\Carbon;
use App\Core\Controllers\Admin\Base;
use App\FlashDeal\Stats\FlashDeal;

class FlashDeals extends Base
{
    protected $viewPath = 'admin.stats';

    public function index()
    {
        $to = Carbon::today();
        $from = $to->copy()->subDays(60);

        $stat = new FlashDeal();

        return $this->render('flash_deals', [
            'dataset' => $stat->getTotalSoldByDays($from, $to)
        ]);
    }
}
