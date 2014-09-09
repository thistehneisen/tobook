<?php namespace App\API\v1_0\LoyaltyCard\Controllers;
use Validator, Response, Request;
use Auth;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Transaction as TransactionModel;
use App\Core\Controllers\Base as Base;
use App\LoyaltyCard\Controllers\ConsumerRepository as ConsumerRepository;

class Consumer extends Base
{
    protected $consumerRepository;

    public function __construct(ConsumerRepository $consumerRp)
    {
        $this->consumerRepository = $consumerRp;
    }

    /**
     * Display list of consumers.
     *
     * @return Response
     */
    public function index()
    {
        $consumers = $this->consumerRepository->getAllConsumers(true);

        if ($consumers->toArray()) {
            return Response::json([
                'error' => false,
                'consumers' => $consumers->toArray(),
            ], 200);
        } else {
            return Response::json([
                'error' => true,
                'message' => 'No customer found',
            ], 404);
        }
    }


    // public function index()
    // {
    //     // get all the consumers
    //     $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
    //                     ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
    //                     ->where('consumer_user.user_id', Auth::user()->id)
    //                     ->get();

    //     if ($consumers->toArray()) {
    //         return Response::json([
    //             'error' => false,
    //             'consumers' => $consumers->toArray(),
    //         ], 200);
    //     } else {
    //         return Response::json([
    //             'error' => true,
    //             'message' => 'No customer found',
    //         ], 404);
    //     }
    // }


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
            'email'         => 'required|email|unique:consumers',
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
                $data = [
                    'first_name'    => Request::get('first_name'),
                    'last_name'     => Request::get('last_name'),
                    'email'         => Request::get('email'),
                    'phone'         => Request::get('phone'),
                    'address'       => Request::get('address'),
                    'postcode'      => Request::get('postcode'),
                    'city'          => Request::get('city'),
                    'country'       => Request::get('country'),
                ];

                // Create core consumer first
                $core = Core::make($data, Auth::user()->id);

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

        if ($consumer) {
            return Response::json([
                'error' => false,
                'consumer' => $consumer->toArray(),
            ], 200);
        } else {
            return Response::json([
                'error' => true,
                'message' => 'Customer not found',
            ], 404);
        }
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

                if ($offer) {
                    $offerRequired = $offer->required;
                    $offerFreeService = $offer->free_service;

                    $consumer = Model::find($id);

                    if ($consumer) {
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
                        $message = 'Customer not found';
                        $status = 404;
                    }
                } else {
                    $message = 'Offer not found';
                    $status = 404;
                }
            } else {
                $error = true;
                $message = 'Offer ID missing';
                $status = 400;
            }
        } else {
            $consumer = Model::find($id);

            if ($consumer) {
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
            } else {
                $message = 'Customer not found';
                $status = 404;
            }
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
            $core = Core::find($id);
            $core->hide(Auth::user()->id);
            $consumer = Model::find($id);
            $consumer->delete();

        } catch (Exception $ex) {

        }

        return Response::json([
            'error' => false,
            'message' => 'Consumer deleted',
        ], 204);
    }
}
