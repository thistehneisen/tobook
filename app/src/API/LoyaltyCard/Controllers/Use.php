<?php namespace App\API\LoyaltyCard\Controllers;
use Validator, Response, Request;
use \App\LoyaltyCard\Models\Voucher as VoucherModel;
use \App\LoyaltyCard\Models\Offer as OfferModel;
use \App\LoyaltyCard\Models\Consumer as ConsumerModel;
use \App\API\LoyaltyCard\Models\Transaction as TransactionModel;
use App\Core\Controllers\Base as Base;
use Auth;

class Use extends Base
{
    public function voucher($id)
    {
    }

    public function offer($id)
    {
        $offer = OfferModel::where('user_id', Auth::user()->id)->find($id);
        $required = $offer->required;
        $free_service = $offer->free_service;

        $consumer_id = Request::get('consumer_id');
        $consumer = ConsumerModel::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                            ->where('consumers.id', $consumer_id)
                            ->get();
        $total_stamps = $consumer->total_stamps;

        $transaction = new TransactionModel;
        $transaction->user_id = Auth::user()->id;
        $transaction->consumer_id = $consumer_id;
        $transaction->offer_id = $id;

        if ($total_stamps !== '') {
            $total_stamps = json_decode($total_stamps);
            $this_stamp = json_decode($total_stamps[$id]);
            $no_of_stamps = $this_stamp[0];
            $free_service = $this_stamp[1];

            if ($required === $no_of_stamps + 1) {
                $transaction->stamp = $required * (-1);
                $transaction->free_service = $free_service;
                $no_of_stamps = 0;
                $free_service++;
            } else {
                $transaction->stamp = 1;
                $transaction->free_service = 0;
                $no_of_stamps++;
            }

            $this_stamp_new = json_encode([$no_of_stamps, $free_service]);
            $total_stamps[$id] = $this_stamp_new;
            $total_stamps = json_encode($total_stamps);
        } else {
            $transaction->stamp = 1;
            $transaction->free_service = 0;
            $this_stamp_new = json_encode([1, 0]);
            $total_stamps = json_encode([$id => $this_stamp_new]);
        }

        $transaction->save();
        $consumer->total_stamps = $total_stamps;
        $consumer->save();

        return Response::json_encode([
            'error' => 'false',

        ]);
    }
}
