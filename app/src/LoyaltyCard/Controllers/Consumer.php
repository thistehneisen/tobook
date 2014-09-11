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
    private function viewIndex($search = '', $isApp = false) {
        $consumers = $this->consumerRepository->getAllConsumers($search, false);

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
        if (Input::get('act') != '') {
            $result = $this->consumerRepository->linkConsumer();

            if (is_object($result)) {
                return Response::json([
                    'success' => true,
                    'message' => 'Customer linked successfully!',
                ]);
            }
        } else {
            $result = $this->consumerRepository->storeConsumer(false);

            if (is_array($result)) {
                if (Request::ajax()) {
                    return Response::json([
                        'success' => false,
                        'errors' => $result,
                    ]);
                } else {
                    return Redirect::back()
                        ->withErrors($result)
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    public function show($id)
    {
        $consumer = $this->consumerRepository->showConsumer($id, false);

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
                $data = ['error' => trans('This customer does not exist')];
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
     * Receive AJAX request to add point to consumer
     * @param  int $consumerId
     * @param  int $points
     * @return Response
     */
    private function ajaxAddPoint($consumerId, $points)
    {
        $result = $this->consumerRepository->addPoint($consumerId, $points, false);

        if (is_array($result)) {
            return Response::json([
                'success' => false,
                'errors' => $result,
            ]);
        } elseif (is_object($result)) {
            return Response::json([
                'success' => true,
                'message' => 'Point added successfully',
                'points'  => $result->total_points,
            ]);
        }
    }

    /**
     * Receive AJAX request to use voucher with point
     * @param  int $consumerId
     * @param  int $voucherId
     * @return Response
     */
    private function ajaxUsePoint($consumerId, $voucherId)
    {
        $consumer = $this->consumerRepository->usePoint($consumerId, $voucherId, false);

        return Response::json([
            'success' => true,
            'message' => 'Point used successfully',
            'points'  => $consumer->total_points,
        ]);
    }

    /**
     * Receive AJAX request to add stamp to consumer
     * @param  int $consumerId
     * @param  int $offerId
     * @return Response
     */
    private function ajaxAddStamp($consumerId, $offerId)
    {
        $consumerNoOfStamps = $this->consumerRepository->addStamp($consumerId, $offerId, false);

        return Response::json([
            'success' => true,
            'message' => 'Stamp added successfully',
            'stamps'  => $consumerNoOfStamps,
        ]);
    }

    /**
     * Receive AJAX request to use offer with stamp
     * @param  int $consumerId
     * @param  int $offerId
     * @return Response
     */
    private function ajaxUseOffer($consumerId, $offerId)
    {
        $consumerNoOfStamps = $this->consumerRepository->useOffer($consumerId, $offerId, false);

        if ($consumerNoOfStamps === null) {
            return Response::json([
                'success' => false,
                'message' => 'Not enough stamp for this offer',
            ]);
        } else {
            return Response::json([
                'success' => true,
                'message' => 'Offer used successfully',
                'stamps'  => $consumerNoOfStamps,
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
        return $this->viewIndex(Input::get('search'), true);
    }
}
