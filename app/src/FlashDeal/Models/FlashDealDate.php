<?php namespace App\FlashDeal\Models;

use Carbon\Carbon;
use DB;
use App\Core\Models\Base;
use App\Core\Models\BusinessCategory;
use App\Cart\CartDetailInterface;
use App\Cart\CartDetail;
use App\FlashDeal\SoldOutException;

class FlashDealDate extends Base implements CartDetailInterface
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
     * @param int $categoryIds
     *
     * @return object
     */
    public function scopeOfBusinessCategory($query, $categoryIds)
    {
        $tblFlashDeal =with(new FlashDeal)->getTable();
        $tblService = with(new Service)->getTable();
        $tblCategory = with(new BusinessCategory)->getTable();

        return $query
            ->select('*', $this->table.'.id AS flash_deal_date_id')
            ->leftJoin($tblFlashDeal, $tblFlashDeal.'.id', '=', $this->table.'.flash_deal_id')
            ->leftJoin($tblService, $tblService.'.id', '=', $tblFlashDeal.'.service_id')
            ->leftJoin($tblCategory, $tblCategory.'.id', '=', $tblService.'.business_category_id')
            ->whereIn($tblCategory.'.id', $categoryIds);
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

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    //--------------------------------------------------------------------------
    // CART DETAIL
    //--------------------------------------------------------------------------
    /**
     * @{@inheritdoc}
     */
    public function getCartDetailName()
    {
        return $this->flashDeal->service->name;
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailOriginal()
    {
        return (object) [
            'instance' => $this
        ];
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailQuantity()
    {
        return 1;
    }

    /**
     * @{@inheritdoc}
     */
    public function getCartDetailPrice()
    {
        return $this->flashDeal->discounted_price;
    }

    /**
     * Increase the number of remaining flash deals, so that other people
     * could buy
     *
     * @param CartDetail $cartDetail
     *
     * @return void
     */
    public function unlockCartDetail(CartDetail $cartDetail)
    {
        $this->incrRemains($cartDetail->quantity);
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    /**
     * Buy this flash deal
     *
     * @param int $quantity
     *
     * @return true
     */
    public function buy($quantity)
    {
        if ($this->remains <= 0) {
            throw new SoldOutException('Unfortunately the current flash deal has been sold out');
        }

        if ($quantity >= $this->remains) {
            $quantity = $this->remains;
        }

        // Update remaining deals
        return $this->decrRemains($quantity);
    }

    /**
     * Increase the numbder of remaining deals
     *
     * @param int $value
     *
     * @return App\FlashDeal\Models\FlashDealDate
     */
    public function incrRemains($value)
    {
        $this->increment('remains', $value);
        $this->save();
        return $this;
    }

    /**
     * Decrease the number of remaining deals
     *
     * @param int $value
     *
     * @return App\FlashDeal\Models\FlashDealDate
     */
    public function decrRemains($value)
    {
        $this->decrement('remains', $value);
        $this->save();
        return $this;
    }
}
