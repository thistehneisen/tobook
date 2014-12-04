<?php namespace App\FlashDeal\Stats;

use Carbon\Carbon, DB;
use App\Cart\Cart;
use App\Cart\CartDetail;

class FlashDeal
{
    /**
     * Count total sold flash deals by dates
     *
     * @param Carbon $from
     * @param Carbon $to
     *
     * @return array
     */
    public function getTotalSoldByDays(Carbon $from, Carbon $to)
    {
        return CartDetail::select(
                DB::raw('SUM(quantity * price) AS revenue'),
                DB::raw('SUM(quantity) AS total'),
                DB::raw('DATE(created_at) AS date')
            )
            ->where('model_type', 'App\FlashDeal\Models\FlashDealDate')
            ->whereHas('cart', function ($query) use ($from, $to) {
                return $query->completed();
            })
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();
    }
}
