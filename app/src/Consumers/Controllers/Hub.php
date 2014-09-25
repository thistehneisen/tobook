<?php namespace App\Consumers\Controllers;

use Input, DB, Confide, View;
use App\Core\Controllers\Base;

class Hub extends Base
{
    use \CRUD;
    protected $viewPath = 'modules.co';
    protected $crudOptions = [
        'modelClass'    => 'App\Consumers\Models\Consumer',
        'langPrefix'    => 'co',
        'indexFields'   => ['first_name', 'last_name', 'email', 'phone'],
        'layout'        => 'layouts.default',
        'showTab'       => true,
        'bulkActions'   => [],
    ];

    protected function upsertHandler($item)
    {
        $item->fill(Input::all());
        $item->saveOrFail();
        $item->users()->detach($this->user->id);
        $item->users()->attach($this->user, ['is_visible' => true]);

        return $item;
    }

    public function getHistory()
    {
        $consumerId = Input::get('id');
        $service = Input::get('service');
        $history = null;

        switch ($service) {
            case 'as':
                $history = DB::table('as_bookings')
                    ->join('as_booking_services', 'as_bookings.id', '=', 'as_booking_services.booking_id')
                    ->join('as_services', 'as_booking_services.service_id', '=', 'as_services.id')
                    ->where('as_bookings.user_id', Confide::user()->id)
                    ->where('as_bookings.consumer_id', $consumerId)
                    ->select('as_bookings.id', 'as_bookings.uuid', 'as_booking_services.date', 'as_bookings.start_at', 'as_bookings.end_at', 'as_services.name', 'as_bookings.notes', 'as_bookings.created_at')
                    ->distinct()
                    ->orderBy('as_bookings.date', 'DESC')
                    ->take(10)
                    ->get();
                break;

            case 'lc':
                $baseHistory = DB::table('lc_transactions')
                    ->join('users', 'lc_transactions.user_id', '=', 'users.id')
                    ->join('lc_consumers', 'lc_transactions.consumer_id', '=', 'lc_consumers.id')
                    ->where('offer_id', null)
                    ->where('voucher_id', null)
                    ->where('lc_transactions.user_id', Confide::user()->id)
                    ->where('lc_consumers.consumer_id', $consumerId)
                    ->select('lc_transactions.created_at', 'lc_transactions.offer_id', 'lc_transactions.voucher_id', 'lc_transactions.point', 'users.business_name')
                    ->take(10)
                    ->get();

                $offerHistory = DB::table('lc_transactions')
                    ->join('users', 'lc_transactions.user_id', '=', 'users.id')
                    ->join('lc_consumers', 'lc_transactions.consumer_id', '=', 'lc_consumers.id')
                    ->join('lc_offers', 'lc_transactions.offer_id', '=', 'lc_offers.id')
                    ->where('lc_transactions.user_id', Confide::user()->id)
                    ->where('lc_consumers.consumer_id', $consumerId)
                    ->select('lc_transactions.created_at', 'lc_transactions.offer_id', 'lc_transactions.voucher_id', 'lc_offers.name', 'lc_transactions.stamp', 'users.business_name')
                    ->take(10)
                    ->get();

                $voucherHistory = DB::table('lc_transactions')
                    ->join('users', 'lc_transactions.user_id', '=', 'users.id')
                    ->join('lc_consumers', 'lc_transactions.consumer_id', '=', 'lc_consumers.id')
                    ->join('lc_vouchers', 'lc_transactions.voucher_id', '=', 'lc_vouchers.id')
                    ->where('lc_transactions.user_id', Confide::user()->id)
                    ->where('lc_consumers.consumer_id', $consumerId)
                    ->select('lc_transactions.created_at', 'lc_transactions.offer_id', 'lc_transactions.voucher_id', 'lc_vouchers.name', 'lc_transactions.point', 'users.business_name')
                    ->take(10)
                    ->get();

                $history = array_merge($baseHistory, $offerHistory, $voucherHistory);

                break;
        }

        usort($history, function ($a, $b) {
            return strcmp($b->created_at, $a->created_at);
        });

        return View::make('modules.co.history', [
            'service' => $service,
            'history' => $history,
        ]);
    }
}
