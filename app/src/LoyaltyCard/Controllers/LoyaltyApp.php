<?php namespace App\LoyaltyCard\Controllers;

use View, Input, Confide, Config, Request, Response;
use App\Core\Controllers\Base;
use App\LoyaltyCard\Controllers\ConsumerRepository;
use App\Consumers\Models\Consumer as CoreConsumer;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;
use App\LoyaltyCard\Models\Consumer as LcConsumer;

class LoyaltyApp extends Base
{
    use \CRUD;

    private $consumerRepository;
    protected $crudOptions = [
        'modelClass'    => 'App\LoyaltyCard\Models\Consumer',
        'langPrefix'    => 'lc.consumer',
        'layout'        => 'layouts.default',
        'indexFields'   => ['name', 'email', 'phone', 'updated_at'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->consumerRepository = new ConsumerRepository();
    }

    /**
     * View index for app
     * @return View
     */
    protected function index()
    {
        $items = $this->consumerRepository->getConsumers(Input::get('search'));

        return View::make('modules.lc.app.index')
            ->with('items', $items);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return View
     */
    protected function show($id, $coreId)
    {
        $lcConsumer = null;
        $consumer = null;
        if ($coreId > 0) {
            $consumer = CoreConsumer::ofCurrentUser()->find($coreId);
        } else {
            $lcConsumer = LcConsumer::ofCurrentUser()->find($id);
            if (!empty($lcConsumer)) {
                $consumer = $lcConsumer->consumer;
            }
        }

        if (Request::ajax()) {
            if ($consumer) {
                // create LC consumer if needed
                $lcConsumer = LcConsumer::makeOrGet($consumer, Confide::user()->id);
                $vouchers = VoucherModel::ofCurrentUser()->where('is_active', true)->get();
                $offers = OfferModel::ofCurrentUser()->where('is_active', true)->get();
                $stampInfo = json_decode($lcConsumer->total_stamps, true);
                $pointInfo = $lcConsumer->total_points;

                $data = [
                    'consumer' => $consumer,
                    'lcConsumer' => $lcConsumer,
                    'offers' => $offers,
                    'vouchers' => $vouchers,
                    'stampInfo' => $stampInfo,
                    'pointInfo' => $pointInfo
                ];
            } else {
                $data = ['error' => trans('lc.error.consumer_not_exist')];
            }

            return View::make('modules.lc.app.show', $data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response / Redirect
     */
    public function store()
    {
        $result = $this->consumerRepository->storeConsumer(Input::all());

        return Response::json([
            'success'   => true,
            'result'    => $result,
        ]);
    }

    /**
     * Receive AJAX request to add point to consumer
     * @param  int      $consumerId
     * @param  int      $points
     * @return Response
     */
    private function ajaxAddPoint($consumerId, $points)
    {
        $result = $this->consumerRepository->addPoint($consumerId, $points);

        if (is_array($result)) {
            return Response::json([
                'success' => false,
                'errors' => $result,
            ]);
        } elseif (is_object($result)) {
            return Response::json([
                'success' => true,
                'message' => trans('lc.success.point_added'),
                'points'  => $result->lc->total_points,
            ]);
        }
    }

    /**
     * Receive AJAX request to use voucher with point
     * @param  int      $consumerId
     * @param  int      $voucherId
     * @return Response
     */
    private function ajaxUsePoint($consumerId, $voucherId)
    {
        $consumer = $this->consumerRepository->usePoint($consumerId, $voucherId);

        return Response::json([
            'success' => true,
            'message' => trans('lc.success.point_used'),
            'points'  => $consumer->lc->total_points,
        ]);
    }

    /**
     * Receive AJAX request to add stamp to consumer
     * @param  int      $consumerId
     * @param  int      $offerId
     * @return Response
     */
    private function ajaxAddStamp($consumerId, $offerId)
    {
        $consumerNoOfStamps = $this->consumerRepository->addStamp($consumerId, $offerId);

        return Response::json([
            'success' => true,
            'message' => trans('lc.success.stamp_added'),
            'stamps'  => $consumerNoOfStamps,
        ]);
    }

    /**
     * Receive AJAX request to use offer with stamp
     * @param  int      $consumerId
     * @param  int      $offerId
     * @return Response
     */
    private function ajaxUseOffer($consumerId, $offerId)
    {
        $consumerNoOfStamps = $this->consumerRepository->useOffer($consumerId, $offerId);

        if ($consumerNoOfStamps === null) {
            return Response::json([
                'success' => false,
                'message' => trans('lc.error.not_enough_point'),
            ]);
        } else {
            return Response::json([
                'success' => true,
                'message' => trans('lc.success.offer_used'),
                'stamps'  => $consumerNoOfStamps,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
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

                case 'addStamp':
                    return $this->ajaxAddStamp($id, Input::get('offerID'));
                    break;

                case 'useOffer':
                    return $this->ajaxUseOffer($id, Input::get('offerID'));
                    break;
            }
        }
    }
}
