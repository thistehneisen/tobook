<?php namespace App\API\LoyaltyCard\Controllers;
use Validator, Response, Request;
use Auth;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\API\LoyaltyCard\Models\Transaction as TransactionModel;
use App\Core\Controllers\Base as Base;

class Consumer extends Base
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the consumers
        $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                        ->where('user_id', Auth::user()->id)
                        ->get();

        return Response::json([
            'error' => false,
            'consumers' => $consumers->toArray(),
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
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email',
            'phone'         => 'required|numeric',
            'address'       => 'required',
            'postcode'      => 'required|numeric',
            'city'          => 'required',
            'country'       => 'required',
        ];

        $validator = Validator::make(Request::all(), $rules);

        if ($validator->fails()) {
            // $messages = $validator->messages();
            // /$data = [];

            // foreach ($rules as $key => $value) {
            //     if ($messages->has($key)) {
            //         $data[] = $key;
            //     }
            // }

            return Response::json([
                'error' => true,
                'message' => 'Invalid data',
                //'details'  => $data,
            ], 400);
        } else {
            try {
                // Create core consumer first
                $core = Model::createCore();
                $core->first_name = Request::get('first_name');
                $core->last_name  = Request::get('last_name');
                $core->user_id    = Auth::user()->id;
                $core->email      = Request::get('email');
                $core->phone      = Request::get('phone');
                $core->address    = Request::get('address');
                $core->postcode   = Request::get('postcode');
                $core->city       = Request::get('city');
                $core->country    = Request::get('country');
                $core->save();

                // Then create a LC consumer
                $consumer = new Model;
                $consumer->total_points = 0;
                $consumer->total_stamps = '';
                $consumer->consumer_id = $core->id;
                $consumer->consumer()->associate($core);
                $consumer->save();
                $inserted_id = $consumer->id;
            } catch (Exception $ex) {

            }

            return Response::json([
                'error' => false,
                'created' => $inserted_id,
                'message' => 'Consumer created',
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
        $consumer = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                            ->where('lc_consumers.consumer_id', $id)
                            ->get();

        return Response::json([
            'error' => false,
            'consumer' => $consumer->toArray(),
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
        $error = true;
        $message = '';
        $status = 0;

        if (Request::get('add_stamp') === '1') {
            if (Request::get('offer_id')) {
                $error = false;
                $message = '';
                $status = 0;

                $offerId = Request::get('offer_id');
                $offer = OfferModel::find($offerId);
                $offerRequired = $offer->required;
                $offerFreeService = $offer->free_service;

                $consumer = Model::find($id);
                $consumerTotalStamps = $consumer->total_stamps;

                $transaction = new TransactionModel;
                $transaction->user_id = Auth::user()->id;
                $transaction->consumer_id = $id;
                $transaction->offer_id = $offerId;

                if ($consumerTotalStamps !== '') {
                    $consumerTotalStamps = json_decode($consumerTotalStamps, true);
                    $consumerThisStamp = $consumerTotalStamps[$offerId];
                    $consumerNoOfStamps = $consumerThisStamp[0];
                    $consumerFreeService = $consumerThisStamp[1];

                    if ($offerRequired === $consumerNoOfStamps + 1) {
                        $consumerNoOfStamps = 0;
                        $consumerFreeService++;
                        $transaction->stamp = $offerRequired * (-1);
                        $transaction->free_service = $consumerFreeService;
                    } else {
                        $consumerNoOfStamps++;
                        $transaction->stamp = 1;
                        $transaction->free_service = 0;
                    }

                    $consumerTotalStamps[$offerId] = [$consumerNoOfStamps, $consumerFreeService];
                    $consumerTotalStamps = json_encode($consumerTotalStamps);
                } else {
                    $transaction->stamp = 1;
                    $transaction->free_service = 0;
                    $consumerTotalStamps = json_encode([$offerId => [1, 0]]);
                }

                $transaction->save();
                $consumer->total_stamps = $consumerTotalStamps;
                $consumer->save();

                $error = false;
                $message = 'Stamp added';
                $status = 200;
            } else {
                $error = true;
                $message = 'Offer ID missing';
                $status = 400;
            }
        } else {
            $consumer = Model::find($id);

            // $rules = [
            //     'email' => 'required|email',
            //     'phone' => 'required|numeric',
            //     'postcode'  => 'numeric',
            // ];

            // $validator = Validator::make(Request::all(), $rules);

            // if ($validator->fails()) {
            //     $messages = $validator->messages();
            //     $data = [];

            //     foreach ($rules as $key => $value) {
            //         if ($messages->has($key)) {
            //             $data[] = $key;
            //         }
            //     }

            //     return Response::json([
            //         'error' => true,
            //         'message' => 'Invalid data',
            //         // 'data'  => $data,
            //     ], 400);
            // } else {
                foreach (['first_name', 'last_name', 'email', 'phone', 'address', 'postcode', 'city', 'country'] as $key) {
                    if (Request::get($key)) {
                        $consumer->consumer->$key = Request::get($key);
                    }
                }

                $consumer->consumer->save();

                $error = false;
                $message = 'Consumer updated';
                $status = 201;
            // }
        }

        return Response::json([
            'error' => $error,
            'message' => $message,
        ], $status);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $consumer = Model::find($id);
            $consumer->consumer->delete();
            $consumer->delete();

        } catch (Exception $ex) {

        }

        return Response::json([
            'error' => false,
            'message' => 'Consumer deleted',
        ], 204);
    }
}
