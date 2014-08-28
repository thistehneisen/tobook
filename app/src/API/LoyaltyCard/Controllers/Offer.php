<?php namespace App\API\LoyaltyCard\Controllers;
use Validator, Response, Request;
use \App\LoyaltyCard\Models\Offer as OfferModel;
use \App\LoyaltyCard\Models\Consumer as ConsumerModel;
use \App\API\LoyaltyCard\Models\Transaction as TransactionModel;
use App\Core\Controllers\Base as Base;
use Auth;

class Offer extends Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $offers = OfferModel::where('user_id', Auth::user()->id)->get();

        return Response::json([
            'error' => false,
            'offers' => $offers->toArray(),
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $inserted_id = 0;
        $rules = [
            'name'          => 'required',
            'required'      => 'required|numeric',
            'free'          => 'required|numeric',
            'active'        => 'required|numeric',
            'auto_add'      => 'required|numeric',
        ];

        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => 'Invalid data'
            ], 400);
        } else {
            $offer = new OfferModel;
            $offer->name = Request::get('name');
            $offer->user_id = Auth::user()->id;
            $offer->required = Request::get('required');
            $offer->free_service = Request::get('free');
            $offer->total_used = 0;
            $offer->is_active = Request::get('active');
            $offer->is_auto_add = Request::get('auto_add');
            $offer->save();
            $inserted_id = $offer->id;

            return Response::json([
                'error' => false,
                'created' => $inserted_id,
                'message' => 'Offer created',
            ], 201);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $offer = OfferModel::find($id);

        return Response::json([
            'error' => false,
            'offers' => $offer->toArray(),
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $offer = OfferModel::find($id);

        foreach ([
            'name'      => 'name',
            'required'  => 'required',
            'free'      => 'free_service',
            'active'    => 'is_active',
            'auto_add'  => 'is_auto_add',
        ] as $key => $value) {
            if (Request::get($key)) {
                $offer->$value = Request::get($key);
            }
        }

        $offer->save();

        return Response::json([
            'error' => false,
            'message' => 'Offer updated',
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $offer = OfferModel::find($id);
        $offer->delete();

        return Response::json([
            'error' => false,
            'message' => 'Offer deleted',
        ], 204);
    }

    public function useOffer($id)
    {
        $error = false;
        $message = '';
        $status = 0;

        $offer = OfferModel::find($id);

        if (Request::get('customer_id')) {
            $consumerId = Request::get('customer_id');
            $consumer = ConsumerModel::find($consumerId);
            $consumerTotalStamps = $consumer->total_stamps;

            if ($consumerTotalStamps !== '') {
                $consumerTotalStamps = json_decode($consumerTotalStamps, true);
                $consumerThisStamp = $consumerTotalStamps[$id];
                $consumerNoOfStamps = $consumerThisStamp[0];
                $consumerFreeService = $consumerThisStamp[1];

                if ($consumerFreeService > 0) {
                    $consumerFreeService -= 1;

                    $consumerTotalStamps[$id] = [$consumerNoOfStamps, $consumerFreeService];
                    $consumerTotalStamps = json_encode($consumerTotalStamps);

                    $transaction = new TransactionModel;
                    $transaction->user_id = Auth::user()->id;
                    $transaction->consumer_id = $consumerId;
                    $transaction->offer_id = $id;
                    $transaction->stamp = 0;
                    $transaction->free_service = -1;
                    $transaction->save();
                    $consumer->total_stamps = $consumerTotalStamps;
                    $consumer->save();

                    $error = false;
                    $message = 'Offer used';
                    $status = 200;
                } else {
                    $error = true;
                    $message = 'Not enough free service';
                    $status = 400;
                }
            } else {
                $error = true;
                $message = 'Not enough free service';
                $status = 400;
            }
        } else {
            $error = true;
            $message = 'Customer ID missing';
            $status = 400;
        }

        return Response::json([
            'error' => $error,
            'message' => $message,
        ], $status);
    }
}
