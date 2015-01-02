<?php namespace App\API\v1_0\LoyaltyCard\Controllers;
use Validator, Response, Request;
use Auth;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Transaction as TransactionModel;
use App\Core\Controllers\Base as Base;
use App\LoyaltyCard\Controllers\ConsumerRepository;

class Consumer extends Base
{
    private $consumerRepository;

    public function __construct()
    {
        $this->consumerRepository = new ConsumerRepository(true);
    }

    /**
     * Display list of consumers.
     *
     * @return Response
     */
    public function index()
    {
        $consumers = $this->consumerRepository->getConsumers();

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

    /**
     * Store a newly created consumer in storage.
     *
     * @return Response
     */
    public function store()
    {
        $result = $this->consumerRepository->storeConsumer();

        if (is_array($result)) {
            return Response::json([
                'error' => true,
                'message' => 'Invalid data',
            ], 400);
        } elseif (is_object($result)) {
            return Response::json([
                'error' => false,
                'created' => $result->id,
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
        // $consumer = Model::find($id);
        $consumer = $this->consumerRepository->showConsumer($id);

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

        $consumer = Model::join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                        ->where('consumer_user.user_id', Auth::user()->id)
                        ->where('id', $id)
                        ->first();

        if ($consumer) {
            if (Request::get('add_stamp') === '1') {
                if (Request::get('offer_id')) {
                    $offerId = Request::get('offer_id');
                    $offer = OfferModel::where('user_id', Auth::user()->id)
                                    ->where('id', $offerId)
                                    ->first();

                    if (!$offer) {
                        $message = 'Offer not found';
                        $status = 404;
                    }

                    if ($offer) {
                        $this->consumerRepository->addStamp(true, $id, $offerId);
                        $error = false;
                        $message = 'Stamp added';
                        $status = 200;
                    }
                } else {
                    $message = 'Offer ID missing';
                    $status = 400;
                }
            } elseif (Request::get('use_offer') === '1') {
                if (Request::get('offer_id')) {
                    $offerId = Request::get('offer_id');
                    $offer = OfferModel::where('user_id', Auth::user()->id)
                                    ->where('id', $offerId)
                                    ->first();

                    if (!$offer) {
                        $message = 'Offer not found';
                        $status = 404;
                    }

                    if ($offer) {
                        $result = $this->consumerRepository->useOffer(true, $id, $offerId);

                        if ($result === null) {
                            $message = 'Not enough stamp';
                            $status = 404;
                        } else {
                            $error = false;
                            $message = 'Offer used';
                            $status = 200;
                        }
                    }
                } else {
                    $message = 'Offer ID missing';
                    $status = 400;
                }
            }  else {
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
        } else {
            $message = 'Customer not found';
            $status = 404;
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
