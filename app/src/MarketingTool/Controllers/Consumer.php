<?php namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator;
use App\Consumers\Models\Consumer as ConsumerModel;
use App\MarketingTool\Models\Campaign as CampaignModel;
use App\MarketingTool\Models\ConsumerUser as ConsumerUserModel;
use App\MarketingTool\Models\Sms as SmsModel;
use Confide;

class Consumer extends \App\Core\Controllers\Base {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // get all the consumers
        $consumers = ConsumerUserModel::join('consumers', 'consumer_user.consumer_id', '=', 'consumers.id')
                ->where('consumer_user.user_id', Confide::user()->id)
                ->where('consumer_user.is_visible', true)
                ->paginate(20);
        
        $campaigns = CampaignModel::where('user_id', '=', Confide::user()->id)
                        ->where('status', '=', 'DRAFT')
                        ->get();
        $sms = SmsModel::where('user_id', '=', Confide::user()->id)
                        ->where('status', '=', 'DRAFT')
                        ->get();

        // load the view and pass the consumers
        return View::make('modules.mt.consumers.index')
            ->with('consumers', $consumers)
            ->with('campaigns', $campaigns)
            ->with('sms', $sms);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $consumer = ConsumerModel::find($id);

        return View::make('modules.mt.consumers.show')
            ->with('consumer', $consumer);
    }
}
