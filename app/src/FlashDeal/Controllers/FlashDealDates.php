<?php namespace App\FlashDeal\Controllers;

use Carbon\Carbon;
use Input, View;
use App\Core\Controllers\Base;

class FlashDealDates extends Base
{
    use \CRUD;

    protected $crudOptions = [
        'langPrefix' => 'fd.flash_deal_dates',
        'modelClass' => 'App\FlashDeal\Models\FlashDealDate',
        'layout'     => 'modules.fd.layout',
    ];


}
