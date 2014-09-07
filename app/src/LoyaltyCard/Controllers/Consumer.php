<?php namespace App\LoyaltyCard\Controllers;

use Input, Session, Redirect, View, Validator, Request, Response;
use Confide;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\Core\Controllers\Base as Base;
use App\LoyaltyCard\Models\Transaction as TransactionModel;

class Consumer extends Base
{
    /**
     * Make view index for both app and BE
     * @return Response
     */
    private function viewIndex($isApp = false, $search = null) {
        if ($search != null) {
            $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                            ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                            ->where('consumer_user.user_id', Confide::user()->id)
                            ->where('consumers.first_name', 'like', '%' . $search . '%')
                            ->orWhere('consumers.last_name', 'like', '%' . $search . '%')
                            ->orWhere('consumers.email', 'like', '%' . $search . '%')
                            ->orWhere('consumers.phone', 'like', '%' . $search . '%')
                            ->select('lc_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'lc_consumers.updated_at')
                            ->paginate(10);
        } else {
            $consumers = Model::join('consumers', 'lc_consumers.consumer_id', '=', 'consumers.id')
                            ->join('consumer_user', 'lc_consumers.consumer_id', '=', 'consumer_user.consumer_id')
                            ->where('consumer_user.user_id', Confide::user()->id)
                            ->select('lc_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'lc_consumers.updated_at')
                            ->paginate(10);
        }

        $viewName = $isApp ? 'modules.lc.app.index' : 'modules.lc.consumers.index';
        return View::make($viewName)
            ->with('consumers', $consumers);
    }

    /**
     * Display a listing of the resource.
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
     * @return Response
     */
    public function create()
    {
        return View::make('modules.lc.consumers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
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

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            if (Request::ajax()) {
                return Response::json([
                    'success' => false,
                    'errors' => $validator->errors()->toArray(),
                ]);
            }

            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'first_name'    => Input::get('first_name'),
                'last_name'     => Input::get('last_name'),
                'email'         => Input::get('email'),
                'phone'         => Input::get('phone'),
                'address'       => Input::get('address'),
                'postcode'      => Input::get('postcode'),
                'city'          => Input::get('city'),
                'country'       => Input::get('country'),
            ];

            // Create core consumer first
            $core = Core::make($data, Confide::user()->id);

            // Then create a LC consumer
            $consumer = new Model;
            $consumer->total_points = 0;
            $consumer->total_stamps = '';
            $consumer->consumer_id = $core->id;
            $consumer->consumer()->associate($core);
            $consumer->save();
        } catch (Exception $ex) {

        }

        if (Request::ajax()) {
            return Response::json([
                'success' => true,
                'message' => 'Customer created successfully!',
            ]);
        }

        return Redirect::route('lc.consumers.index');
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


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if (Request::ajax()) {
            $consumer = Model::find($id);

            $transaction = new TransactionModel;
            $transaction->user_id = Confide::user()->id;
            $transaction->consumer_id = $id;

            switch (Input::get('action')) {
                case 'addPoint':
                    $rules = [
                        'points' => 'required|numeric',
                    ];

                    $validator = Validator::make(Input::all(), $rules);

                    if ($validator->fails()) {
                        return Response::json([
                            'success' => false,
                            'errors' => $validator->errors()->toArray(),
                        ]);
                    } else {
                        $transaction->point = Input::get('points');
                        $transaction->save();

                        $consumer->total_points += Input::get('points');
                        $consumer->save();

                        return Response::json([
                            'success' => true,
                            'message' => 'Point added successfully',
                            'points'  => $consumer->total_points,
                        ]);
                    }
                    break;

                case 'usePoint':
                    $voucher = VoucherModel::find(Input::get('voucherID'));
                    $transaction->voucher_id = Input::get('voucherID');
                    $transaction->point = $voucher->required * -1;
                    $transaction->save();

                    $consumer->total_points -= $voucher->required;
                    $consumer->save();

                    return Response::json([
                        'success' => true,
                        'message' => 'Point used successfully',
                        'points'  => $consumer->total_points,
                    ]);
                    break;

                case 'addStamp':
                    $offer = OfferModel::find(Input::get('offerID'));
                    $transaction->offer_id = Input::get('offerID');
                    $consumerTotalStamps = $consumer->total_stamps;

                    if ($consumerTotalStamps !== '') {
                        $consumerTotalStamps = json_decode($consumerTotalStamps, true);

                        if (array_key_exists(Input::get('offerID'), $consumerTotalStamps)) {
                            $consumerThisStamp = $consumerTotalStamps[Input::get('offerID')];
                        } else {
                            $consumerThisStamp = [0,0];
                        }

                        $consumerNoOfStamps = $consumerThisStamp[0];
                        $consumerFreeService = $consumerThisStamp[1];

                        if ($offer->required === $consumerNoOfStamps + 1) {
                            $consumerNoOfStamps = 0;
                            $consumerFreeService++;
                            $transaction->stamp = $offer->required * (-1);
                            $transaction->free_service = $consumerFreeService;
                        } else {
                            $consumerNoOfStamps++;
                            $transaction->stamp = 1;
                            $transaction->free_service = 0;
                        }

                        $consumerTotalStamps[Input::get('offerID')] = [$consumerNoOfStamps, $consumerFreeService];
                        $consumerTotalStamps = json_encode($consumerTotalStamps);
                    } else {
                        $consumerNoOfStamps = 1;
                        $consumerFreeService = 0;
                        $transaction->stamp = 1;
                        $transaction->free_service = 0;
                        $consumerTotalStamps = json_encode([Input::get('offerID') => [1, 0]]);
                    }

                    $transaction->save();
                    $consumer->total_stamps = $consumerTotalStamps;
                    $consumer->save();

                    return Response::json([
                        'success' => true,
                        'message' => 'Stamp added successfully',
                        'stamps'  => $consumerNoOfStamps,
                        'free'    => $consumerFreeService,
                    ]);

                    break;

                case 'useOffer':
                    $offerID = Input::get('offerID');
                    $consumerTotalStamps = $consumer->total_stamps;

                    if ($consumerTotalStamps !== '') {
                        $consumerTotalStamps = json_decode($consumerTotalStamps, true);

                        if (array_key_exists($offerID, $consumerTotalStamps)) {
                            $consumerThisStamp = $consumerTotalStamps[$offerID];
                            $consumerNoOfStamps = $consumerThisStamp[0];
                            $consumerFreeService = $consumerThisStamp[1];

                            if ($consumerFreeService > 0) {
                                $consumerFreeService -= 1;

                                $consumerTotalStamps[$offerID] = [$consumerNoOfStamps, $consumerFreeService];
                                $consumerTotalStamps = json_encode($consumerTotalStamps);

                                $transaction->offer_id = $offerID;
                                $transaction->stamp = 0;
                                $transaction->free_service = -1;
                                $transaction->save();
                                $consumer->total_stamps = $consumerTotalStamps;
                                $consumer->save();

                                return Response::json([
                                    'success' => true,
                                    'message' => 'Offer used successfully',
                                    'free'    => $consumerFreeService,
                                ]);
                            } else {
                                return Response::json([
                                    'success' => false,
                                    'message' => 'No free service of this offer',
                                ]);
                            }
                        } else {
                            return Response::json([
                                'success' => false,
                                'message' => 'No free service of this offer',
                            ]);
                        }
                    } else {
                        return Response::json([
                            'success' => false,
                            'message' => 'No offer to use',
                        ]);
                    }
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
