<?php namespace App\LoyaltyCard\Controllers;

use View, Input, Confide, Config, Request, Response;
use App\Core\Controllers\Base;
use App\LoyaltyCard\Controllers\ConsumerRepository as ConsumerRepository;
use App\Consumers\Models\Consumer as Core;
use App\LoyaltyCard\Models\Consumer as Model;
use App\LoyaltyCard\Models\Voucher as VoucherModel;
use App\LoyaltyCard\Models\Offer as OfferModel;

class Consumer extends Base
{
    use \CRUD;

    protected $consumerRepository;
    protected $viewPath = 'modules.lc.consumers';
    protected $crudOptions = [
        'modelClass'    => 'App\LoyaltyCard\Models\Consumer',
        'langPrefix'    => 'loyalty-card.consumer',
        'layout'        => 'layouts.default',
        'indexFields'   => ['name', 'email', 'phone', 'updated_at'],
    ];

    public function __construct(ConsumerRepository $consumerRp)
    {
        parent::__construct();
        $this->consumerRepository = $consumerRp;
    }

    public function search()
    {
        $q = e(Input::get('q'));

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));

        $items = $this->consumerRepository->getAllConsumers($q, $perPage);

        // Disable sorting items
        $this->crudSortable = false;

        return $this->renderList($items);
    }

    protected function upsertHandler($item)
    {
        $core = $item->consumer;

        if ($core === null) {
            $duplicatedConsumer = $this->consumerRepository->getDuplicatedConsumer();
            $existedConsumer = $this->consumerRepository->getExistedConsumer();

            if ($duplicatedConsumer) {
                return;
            } elseif ($existedConsumer) {
                $core = Core::find($existedConsumer->id);
            } else {
                $core = Core::make(Input::all());
                $core->users()->detach($this->user->id);
            }

            $core->users()->attach($this->user, ['is_visible' => true]);
            $item->total_points = 0;
            $item->total_stamps = '';
            $item->user()->associate(Confide::user());
            $item->consumer()->associate($core);
            $item->saveOrFail();
        } else {
            $core->fill(Input::all());
            $core->save();
        }

        return $item;
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
     * Index for app
     * @return Response
     */
    public function appIndex() {
        return $this->viewIndex(Input::get('search'), true);
    }

    /**
     * Make view index for both app and BE
     * @return View
     */
    private function viewIndex($search = '', $isApp = false) {
        // To make sure that we only show records of current user
        $query = $this->getModel();

        // Allow to filter results in query string
        $query = $this->applyQueryStringFilter($query);

        // If this controller is sortable
        if ($this->getOlutOptions('sortable') === true) {
            $query = $query->orderBy('order');
        }

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        if ($isApp) {
            $items = $this->consumerRepository->getAllConsumers($search);

            return View::make('modules.lc.app.index')
                        ->with('items', $items);
        } else {
            return $this->renderList($items);
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
     * Store a newly created resource in storage.
     *
     * @return Response / Redirect
     */
    public function store()
    {
        $result = $this->consumerRepository->storeConsumer(false);

        return Response::json([
            'success'   => true,
            'result'    => $result,
        ]);
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
