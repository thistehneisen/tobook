<?php namespace App\FlashDeal\Models;

use Carbon\Carbon;
use DB;
use App\Core\Models\Base;
use App\Core\Models\BusinessCategory;

class FlashDealDate extends Base
{
    protected $table = 'fd_flash_deal_dates';
    public $fillable = [
        'expire',
        'remains',
    ];
    protected $rulesets = [
        'saving' => [
            'expire'  => 'required',
            'remains' => 'required',
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getExpireAttribute()
    {
        return new Carbon($this->attributes['expire']);
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
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('remains', '>', 0)
            ->where('expire', '>=', Carbon::now());
    }

    /**
     * Show expired flash deals
     *
     * @param  Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('expire', '<', Carbon::now());
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function flashDeal()
    {
        return $this->belongsTo('App\FlashDeal\Models\FlashDeal');
    }
}
