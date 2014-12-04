<?php namespace App\Core\Controllers\Admin\Stats;

use App\Core\Controllers\Admin\Base;

class FlashDeals extends Base
{
    protected $viewPath = 'admin.stats';

    public function index()
    {
        return $this->render('flash_deals');
    }
}
