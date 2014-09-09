<?php namespace App\LoyaltyCard\Controllers;

use Input, Session, Redirect, View, Validator, Request, Response;
use Confide;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\Core\Controllers\Base as Base;
use App\LoyaltyCard\Models\Transaction as TransactionModel;
use App\LoyaltyCard\Controllers\ConsumerRepository as ConsumerRepository;

class Consumer extends Base
{
    protected $consumerRepository;

    public function __construct(ConsumerRepository $consumerRp)
    {
        $this->consumerRepository = $consumerRp;
    }

    /**
     * Make view index for both app and BE
     * @return View
     */
    private function viewIndex($isApp = false, $search = null) {
        $consumers = $this->consumerRepository->getAllConsumers(false, $search);

        $viewName = $isApp ? 'modules.lc.app.index' : 'modules.lc.consumers.index';
        return View::make($viewName)
            ->with('consumers', $consumers);
    }

    /**
     * Display list of consumers.
     *
     * @return View
     */
    public function index()
    {
        return $this->viewIndex();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return View::make('modules.lc.consumers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response / Redirect
     */
    public function store()
    {
        $validator = null;

        $consumer = $this->consumerRepository->storeConsumer(false, $validator);

        if ($consumer === null) {
            if (Request::ajax()) {
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors()->toArray(),
                ]);
            } else {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
        } else {
            if (Request::ajax()) {
                return Response::json([
                    'success' => true,
                    'message' => 'Customer created successfully!',
                ]);
            } else {
                return Redirect::route('lc.consumers.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $consumer = Model::find($id);

        if (Request::ajax()) {
            if ($consumer) {
                $vouchers = VoucherModel::where('user_id', Confide::user()->id)->get();
                $offers = OfferModel::where('user_id', Confide::user()->id)->get();
                $consumerTotalStamps = $consumer->total_stamps;

                if ($consumerTotalStamps === '') {
                    $stampInfo = null;
                } else {
                    $stampInfo = json_decode($consumerTotalStamps, true);
                }

                $data = [
                    'consumer' => $consumer,
                    'offers' => $offers,
                    'vouchers' => $vouchers,
                    'stampInfo' => $stampInfo,
                ];
            } else {
                $data = ['error' => trans('This consumer does not exist')];
            }

            return View::make('modules.lc.app.show', $data);
        }

        // what if it's not AJAX?
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $consumer = Model::find($id);

        return View::make('modules.lc.consumers.edit')
            ->with('consumer', $consumer);
    }

    private function ajaxAddPoint($consumerID, $points)
    {
        $rules = [
            'points' => 'required|numeric',
        ];

        $validator = Validator::make(['points' => $points], $rules);

        // INVALID POINT
        if ($validator->fails()) {
            return Response::json([
                'success' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        // ADD POINT
        } else {
            $consumer = Model::find($consumerID);
            $consumer->total_points += $points;
            $consumer->save();

            $transaction = new TransactionModel;
            $transaction->user_id = Confide::user()->id;
            $transaction->consumer_id = $consumerID;
            $transaction->point = $points;
            $transaction->save();

            return Response::json([
                'success' => true,
                'message' => 'Point added successfully',
                'points'  => $consumer->total_points,
            ]);
        }
    }

    private function ajaxUsePoint($consumerID, $voucherID)
    {
        $voucher = VoucherModel::find($voucherID);

        $consumer = Model::find($consumerID);
        $consumer->total_points -= $voucher->required;
        $consumer->save();

        $transaction = new TransactionModel;
        $transaction->user_id = Confide::user()->id;
        $transaction->consumer_id = $consumerID;
        $transaction->voucher_id = $voucherID;
        $transaction->point = $voucher->required * -1;
        $transaction->save();

        return Response::json([
            'success' => true,
            'message' => 'Point used successfully',
            'points'  => $consumer->total_points,
        ]);
    }

    private function ajaxAddStamp($consumerID, $offerID)
    {
        $offer = OfferModel::find($offerID);

        $transaction = new TransactionModel;
        $transaction->user_id = Confide::user()->id;
        $transaction->consumer_id = $consumerID;
        $transaction->offer_id = $offerID;

        $consumer = Model::find($consumerID);
        $consumerTotalStamps = $consumer->total_stamps;

        if ($consumerTotalStamps !== '') {
            $consumerTotalStamps = json_decode($consumerTotalStamps, true);

            if (array_key_exists(Input::get('offerID'), $consumerTotalStamps)) {
                // CONSUMER HAS STAMP OF THIS OFFER ALREADY
                $consumerNoOfStamps = $consumerTotalStamps[$offerID];
                $consumerNoOfStamps += 1;
            } else {
                // CONSUMER DOES NOT HAVE STAMP OF THIS OFFER
                $consumerNoOfStamps = 1;
            }

            $consumerTotalStamps[$offerID] = $consumerNoOfStamps;
            $consumerTotalStamps = json_encode($consumerTotalStamps);
        } else {
            // NO STAMP AT ALL
            $consumerNoOfStamps = 1;
            $consumerTotalStamps = json_encode([$offerID => 1]);
        }

        $transaction->stamp = 1;
        $transaction->save();
        $consumer->total_stamps = $consumerTotalStamps;
        $consumer->save();

        return Response::json([
            'success' => true,
            'message' => 'Stamp added successfully',
            'stamps'  => $consumerNoOfStamps,
        ]);
    }

    private function ajaxUseOffer($consumerID, $offerID)
    {
        $offer = OfferModel::find($offerID);

        $transaction = new TransactionModel;
        $transaction->user_id = Confide::user()->id;
        $transaction->consumer_id = $consumerID;
        $transaction->offer_id = $offerID;

        $consumer = Model::find($consumerID);
        $consumerTotalStamps = $consumer->total_stamps;

        if ($consumerTotalStamps !== '') {
            $consumerTotalStamps = json_decode($consumerTotalStamps, true);

            if (array_key_exists($offerID, $consumerTotalStamps)) {
                // HAVE STAMP OF THIS OFFER
                $consumerNoOfStamps = $consumerTotalStamps[$offerID];

                if ($consumerNoOfStamps >= $offer->required) {
                    // ENOUGHT STAMP TO USE OFFER
                    $consumerNoOfStamps -= $offer->required;

                    $consumerTotalStamps[$offerID] = $consumerNoOfStamps;
                    $consumerTotalStamps = json_encode($consumerTotalStamps);

                    $transaction->offer_id = $offerID;
                    $transaction->stamp = $offer->required * -1;
                    $transaction->save();
                    $consumer->total_stamps = $consumerTotalStamps;
                    $consumer->save();

                    return Response::json([
                        'success' => true,
                        'message' => 'Offer used successfully',
                        'stamps'  => $consumerNoOfStamps,
                    ]);
                } else {
                    return Response::json([
                        'success' => false,
                        'message' => 'Not enough stamp for this offer',
                    ]);
                }
            } else {
                return Response::json([
                    'success' => false,
                    'message' => 'Not enough stamp for this offer',
                ]);
            }
        } else {
            return Response::json([
                'success' => false,
                'message' => 'No offer to use',
            ]);
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
        if (Request::ajax()) {
            switch (Input::get('action')) {
                case 'addPoint':
                    return $this->ajaxAddPoint($id, Input::get('points'));
                    break;

                case 'usePoint':
                    return $this->ajaxUsePoint($id, Input::get('voucherID'));
                    break;

                // ADD STAMP TO CONSUMER
                case 'addStamp':
                    return $this->ajaxAddStamp($id, Input::get('offerID'));
                    break;

                // USE OFFER
                case 'useOffer':
                    return $this->ajaxUseOffer($id, Input::get('offerID'));
                    break;
            }
        } else {
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

            $validator = Validator::make(Input::all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput(Input::all());
            } else {
                $consumer = Model::find($id)->consumer;
                $consumer->first_name = Input::get('first_name');
                $consumer->last_name = Input::get('last_name');
                $consumer->email = Input::get('email');
                $consumer->phone = Input::get('phone');
                $consumer->address = Input::get('address');
                $consumer->postcode = Input::get('postcode');
                $consumer->city = Input::get('city');
                $consumer->country = Input::get('country');
                $consumer->save();

                return Redirect::route('lc.consumers.index');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $core = Core::find($id);
        $core->hide(Confide::user()->id);
        $consumer = Model::find($id);
        $consumer->delete();

        if (Request::ajax()) {
            Response::json([
                'success' => true,
            ]);
        }

        return Redirect::route('lc.consumers.index');
    }

    /**
     * Index for app
     * @return Response
     */
    public function appIndex() {
        return $this->viewIndex(true, Input::get('search'));
    }
}
