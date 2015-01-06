<?php namespace App\LoyaltyCard\Controllers;

use Auth, Confide, Validator, Request, Input;
use App\LoyaltyCard\Models\Consumer as LcConsumer;
use App\Consumers\Models\Consumer as CoreConsumer;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Transaction as TransactionModel;

class ConsumerRepository
{
    private $isApi;
    private $userId;

    public function __construct($isApi = false)
    {
        $this->isApi = $isApi;
        $this->userId = $isApi ? Auth::user()->id : Confide::user()->id;
    }

    /**
     * Return all consumers or search consumers
     * @param  string $search
     * @param  int    $perPage
     * @return Consumer
     */
    public function getConsumers($search = '', $perPage = 10)
    {
        $consumers = CoreConsumer::ofCurrentUser();
        if ($search !== '') {
            $consumers = $consumers->where(function ($q) use ($search) {
                $q->where('consumers.first_name', 'like', '%' . $search . '%')
                    ->orWhere('consumers.last_name', 'like', '%' . $search . '%')
                    ->orWhere('consumers.email', 'like', '%' . $search . '%')
                    ->orWhere('consumers.phone', 'like', '%' . $search . '%');
            });
        }

        if ($this->isApi) {
            // no pagination if this is API
            return $consumers->get();
        } else {
            return $consumers->paginate($perPage);
        }
    }

    /**
     * Store consumer to DB
     * @param  array $data
     * @return Consumer
     */
    public function storeConsumer($data)
    {
        $consumer = CoreConsumer::make($data, $this->userId);
        $lcConsumer = LcConsumer::makeOrGet($consumer, $this->userId);

        return $lcConsumer;
    }

    /**
     * Add point to consumer
     * @param  int      $consumerId
     * @param  int      $points
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
            $consumer = CoreConsumer::find($consumerId);

            $consumer->lc->total_points += $points;
            $consumer->lc->save();

            // TODO elegant way to save transaction
            $transaction = new TransactionModel();
            $transaction->user_id = $this->userId;
            $transaction->consumer_id = $consumer->lc->id;
            $transaction->point = $points;
            $transaction->save();

            return $consumer;
        }
    }

    /**
     * Use voucher with point
     * @param  int      $consumerId
     * @param  int      $voucherId
     * @return Consumer
     */
    public function usePoint($consumerId, $voucherId)
    {
        $voucher = VoucherModel::find($voucherId);
        $consumer = CoreConsumer::find($consumerId);

        $consumer->lc->total_points -= $voucher->required;
        $consumer->lc->save();

        $transaction = new TransactionModel();
        $transaction->user_id = $this->userId;
        $transaction->consumer_id = $consumer->lc->id;
        $transaction->voucher_id = $voucherId;
        $transaction->point = $voucher->required * -1;
        $transaction->save();

        return $consumer;
    }

    /**
     * Add stamp to consumer
     * @param  int  $consumerId
     * @param  int  $offerId
     * @return int
     */
    public function addStamp($consumerId, $offerId)
    {
        $consumer = CoreConsumer::find($consumerId);
        $consumerTotalStamps = $consumer->lc->total_stamps;

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

        $consumer->lc->total_stamps = $consumerTotalStamps;
        $consumer->lc->save();

        $transaction = new TransactionModel();
        $transaction->user_id = $this->userId;
        $transaction->consumer_id = $consumer->lc->id;
        $transaction->offer_id = $offerId;
        $transaction->stamp = 1;
        $transaction->save();

        return $consumerNoOfStamps;
    }

    /**
     * Use offer with stamp
     * @param  int  $consumerId
     * @param  int  $offerId
     * @return int
     */
    public function useOffer($consumerId, $offerId)
    {
        $offer = OfferModel::find($offerId);
        $consumer = CoreConsumer::find($consumerId);
        $consumerTotalStamps = $consumer->lc->total_stamps;

        if ($consumerTotalStamps !== '') {
            $consumerTotalStamps = json_decode($consumerTotalStamps, true);

            if (array_key_exists($offerId, $consumerTotalStamps)) {
                $consumerNoOfStamps = $consumerTotalStamps[$offerId];

                if ($consumerNoOfStamps >= $offer->required) {
                    $consumerNoOfStamps -= $offer->required;

                    $consumerTotalStamps[$offerId] = $consumerNoOfStamps;
                    $consumerTotalStamps = json_encode($consumerTotalStamps);

                    $consumer->lc->total_stamps = $consumerTotalStamps;
                    $consumer->lc->save();

                    $transaction = new TransactionModel();
                    $transaction->user_id = $this->userId;
                    $transaction->consumer_id = $consumer->lc->id;
                    $transaction->offer_id = $offerId;
                    $transaction->offer_id = $offerId;
                    $transaction->stamp = $offer->required * -1;
                    $transaction->save();

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
