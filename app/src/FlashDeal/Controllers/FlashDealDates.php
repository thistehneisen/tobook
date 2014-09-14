<?php namespace App\FlashDeal\Controllers;

use Input, View;
use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;

class FlashDealDates extends Base
{
    use Crud;

    protected $langPrefix = 'fd.flash_deal_dates';
    protected $modelClass = 'App\FlashDeal\Models\FlashDealDate';
    protected $crudLayout = 'modules.fd.layout';

}
