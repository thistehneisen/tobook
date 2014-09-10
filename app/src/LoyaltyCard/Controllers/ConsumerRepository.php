<?php namespace App\LoyaltyCard\Controllers;

use Auth, Confide, Validator, Request, Input;
use App\LoyaltyCard\Models\Consumer as Model;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Transaction as TransactionModel;

class ConsumerRepository
{
    /**
     * Return all consumers
     * @param  string $search
     * @param  bool $isApi
     * @return Consumer
     */
    public function getAllConsumers($search = '', $isApi = false)
    {
        if ($isApi) {
            $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                        ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                        ->where('consumer_user.user_id', Auth::user()->id)
                        ->get();
        } else {
            if ($search != '') {
                $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                                ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                                ->where('consumer_user.user_id', Confide::user()->id)
                                ->where(function($q) use ($search) {
                                    $q->where('consumers.first_name', 'like', '%' . $search . '%')
                                        ->orWhere('consumers.last_name', 'like', '%' . $search . '%')
                                        ->orWhere('consumers.email', 'like', '%' . $search . '%')
                                        ->orWhere('consumers.phone', 'like', '%' . $search . '%');
                                })
                                ->select('lc_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'lc_consumers.updated_at')
                                ->paginate(10);
            } else {
                $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                                ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                                ->where('consumer_user.user_id', Confide::user()->id)
                                ->select('lc_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'lc_consumers.updated_at')
                                ->paginate(10);
            }
        }

        return $consumers;
    }

    /**
     * Store consumer to storage
     * @param  bool $isApi
     * @return Consumer
     */
    public function storeConsumer($isApi = false)
    {
        $rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email|unique:consumers',
            'phone'         => 'required|numeric',
            'address'       => 'required',
            'postcode'      => 'required|numeric',
            'city'          => 'required',
            'country'       => 'required',
        ];

        $validator = $isApi ? Validator::make(Request::all(), $rules) : Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        } else {
            $data = [
                'first_name'    => $isApi ? Request::get('first_name') : Input::get('first_name'),
                'last_name'     => $isApi ? Request::get('last_name') : Input::get('last_name'),
                'email'         => $isApi ? Request::get('email') : Input::get('email'),
                'phone'         => $isApi ? Request::get('phone') : Input::get('phone'),
                'address'       => $isApi ? Request::get('address') : Input::get('address'),
                'postcode'      => $isApi ? Request::get('postcode') : Input::get('postcode'),
                'city'          => $isApi ? Request::get('city') : Input::get('city'),
                'country'       => $isApi ? Request::get('country') : Input::get('country'),
            ];

            $core = $isApi ? Core::make($data, Auth::user()->id) : Core::make($data, Confide::user()->id);

            $consumer = new Model;
            $consumer->total_points = 0;
            $consumer->total_stamps = '';
            $consumer->consumer_id = $core->id;
            $consumer->consumer()->associate($core);
            $consumer->save();

            return $consumer;
        }
    }

    /**
     * Show consumer information
     * @param  int $consumerId
     * @param  bool $isApi
     * @return Consumer
     */
    public function showConsumer($consumerId, $isApi = false)
    {
        $userId = $isApi ? Auth::user()->id : Confide::user()->id;

        $consumer = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                        ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                        ->where('consumer_user.user_id', $userId)
                        ->where('lc_consumers.id', $consumerId)
                        ->first();

        return $consumer;
    }

    /**
     * Add point to consumer
     * @param int $consumerId
     * @param int $points
     * @param bool $isApi
     * @return Consumer
     */
    public function addPoint($consumerId, $points)
    {
        $rules = [
            'points' => 'required|numeric',
        ];

        $validator = Validator::make(['points' => $points], $rules);

        if ($validator->fails()) {
            return $validator->errors()->toArray();
        } else {
            $consumer = Model::find($consumerId);
            $consumer->total_points += $points;
            $consumer->save();

            $transaction = new TransactionModel;
            $transaction->user_id = Confide::user()->id;
            $transaction->consumer_id = $consumerId;
            $transaction->point = $points;
            $transaction->save();

            return $consumer;
        }
    }

    /**
     * Use voucher with point
     * @param  int $consumerId
     * @param  int $voucherId
     * @return Consumer
     */
    public function usePoint($consumerId, $voucherId)
    {
        $voucher = VoucherModel::find($voucherId);

        $consumer = Model::find($consumerId);
        $consumer->total_points -= $voucher->required;
        $consumer->save();

        $transaction = new TransactionModel;
        $transaction->user_id = Confide::user()->id;
        $transaction->consumer_id = $consumerId;
        $transaction->voucher_id = $voucherId;
        $transaction->point = $voucher->required * -1;
        $transaction->save();

        return $consumer;
    }

    /**
     * Add stamp to consumer
     * @param int $consumerId
     * @param int $offerId
     * @param bool $isApi
     * @return int
     */
    public function addStamp($consumerId, $offerId, $isApi)
    {
        $transaction = new TransactionModel;
        $transaction->user_id = $isApi ? Auth::user()->id : Confide::user()->id;
        $transaction->consumer_id = $consumerId;
        $transaction->offer_id = $offerId;

        $consumer = Model::find($consumerId);
        $consumerTotalStamps = $consumer->total_stamps;

        if ($consumerTotalStamps !== '') {
            $consumerTotalStamps = json_decode($consumerTotalStamps, true);

            if (array_key_exists($offerId, $consumerTotalStamps)) {
                $consumerNoOfStamps = $consumerTotalStamps[$offerId];
                $consumerNoOfStamps += 1;
            } else {
                $consumerNoOfStamps = 1;
            }

            $consumerTotalStamps[$offerId] = $consumerNoOfStamps;
            $consumerTotalStamps = json_encode($consumerTotalStamps);
        } else {
            $consumerNoOfStamps = 1;
            $consumerTotalStamps = json_encode([$offerId => 1]);
        }

        $transaction->stamp = 1;
        $transaction->save();
        $consumer->total_stamps = $consumerTotalStamps;
        $consumer->save();

        return $consumerNoOfStamps;
    }

    /**
     * Use offer with stamp
     * @param  int $consumerId
     * @param  int $offerId
     * @param  bool $isApi
     * @return int
     */
    public function useOffer($consumerId, $offerId, $isApi)
    {
        $offer = OfferModel::find($offerId);

        $transaction = new TransactionModel;
        $transaction->user_id = $isApi ? Auth::user()->id : Confide::user()->id;
        $transaction->consumer_id = $consumerId;
        $transaction->offer_id = $offerId;

        $consumer = Model::find($consumerId);
        $consumerTotalStamps = $consumer->total_stamps;

        if ($consumerTotalStamps !== '') {
            $consumerTotalStamps = json_decode($consumerTotalStamps, true);

            if (array_key_exists($offerId, $consumerTotalStamps)) {
                $consumerNoOfStamps = $consumerTotalStamps[$offerId];

                if ($consumerNoOfStamps >= $offer->required) {
                    $consumerNoOfStamps -= $offer->required;

                    $consumerTotalStamps[$offerId] = $consumerNoOfStamps;
                    $consumerTotalStamps = json_encode($consumerTotalStamps);

                    $transaction->offer_id = $offerId;
                    $transaction->stamp = $offer->required * -1;
                    $transaction->save();
                    $consumer->total_stamps = $consumerTotalStamps;
                    $consumer->save();

                    return $consumerNoOfStamps;
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
