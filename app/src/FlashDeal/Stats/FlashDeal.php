<?php namespace App\FlashDeal\Stats;

use Carbon\Carbon, DB;
use App\Cart\Cart;
use App\Cart\CartDetail;
use App\Core\Models\Business;

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

    /**
     * Get total sold flash deals by businesses
     *
     * @param Carbon $from
     * @param Carbon $to
     *
     * @return Illuminate\Support\Collection
     */
    public function getTotalSoldByBusinesses(Carbon $from, Carbon $to)
    {
        $result = CartDetail::select(
                DB::raw('SUM(quantity * price) AS revenue'),
                DB::raw('SUM(quantity) AS total'),
                DB::raw('user_id')
            )
            ->where('model_type', 'App\FlashDeal\Models\FlashDealDate')
            ->whereHas('cart', function ($query) use ($from, $to) {
                return $query->completed();
            })
            ->join('carts', 'cart_details.id', '=', 'carts.id')
            ->where('cart_details.created_at', '>=', $from)
            ->where('cart_details.created_at', '<=', $to)
            ->groupBy('carts.user_id')
            ->get();

        $result->map(function ($item) {
            $item->business = Business::ofUser($item->user_id)->first();
        });

        return $result;
    }
}
