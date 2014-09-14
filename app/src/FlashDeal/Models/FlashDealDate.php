<?php namespace App\FlashDeal\Models;

use Carbon\Carbon;
use DB;
use App\Core\Models\Base;
use App\Core\Models\BusinessCategory;

class FlashDealDate extends Base
{
    protected $table = 'fd_flash_deal_dates';
    public $fillable = [
        'date',
        'time',
        'remains',
    ];
    protected $rulesets = [
        'saving' => [
            'date' => 'required',
            'time' => 'required',
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getDAttribute()
    {
        return new Carbon($this->attributes['date'].' '.$this->attributes['time']);
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------

    /**
     * Get flash deals belonging to this business category
     *
     * @param object $query
     * @param int $categoryId
     *
     * @return object
     */
    public function scopeOfBusinessCategory($query, $categoryId)
    {
        $tblFlashDeal =with(new FlashDeal)->getTable();
        $tblService = with(new Service)->getTable();
        $tblCategory = with(new BusinessCategory)->getTable();

        return $query
            ->join($tblFlashDeal, $tblFlashDeal.'.id', '=', $this->table.'.flash_deal_id')
            ->join($tblService, $tblService.'.id', '=', $tblFlashDeal.'.service_id')
            ->join($tblCategory, $tblCategory.'.id', '=', $tblService.'.business_category_id')
            ->where($tblCategory.'.id', $categoryId);
    }

    /**
     * Get active flash deal dates
     *
     * @param object $query
     *
     * @return object
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('remains', '>', 0)
            ->where('date', $now->toDateString())
            ->where('time', '>=', $now->toTimeString());
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function flashDeal()
    {
        return $this->belongsTo('App\FlashDeal\Models\FlashDeal');
    }
}
