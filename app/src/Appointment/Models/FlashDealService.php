<?php namespace App\Appointment\Models;

use Carbon\Carbon;
use App\Appointment\Models\FlashDeal;
use Watson\Validating\ValidationException;

class FlashDealService extends Base
{
    protected $table = 'as_flash_deal_services';


    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function flashDeal()
    {
        return $this->belongsTo('App\Appointment\Models\FlashDeal');
    }

    public function service()
    {
       return $this->belongsTo('App\Appointment\Models\Service');
    }

}
